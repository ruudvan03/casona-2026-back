<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use App\Mail\RoomReserved;
use App\Models\Room;
use App\Models\Reservation;
use Carbon\Carbon;

/**
 * RUTAS DE API - PALAPA LA CASONA
 */

Route::get('/rooms', function () {
    return response()->json(Room::all());
});

Route::post('/check-availability', function (Request $request) {
    $request->validate([
        'check_in'  => 'required|date|after_or_equal:today',
        'check_out' => 'required|date|after:check_in',
        'guests'    => 'nullable|integer|min:1' 
    ]);

    $start = $request->check_in;
    $end = $request->check_out;
    $guests = $request->guests;

    $availableRooms = Room::whereDoesntHave('reservations', function ($query) use ($start, $end) {
        $query->overlapping(null, $start, $end); 
    })
    ->when($guests, function ($query) use ($guests) {
        return $query->where('capacity', '>=', $guests);
    })
    ->get();

    return response()->json($availableRooms);
});

Route::post('/reserve-room', function (Request $request) {
    $validated = $request->validate([
        'name'           => 'required|string|max:255',
        'email'          => 'required|email',
        'phone'          => 'required|string',
        'room_id'        => 'required|exists:rooms,id',
        'check_in'       => 'required|date|after_or_equal:today',
        'check_out'      => 'required|date|after:check_in',
        'payment_method' => 'required|in:transfer,cash',
    ]);

    // Validación de disponibilidad real
    $isOccupied = Reservation::overlapping($request->room_id, $request->check_in, $request->check_out)->exists();

    if ($isOccupied) {
        return response()->json(['success' => false, 'message' => 'Fechas no disponibles.'], 422);
    }

    try {
        $room = Room::findOrFail($request->room_id);
        $checkIn = Carbon::parse($request->check_in);
        $checkOut = Carbon::parse($request->check_out);
        $days = $checkIn->diffInDays($checkOut);
        $totalPrice = $room->price_per_night * ($days ?: 1);

        // Crear registro
        $reservation = Reservation::create([
            'room_id'        => $request->room_id,
            'customer_name'  => $request->name,
            'customer_email' => $request->email,
            'customer_phone' => $request->phone,
            'check_in'       => $request->check_in,
            'check_out'      => $request->check_out,
            'total_price'    => $totalPrice,
            'payment_method' => $request->payment_method,
            'status'         => 'pending',
        ]);

        // ENVÍO DE CORREO: Pasamos el objeto íntegro
        try {
            Mail::to($reservation->customer_email)->send(new RoomReserved($reservation));
        } catch (\Exception $e) {
            \Log::error("Error Mail: " . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'folio'   => $reservation->folio,
            'email'   => $reservation->customer_email,
            'total'   => $totalPrice,
            'payment' => $request->payment_method
        ], 201);

    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
    }
});