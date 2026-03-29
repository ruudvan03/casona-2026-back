<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\RoomReserved; // Asumiendo que creaste el Mailable

Route::post('/reserve-room', function (Request $request) {
    // 1. Validar los datos
    $validated = $request->validate([
        'name' => 'required|string',
        'email' => 'required|email',
        'phone' => 'required|string',
        'room_id' => 'required|exists:rooms,id',
        'check_in' => 'required|date',
        'check_out' => 'required|date|after:check_in',
    ]);

    // 2. Aquí idealmente guardas en tu tabla de "reservations" en la Base de Datos
    // $reservation = Reservation::create($validated);
    
    // Generamos un folio falso por ahora
    $folio = 'CAS-' . rand(1000, 9999);

    // 3. Enviar el correo al cliente
    Mail::to($request->email)->send(new RoomReserved($validated, $folio));

    return response()->json([
        'success' => true,
        'folio' => $folio,
        'email' => $request->email
    ]);
});