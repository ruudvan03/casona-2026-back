<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class EventController extends Controller
{
    /**
     * Muestra el calendario y el listado de eventos de la Palapa.
     */
    public function index()
    {
        $events = Event::latest()->get();

        // Formateamos los eventos para FullCalendar
        $calendarEvents = $events->map(function($e) {
            return [
                'id'    => $e->id,
                'title' => "[$e->event_type] $e->customer_name",
                'start' => $e->event_date . 'T' . $e->start_time,
                'end'   => $e->event_date . 'T' . $e->end_time,
                // Color según estado: Confirmado (Verde), Cancelado (Rojo), Pendiente (Ámbar)
                'color' => $e->status == 'confirmed' ? '#10b981' : ($e->status == 'cancelled' ? '#ef4444' : '#f59e0b'),
                'extendedProps' => [
                    'folio' => $e->folio,
                    'phone' => $e->customer_phone,
                    'type'  => $e->event_type
                ]
            ];
        });

        return view('admin.events.index', compact('events', 'calendarEvents'));
    }

    /**
     * Almacena un nuevo evento con lógica de anticipo automática y validación de horarios.
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_name'  => 'required|string|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'event_type'     => 'required|string',
            'event_date'     => 'required|date|after_or_equal:today',
            'start_time'     => 'required',
            'end_time'       => 'required|after:start_time',
            'total_price'    => 'required|numeric|min:0',
            'down_payment'   => 'nullable|numeric|min:0',
        ]);

        // 1. Validar que no haya choques de horario el mismo día
        $overlap = Event::where('event_date', $request->event_date)
            ->where('status', '!=', 'cancelled')
            ->where(function ($query) use ($request) {
                $query->whereBetween('start_time', [$request->start_time, $request->end_time])
                      ->orWhereBetween('end_time', [$request->start_time, $request->end_time])
                      ->orWhere(function ($q) use ($request) {
                          $q->where('start_time', '<=', $request->start_time)
                            ->where('end_time', '>=', $request->end_time);
                      });
            })->exists();

        if ($overlap) {
            return back()->withInput()->with('error', '¡Atención! Ya existe un evento agendado que choca con este horario.');
        }

        // 2. Preparar datos y forzar cálculo del 50% si el anticipo está vacío o es 0
        $data = $request->all();

        if (!$request->filled('down_payment') || (float)$request->down_payment <= 0) {
            $data['down_payment'] = (float)$request->total_price * 0.5;
        }

        Event::create($data);

        return redirect()->route('events.index')->with('success', 'Evento agendado. Se ha registrado el anticipo correctamente.');
    }

    /**
     * Genera y descarga el contrato del evento en formato PDF con soporte para español.
     */
    public function downloadContract(Event $event)
    {
        // Procesamiento del logo para el PDF (Base64 para evitar errores de ruta)
        $logoPath = public_path('images/logo.png');
        $logoBase64 = '';
        if (file_exists($logoPath)) {
            $logoData = base64_encode(file_get_contents($logoPath));
            $logoBase64 = 'data:image/' . pathinfo($logoPath, PATHINFO_EXTENSION) . ';base64,' . $logoData;
        }

        // Diccionario para meses en español
        $meses = [
            'January' => 'Enero', 'February' => 'Febrero', 'March' => 'Marzo', 'April' => 'Abril',
            'May' => 'Mayo', 'June' => 'Junio', 'July' => 'Julio', 'August' => 'Agosto',
            'September' => 'Septiembre', 'October' => 'Octubre', 'November' => 'Noviembre', 'December' => 'Diciembre'
        ];

        $data = [
            'logo'           => $logoBase64,
            'event'          => $event,
            'meses'          => $meses, // Pasamos el diccionario a la vista
            'establishment'  => 'Palapa “La Casona”',
            'representative' => 'María Magdalena Cruz García',
            'city'           => 'San Pedro Pochutla, Oaxaca'
        ];

        $pdf = Pdf::loadView('admin.events.contract', $data)
                  ->setPaper('letter', 'portrait');

        return $pdf->download('Contrato_Evento_' . $event->folio . '.pdf');
    }

    /**
     * Actualiza el estado del evento (Confirmar pago total, etc.)
     */
    public function updateStatus(Event $event, $status)
    {
        if (in_array($status, ['confirmed', 'pending', 'cancelled'])) {
            $updateData = ['status' => $status];
            
            // Si se marca como liquidado (confirmed), actualizamos el anticipo al total
            if ($status === 'confirmed') {
                $updateData['down_payment'] = $event->total_price;
            }

            $event->update($updateData);
            return back()->with('success', "Estado del evento {$event->folio} actualizado.");
        }
        return back()->with('error', 'Estado no válido.');
    }

    /**
     * Elimina el registro del evento.
     */
    public function destroy(Event $event)
    {
        $event->delete();
        return back()->with('success', 'Evento eliminado de la base de datos.');
    }
}