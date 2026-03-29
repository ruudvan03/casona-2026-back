<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\Admin\ReservationController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EventController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- Autenticación ---

// 1. Ruta para VER el formulario de inicio de sesión (GET)
Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');

// 2. Ruta para PROCESAR las credenciales (POST)
Route::post('/login', [LoginController::class, 'login'])->name('login.process');

// 3. Ruta para Cerrar Sesión de forma segura
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


// --- Rutas Protegidas (Requieren inicio de sesión previo) ---

Route::middleware('auth')->group(function () {
    
    // DASHBOARD PRINCIPAL
    // Conecta con el controlador para mostrar ingresos, ocupación y folios pendientes
    Route::get('/admin', [DashboardController::class, 'index'])->name('admin.dashboard');

    // --- Gestión de Habitaciones (CRUD de espacios en La Casona) ---
    Route::resource('admin/rooms', RoomController::class)->names('rooms');

    // --- Gestión de Reservaciones (Habitaciones) ---
    
    // Listado principal con calendario y tabla de búsqueda
    Route::get('/admin/reservations', [ReservationController::class, 'index'])->name('reservations.index');
    
    // Verificación de disponibilidad de habitaciones
    Route::get('/admin/reservations/check', [ReservationController::class, 'checkAvailability'])->name('reservations.check');
    Route::post('/admin/reservations/verify', [ReservationController::class, 'verify'])->name('reservations.verify');
    
    // Almacenamiento de nuevas reservas
    Route::post('/admin/reservations/store', [ReservationController::class, 'store'])->name('reservations.store');

    // CONTROL MANUAL DE ESTADOS (Módulo de operación rápida)
    Route::patch('/admin/reservations/{reservation}/status/{status}', [ReservationController::class, 'updateStatus'])->name('reservations.status');

    // Gestión de registros existentes
    Route::get('/admin/reservations/{reservation}/edit', [ReservationController::class, 'edit'])->name('reservations.edit');
    Route::put('/admin/reservations/{reservation}', [ReservationController::class, 'update'])->name('reservations.update');
    Route::delete('/admin/reservations/{reservation}', [ReservationController::class, 'destroy'])->name('reservations.destroy');

    // DOCUMENTACIÓN LEGAL (Contrato de Hospedaje)
    Route::get('/admin/reservations/{reservation}/pdf', [ReservationController::class, 'downloadContract'])->name('reservations.pdf');


    // --- NUEVO: Gestión de Palapa / Eventos Sociales ---
    // Módulo independiente para la renta de áreas comunes por hora/día
    
    // Listado y Calendario de Eventos
    Route::get('/admin/events', [EventController::class, 'index'])->name('events.index');
    
    // Almacenamiento con lógica de anticipo del 50%
    Route::post('/admin/events/store', [EventController::class, 'store'])->name('events.store');
    
    // Control de estados (Liquidado, Pendiente, Cancelado)
    Route::patch('/admin/events/{event}/status/{status}', [EventController::class, 'updateStatus'])->name('events.status');
    
    // Eliminación de eventos
    Route::delete('/admin/events/{event}', [EventController::class, 'destroy'])->name('events.destroy');

    // DOCUMENTACIÓN LEGAL (NUEVO: Contrato de Renta de Palapa)
    // Permite descargar el contrato específico para eventos en formato PDF
    Route::get('/admin/events/{event}/pdf', [EventController::class, 'downloadContract'])->name('events.pdf');

});