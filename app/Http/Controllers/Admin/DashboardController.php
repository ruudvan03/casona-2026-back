<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Event; // Importamos el modelo de la Palapa
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $hoy = now()->format('Y-m-d');
        $mesActual = now()->month;
        $anioActual = now()->year;

        // --- MÉTRICAS DE HABITACIONES (Existentes) ---
        
        // 1. Ingresos del mes actual (Solo reservas confirmadas)
        $ingresosMes = Reservation::where('status', 'confirmed')
            ->whereMonth('check_in', $mesActual)
            ->whereYear('check_in', $anioActual)
            ->sum('total_price');

        // 2. Ocupación Hoy (Habitaciones activas)
        $ocupacionHoy = Reservation::where('status', 'confirmed')
            ->where('check_in', '<=', $hoy)
            ->where('check_out', '>=', $hoy)
            ->count();

        // 3. Pendientes de Pago / Confirmación
        $pendientesPago = Reservation::where('status', 'pending')->count();

        // 4. Próximas Llegadas (En los siguientes 7 días)
        $proximasLlegadas = Reservation::where('status', 'confirmed')
            ->whereBetween('check_in', [now(), now()->addDays(7)])
            ->count();

        // 5. Últimas 5 reservaciones para la tabla de actividad
        $recientes = Reservation::with('room')->latest()->take(5)->get();


        // --- MÉTRICAS DE PALAPA / EVENTOS (NUEVAS) ---

        // 1. Eventos más cercanos (Próximos 5 que no estén cancelados)
        $upcomingEvents = Event::where('event_date', '>=', $hoy)
            ->where('status', '!=', 'cancelled')
            ->orderBy('event_date', 'asc')
            ->orderBy('start_time', 'asc')
            ->take(5)
            ->get();

        // 2. Ingresos Totales de Palapa (Este mes - Solo liquidados o confirmados)
        $palapaEarnings = Event::whereMonth('event_date', $mesActual)
            ->whereYear('event_date', $anioActual)
            ->where('status', 'confirmed')
            ->sum('total_price');

        // 3. Conteo de eventos totales programados este mes
        $monthlyEventsCount = Event::whereMonth('event_date', $mesActual)
            ->whereYear('event_date', $anioActual)
            ->where('status', '!=', 'cancelled')
            ->count();


        // --- DATOS PARA LA GRÁFICA (Ingresos últimos 6 meses - Habitaciones) ---
        
        $ingresosPorMes = Reservation::where('status', 'confirmed')
            ->selectRaw('SUM(total_price) as total, DATE_FORMAT(check_in, "%Y-%m") as mes')
            ->where('check_in', '>=', now()->subMonths(6))
            ->groupBy('mes')
            ->orderBy('mes', 'asc')
            ->get();

        $labels = [];
        $data = [];

        foreach ($ingresosPorMes as $registro) {
            $labels[] = Carbon::parse($registro->mes)->translatedFormat('F Y');
            $data[] = $registro->total;
        }

        // --- RETORNO DE VISTA ---

        return view('admin.dashboard', compact(
            'ingresosMes', 
            'ocupacionHoy', 
            'pendientesPago', 
            'proximasLlegadas', 
            'recientes',
            'labels',
            'data',
            // Nuevas variables para la Palapa
            'upcomingEvents',
            'palapaEarnings',
            'monthlyEventsCount'
        ));
    }
}