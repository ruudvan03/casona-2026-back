<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Mail\NewReservationAlert;
use App\Mail\GuestReservationConfirmation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class PublicEventController extends Controller
{
    /**
     * Devuelve una lista de fechas ya reservadas.
     * Útil para bloquear días en el calendario de Astro.
     */
    public function getAvailability()
    {
        $occupiedDates = Event::where('status', '!=', 'cancelled')
            ->where('event_date', '>=', now()->toDateString())
            ->pluck('event_date');

        return response()->json($occupiedDates);
    }

    /**
     * Procesa la solicitud de reserva desde la Landing Page.
     */
    public function storePublicReservation(Request $request)
    {
        // 1. Validación de los datos recibidos (Incluye email)
        $validated = $request->validate([
            'customer_name'  => 'required|string|max:255',
            'customer_email' => 'required|email', // Indispensable para enviar confirmación al cliente
            'customer_phone' => 'required|string|max:20',
            'event_type'     => 'required|string',
            'event_date'     => 'required|date|after:today',
            'start_time'     => 'required',
            'end_time'       => 'required|after:start_time',
            'include_pool'   => 'boolean',
            'include_kitchen'=> 'boolean',
        ]);

        // 2. Verificar disponibilidad para evitar duplicados
        $isOccupied = Event::where('event_date', $validated['event_date'])
            ->where('status', '!=', 'cancelled')
            ->exists();

        if ($isOccupied) {
            return response()->json([
                'error' => 'Lo sentimos, esta fecha ya ha sido reservada.'
            ], 422);
        }

        // 3. Crear la Reserva con estatus pendiente
        // El folio se genera automáticamente si tienes la lógica en el modelo
        $event = Event::create(array_merge($validated, [
            'status' => 'pending',
            'total_price' => 0, // Se cotiza posteriormente en el panel
            'down_payment' => 0
        ]));

        // 4. ENVÍO DE CORREOS (Doble vía mediante Gmail)
        try {
            // Correo 1: Al Encargado (Notificación interna)
            // Se usa la dirección configurada en MAIL_FROM_ADDRESS del .env
            Mail::to(config('mail.from.address'))->send(new NewReservationAlert($event));

            // Correo 2: Al Huésped (Confirmación automática al cliente)
            Mail::to($validated['customer_email'])->send(new GuestReservationConfirmation($event));

        } catch (\Exception $e) {
            // Registramos el error en logs pero permitimos que el usuario reciba su folio
            Log::error("Fallo en envío de correos de reserva: " . $e->getMessage());
        }

        // 5. Respuesta para el frontend en Astro
        return response()->json([
            'success' => true,
            'message' => '¡Solicitud enviada con éxito! Revisa tu correo electrónico.',
            'folio'   => $event->folio,
            'data'    => $event
        ], 201);
    }
}