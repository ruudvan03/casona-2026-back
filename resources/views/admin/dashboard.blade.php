@extends('layouts.admin')

@section('content')
<div class="max-w-6xl mx-auto space-y-10">
    {{-- Encabezado Dinámico --}}
    <div class="mb-10 flex justify-between items-end">
        <div>
            <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">Bienvenido, {{ Auth::user()->name }}</h2>
            <p class="text-slate-500 mt-1 text-sm">Torre de control central de Palapa "La Casona".</p>
        </div>
        <div class="hidden md:block">
            <span class="text-[10px] font-black uppercase tracking-widest text-emerald-600 bg-emerald-50 px-4 py-2 rounded-full border border-emerald-100">
                {{ now()->translatedFormat('d F, Y') }}
            </span>
        </div>
    </div>

    {{-- Grid de Métricas --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
        
        {{-- Métrica: Ocupación Habitaciones --}}
        <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-xl relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-16 h-16 bg-emerald-50 rounded-bl-full -mr-6 -mt-6 opacity-40 group-hover:scale-110 transition-transform duration-500"></div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Ocupación Hoy</p>
            <div class="flex items-end gap-2">
                @php
                    $totalRooms = \App\Models\Room::count() ?: 1;
                    $percent = round(($ocupacionHoy / $totalRooms) * 100);
                @endphp
                <span class="text-3xl font-black text-slate-900">{{ $percent }}%</span>
                <span class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded mb-1">Habitaciones</span>
            </div>
            <div class="mt-4 w-full bg-slate-100 rounded-full h-2">
                <div class="bg-emerald-500 h-full rounded-full transition-all duration-1000" style="width: {{ $percent }}%"></div>
            </div>
        </div>

        {{-- Métrica: Ingresos Habitaciones --}}
        <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-xl">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Habitaciones</p>
            <h3 class="text-3xl font-black text-slate-900">${{ number_format($ingresosMes, 0) }}</h3>
            <p class="text-[10px] text-emerald-600 font-bold uppercase mt-2 italic">Ingresos Mes</p>
        </div>

        {{-- Métrica: Ingresos Palapa --}}
        <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-xl relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-16 h-16 bg-indigo-50 rounded-bl-full -mr-6 -mt-6 opacity-40 group-hover:scale-110 transition-transform duration-500"></div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Palapa</p>
            <h3 class="text-3xl font-black text-slate-900">${{ number_format($palapaEarnings, 0) }}</h3>
            <p class="text-[10px] text-indigo-600 font-bold uppercase mt-2 italic">Ingresos Mes</p>
        </div>

        {{-- Métrica: Conteo Eventos --}}
        <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-xl">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Eventos</p>
            <div class="flex items-center gap-3">
                <span class="text-3xl font-black text-slate-900">{{ $monthlyEventsCount }}</span>
                <div class="w-8 h-8 bg-amber-50 rounded-lg flex items-center justify-center border border-amber-100">
                    <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
            </div>
            <p class="text-[10px] text-slate-400 font-bold uppercase mt-2">Agenda del mes</p>
        </div>
    </div>

    {{-- Gráfica y Card Palapa --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-12">
        <div class="lg:col-span-2 bg-white rounded-3xl p-8 border border-slate-200 shadow-xl">
            <div class="flex justify-between items-center mb-6">
                <h3 class="font-black text-slate-800 uppercase tracking-widest text-sm">Comportamiento de Ingresos</h3>
                <span class="text-[10px] font-black text-emerald-600 bg-emerald-50 px-3 py-1 rounded-full uppercase">Hospedaje</span>
            </div>
            <div class="relative h-64 w-full">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <div class="bg-emerald-900 rounded-3xl p-8 text-white relative overflow-hidden flex flex-col justify-between shadow-xl">
            <div class="absolute top-0 right-0 w-40 h-40 bg-emerald-500 rounded-full blur-3xl opacity-20 -mr-10 -mt-10"></div>
            <div>
                <h3 class="text-2xl font-black mb-2 leading-tight">Módulo de<br>la Palapa</h3>
                <p class="text-emerald-200 text-sm font-medium mt-2 italic">Control total de renta de salón, alberca y cocina.</p>
            </div>
            <a href="{{ route('events.index') }}" class="inline-flex items-center justify-center w-full py-4 bg-white text-emerald-900 font-black rounded-2xl hover:bg-emerald-50 transition transform hover:-translate-y-1 mt-6 text-xs uppercase tracking-widest shadow-lg">
                Ir a Calendario
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </a>
        </div>
    </div>

    {{-- Tabla: Próximos Eventos --}}
    <div class="bg-white rounded-3xl border border-slate-200 shadow-xl overflow-hidden mb-12">
        <div class="px-8 py-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
            <div>
                <h3 class="font-black text-slate-800 tracking-tight uppercase text-sm">Agenda Próxima</h3>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">Eventos en salón y áreas comunes</p>
            </div>
            <a href="{{ route('events.index') }}" class="px-5 py-2 bg-indigo-50 text-indigo-700 rounded-full text-[10px] font-black uppercase tracking-widest hover:bg-indigo-500 hover:text-white transition shadow-sm">Ver Todo</a>
        </div>
        <div class="overflow-x-auto p-4">
            <table class="w-full text-left border-collapse">
                <tbody class="divide-y divide-slate-100">
                    @forelse($upcomingEvents as $e)
                    <tr class="hover:bg-slate-50 transition group">
                        <td class="px-4 py-6">
                            <div class="flex items-center gap-4">
                                <div class="bg-white border border-slate-200 rounded-xl p-2 text-center min-w-[50px] shadow-sm">
                                    <span class="block text-sm font-black text-slate-900 leading-none">{{ \Carbon\Carbon::parse($e->event_date)->format('d') }}</span>
                                    <span class="block text-[8px] font-black text-emerald-600 uppercase">{{ \Carbon\Carbon::parse($e->event_date)->translatedFormat('M') }}</span>
                                </div>
                                <div>
                                    <div class="text-[10px] font-black text-indigo-600 uppercase">{{ $e->folio }}</div>
                                    <div class="text-sm font-bold text-slate-800 leading-tight">{{ $e->customer_name }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-6">
                            <div class="flex gap-1.5">
                                @if($e->include_pool) 
                                    <div class="w-7 h-7 bg-blue-50 rounded-lg border border-blue-100 flex items-center justify-center text-blue-500" title="Alberca">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M21 12c0 1.657-3.134 3-7 3s-7-1.343-7-3 3.134-3 7-3 7 1.343 7 3z"/><path d="M21 12c0 1.657-3.134 3-7 3s-7-1.343-7-3m14 0c0 1.657-3.134 3-7 3s-7-1.343-7-3"/></svg>
                                    </div>
                                @endif
                                @if($e->include_kitchen) 
                                    <div class="w-7 h-7 bg-amber-50 rounded-lg border border-amber-100 flex items-center justify-center text-amber-600" title="Cocina">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4"/></svg>
                                    </div>
                                @endif
                            </div>
                        </td>
                        <td class="px-4 py-6 text-xs font-bold text-slate-600 uppercase whitespace-nowrap">
                            {{ \Carbon\Carbon::parse($e->start_time)->format('h:i A') }}
                        </td>
                        <td class="px-4 py-6 text-right">
                            @php $pend = $e->total_price - $e->down_payment; @endphp
                            <span class="text-sm font-black {{ $pend > 0 ? 'text-red-500' : 'text-emerald-500' }}">
                                ${{ number_format($pend, 0) }}
                            </span>
                            <div class="text-[8px] font-black text-slate-400 uppercase tracking-tighter">Saldo</div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="px-8 py-10 text-center text-slate-400 font-bold uppercase text-[10px]">Sin eventos próximos</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('revenueChart').getContext('2d');
        let gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(16, 185, 129, 0.3)');
        gradient.addColorStop(1, 'rgba(16, 185, 129, 0.0)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($labels),
                datasets: [{
                    data: @json($data),
                    borderColor: '#10b981',
                    backgroundColor: gradient,
                    borderWidth: 3,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#10b981',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { color: '#f1f5f9' }, ticks: { color: '#94a3b8', font: { size: 10 } } },
                    x: { grid: { display: false }, ticks: { color: '#64748b', font: { size: 10 } } }
                }
            }
        });
    });
</script>
@endsection