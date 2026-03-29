<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class ReservationController extends Controller
{
    /**
     * Listar todas las reservas y preparar eventos para el calendario
     */
    public function index()
    {
        $reservations = Reservation::with('room')->latest()->get();

        $events = $reservations->map(function($res) {
            $color = match($res->status) {
                'confirmed' => '#10b981', // Verde
                'pending'   => '#f59e0b', // Ámbar
                'cancelled' => '#ef4444', // Rojo
                default     => '#64748b',
            };

            return [
                'id'    => $res->id,
                'title' => $res->room->name . ' - ' . $res->customer_name,
                'start' => $res->check_in,
                'end'   => Carbon::parse($res->check_out)->addDay()->format('Y-m-d'),
                'color' => $color,
                'extendedProps' => [
                    'customer' => $res->customer_name,
                    'phone'    => $res->customer_phone,
                    'status'   => $res->status
                ]
            ];
        });

        return view('admin.reservations.index', compact('reservations', 'events'));
    }

    /**
     * Actualizar manualmente el estado de la reserva
     */
    public function updateStatus(Reservation $reservation, $status)
    {
        if (in_array($status, ['confirmed', 'pending', 'cancelled'])) {
            $reservation->update(['status' => $status]);
            
            $estadoTexto = match($status) {
                'confirmed' => 'CONFIRMADA',
                'pending'   => 'PENDIENTE',
                'cancelled' => 'CANCELADA',
            };

            return back()->with('success', "Folio {$reservation->folio} actualizado a: {$estadoTexto}");
        }

        return back()->with('error', 'El estado solicitado no es válido.');
    }

    /**
     * Generar PDF del contrato
     */
    public function downloadContract(Reservation $reservation)
    {
        $reservation->load('room');

        $logoPath = public_path('images/logo.png');
        $logoBase64 = '';
        if (file_exists($logoPath)) {
            $logoData = base64_encode(file_get_contents($logoPath));
            $logoBase64 = 'data:image/' . pathinfo($logoPath, PATHINFO_EXTENSION) . ';base64,' . $logoData;
        }

        $data = [
            'logo'           => $logoBase64,
            'reservation'    => $reservation,
            'check_in'       => \Carbon\Carbon::parse($reservation->check_in),
            'check_out'      => \Carbon\Carbon::parse($reservation->check_out),
            'establishment'  => 'Palapa “La Casona”',
            'representative' => 'María Magdalena Cruz García',
            'city'           => 'San Pedro Pochutla, Oaxaca'
        ];

        $pdf = \Pdf::loadView('admin.reservations.contract', $data)
                    ->setPaper('letter', 'portrait');

        return $pdf->download('Contrato_' . $reservation->folio . '.pdf');
    }

    /**
     * Muestra el formulario de búsqueda limpio
     */
    public function checkAvailability()
    {
        // Solo retornamos la vista, la búsqueda se hace en 'verify'
        return view('admin.reservations.check');
    }

    /**
     * LÓGICA DE BÚSQUEDA: Filtra por FECHAS y CAPACIDAD
     */
    public function verify(Request $request)
    {
        $request->validate([
            'check_in'  => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
            'guests'    => 'required|integer|min:1', // Validamos número de personas
        ]);

        $checkIn = $request->check_in;
        $checkOut = $request->check_out;
        $guests = $request->guests;

        // Buscamos habitaciones que:
        // 1. Tengan capacidad suficiente (>= guests)
        // 2. NO tengan reservas activas (no canceladas) que se solapen con las fechas solicitadas
        // 3. Traemos las imágenes para el carrusel (Eager Loading)
        $availableRooms = Room::with('images') // <--- CAMBIO AQUÍ: Cargamos la galería
            ->where('capacity', '>=', $guests)
            ->whereDoesntHave('reservations', function ($query) use ($checkIn, $checkOut) {
                $query->where('status', '!=', 'cancelled')
                      ->where(function ($q) use ($checkIn, $checkOut) {
                          $q->whereBetween('check_in', [$checkIn, $checkOut])
                            ->orWhereBetween('check_out', [$checkIn, $checkOut])
                            ->orWhere(function ($sub) use ($checkIn, $checkOut) {
                                $sub->where('check_in', '<', $checkIn)
                                    ->where('check_out', '>', $checkOut);
                            });
                      });
            })
            ->get();

        // Retornamos los resultados a la misma vista
        return view('admin.reservations.check', compact('availableRooms', 'checkIn', 'checkOut', 'guests'));
    }

    /**
     * Guardar la reserva (Estado inicial: PENDIENTE)
     */
    public function store(Request $request)
    {
        $request->validate([
            'room_id'        => 'required|exists:rooms,id',
            'customer_name'  => 'required|string|max:255',
            'customer_email' => 'required|email',
            'customer_phone' => 'required|string|max:20',
            'check_in'       => 'required|date',
            'check_out'      => 'required|date',
        ]);

        $room = Room::findOrFail($request->room_id);
        $checkIn = Carbon::parse($request->check_in);
        $checkOut = Carbon::parse($request->check_out);
        $dias = $checkIn->diffInDays($checkOut);
        
        $total = $room->price_per_night * ($dias ?: 1);

        // Creamos la reserva como PENDIENTE
        Reservation::create(array_merge($request->all(), [
            'total_price' => $total,
            'status'      => 'pending' // <--- IMPORTANTE: Inicia pendiente de pago
        ]));

        return redirect()->route('reservations.index')
            ->with('success', 'Reserva registrada. Estado: PENDIENTE (Verificar pago para confirmar).');
    }

    public function edit(Reservation $reservation)
    {
        $rooms = Room::all();
        return view('admin.reservations.edit', compact('reservation', 'rooms'));
    }

    public function update(Request $request, Reservation $reservation)
    {
        $request->validate([
            'customer_name'  => 'required|string|max:255',
            'customer_email' => 'required|email',
            'customer_phone' => 'required|string|max:20',
            'status'         => 'required|in:pending,confirmed,cancelled'
        ]);

        $reservation->update($request->all());
        return redirect()->route('reservations.index')->with('success', 'Datos actualizados.');
    }

    public function destroy(Reservation $reservation)
    {
        $reservation->delete();
        return redirect()->route('reservations.index')->with('success', 'Reserva eliminada.');
    }
}