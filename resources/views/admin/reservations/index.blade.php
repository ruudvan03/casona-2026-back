@extends('layouts.admin')

@section('content')
<div class="max-w-6xl mx-auto space-y-10">
    {{-- Encabezado --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h2 class="text-3xl font-black text-slate-900 leading-tight">Reservaciones</h2>
            <p class="text-slate-500 font-medium text-sm">Gestiona la ocupación y el calendario de La Casona.</p>
        </div>
        {{-- Botón con un solo icono --}}
        <a href="{{ route('reservations.check') }}" class="bg-emerald-600 text-white font-black uppercase tracking-widest px-6 py-4 rounded-2xl shadow-lg hover:bg-emerald-700 transition transform hover:-translate-y-1 flex items-center shrink-0 text-xs" title="Nueva Reserva">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            <span class="ml-2">Nueva Reserva</span>
        </a>
    </div>

    {{-- SECCIÓN DEL CALENDARIO --}}
    <div class="bg-white rounded-[2.5rem] border border-slate-200 shadow-xl p-8">
        <div class="flex flex-col md:flex-row items-center justify-between mb-8 gap-4">
            <div class="flex items-center gap-3">
                <div class="bg-emerald-100 text-emerald-600 p-2 rounded-xl">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <h3 class="text-xl font-black text-slate-800 uppercase tracking-wider">Calendario de Ocupación</h3>
            </div>
            <div class="flex gap-4 text-[10px] font-black uppercase tracking-widest">
                <div class="flex items-center gap-1.5"><span class="w-3 h-3 bg-emerald-500 rounded-full"></span> Confirmada</div>
                <div class="flex items-center gap-1.5"><span class="w-3 h-3 bg-amber-500 rounded-full"></span> Pendiente</div>
                <div class="flex items-center gap-1.5"><span class="w-3 h-3 bg-red-500 rounded-full"></span> Cancelada</div>
            </div>
        </div>
        
        <div id="calendar" class="min-h-[600px]"></div>
    </div>

    {{-- Buscador y Filtros --}}
    <div class="bg-slate-100/50 p-4 rounded-[2rem] border border-slate-200">
        <div class="relative w-full">
            <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </span>
            <input type="text" id="tableSearch" placeholder="Buscar por Folio, Cliente o Habitación..." class="block w-full pl-11 pr-4 py-4 border-transparent focus:ring-emerald-500 focus:border-emerald-500 bg-white rounded-2xl text-sm font-bold shadow-sm placeholder:text-slate-300 outline-none">
        </div>
    </div>

    {{-- Tabla de Reservas --}}
    <div class="bg-white rounded-[2.5rem] border border-slate-200 shadow-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[900px]" id="reservationsTable">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100">
                        <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Folio / Cliente</th>
                        <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Espacio</th>
                        <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Estancia</th>
                        <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Total</th>
                        <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] text-center">Estado y Gestión</th>
                        <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($reservations as $res)
                    <tr class="hover:bg-slate-50/50 transition-colors reservation-row">
                        <td class="px-6 py-6">
                            <div class="text-[10px] font-black text-emerald-600 uppercase mb-1">{{ $res->folio }}</div>
                            <div class="font-bold text-slate-900 leading-tight">{{ $res->customer_name }}</div>
                            <div class="text-[11px] text-slate-400 font-medium mb-1">{{ $res->customer_email }}</div>
                            @if($res->customer_phone)
                                @php
                                    $msg = "¡Hola {$res->customer_name}! Tu reserva en La Casona con Folio {$res->folio} está registrada.";
                                    $waUrl = "https://wa.me/52" . preg_replace('/\D/', '', $res->customer_phone) . "?text=" . urlencode($msg);
                                @endphp
                                <a href="{{ $waUrl }}" target="_blank" class="inline-flex items-center text-[10px] font-black text-emerald-500 uppercase tracking-wider hover:text-emerald-700 mt-1">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.588-5.946 0-6.556 5.332-11.888 11.888-11.888 3.176 0 6.161 1.237 8.404 3.484 2.247 2.247 3.484 5.232 3.484 8.405 0 6.556-5.332 11.89-11.888 11.89-2.014 0-3.991-.51-5.757-1.478l-6.23 1.635zm6.383-3.664c1.611.955 3.178 1.434 4.743 1.434 5.456 0 9.896-4.44 9.896-9.896s-4.44-9.896-9.896-9.896c-5.455 0-9.896 4.44-9.896 9.896 0 2.115.659 4.103 1.906 5.741l-.145.214-1.035 3.777 3.864-1.012-.137-.058z"/></svg>
                                    {{ $res->customer_phone }}
                                </a>
                            @endif
                        </td>
                        <td class="px-6 py-6">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black bg-slate-100 text-slate-600 uppercase border border-slate-200">
                                {{ $res->room->name }}
                            </span>
                        </td>
                        <td class="px-6 py-6 text-sm font-bold text-slate-700">
                            <div class="whitespace-nowrap">{{ \Carbon\Carbon::parse($res->check_in)->translatedFormat('d M') }}</div>
                            <div class="text-[10px] text-slate-400">{{ \Carbon\Carbon::parse($res->check_out)->translatedFormat('d M, Y') }}</div>
                        </td>
                        <td class="px-6 py-6 font-black text-slate-900">${{ number_format($res->total_price, 2) }}</td>
                        <td class="px-6 py-6 text-center">
                            <div class="flex flex-col items-center gap-2">
                                @php $st = $res->status; $cls = $st == 'confirmed' ? 'emerald' : ($st == 'cancelled' ? 'red' : 'amber'); @endphp
                                <span class="px-3 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest border bg-{{$cls}}-100 text-{{$cls}}-700 border-{{$cls}}-200">
                                    {{ $st == 'confirmed' ? 'Confirmada' : ($st == 'cancelled' ? 'Cancelada' : 'Pendiente') }}
                                </span>
                                <div class="flex gap-1.5 justify-center mt-1">
                                    @if($st !== 'confirmed')
                                    <form action="{{ route('reservations.status', [$res, 'confirmed']) }}" method="POST">@csrf @method('PATCH')
                                        <button type="submit" class="p-2 bg-white border border-emerald-200 text-emerald-600 rounded-xl hover:bg-emerald-600 hover:text-white transition shadow-sm" title="Confirmar Pago"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path d="M5 13l4 4L19 7"/></svg></button>
                                    </form>
                                    @endif
                                    @if($st !== 'cancelled')
                                    <form action="{{ route('reservations.status', [$res, 'cancelled']) }}" method="POST">@csrf @method('PATCH')
                                        <button type="submit" class="p-2 bg-white border border-red-200 text-red-600 rounded-xl hover:bg-red-600 hover:text-white transition shadow-sm" title="Cancelar"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path d="M6 18L18 6M6 6l12 12"/></svg></button>
                                    </form>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-6 whitespace-nowrap text-right">
                            <div class="flex justify-end items-center gap-2">
                                {{-- Contrato --}}
                                <a href="{{ route('reservations.pdf', $res) }}" class="w-10 h-10 flex items-center justify-center bg-blue-50 text-blue-600 rounded-xl hover:bg-blue-600 hover:text-white transition-all shadow-sm border border-blue-100" title="Descargar Contrato">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                </a>
                                {{-- Editar --}}
                                <a href="{{ route('reservations.edit', $res) }}" class="w-10 h-10 flex items-center justify-center bg-slate-50 text-slate-600 rounded-xl hover:bg-slate-900 hover:text-white transition-all shadow-sm border border-slate-200" title="Editar">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                    </svg>
                                </a>
                                {{-- Eliminar --}}
                                <form action="{{ route('reservations.destroy', $res) }}" method="POST" onsubmit="return confirm('¿Eliminar folio {{ $res->folio }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="w-10 h-10 flex items-center justify-center bg-red-50 text-red-500 rounded-xl hover:bg-red-600 hover:text-white transition-all shadow-sm border border-red-100" title="Eliminar">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="px-8 py-24 text-center text-slate-400 font-black uppercase tracking-widest text-xs opacity-30">No hay movimientos registrados.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- RECURSOS CALENDARIO --}}
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/locales/es.global.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const calendarEl = document.getElementById('calendar');
        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'es',
            headerToolbar: { 
                left: 'prev,next today', 
                center: 'title', 
                right: 'dayGridMonth,timeGridWeek' 
            },
            buttonText: {
                today: 'Hoy',
                month: 'Mes',
                week: 'Semana'
            },
            events: @json($events),
            height: 'auto',
            dayMaxEvents: true
        });
        calendar.render();

        const searchInput = document.getElementById('tableSearch');
        searchInput.addEventListener('keyup', function() {
            const filter = searchInput.value.toLowerCase();
            document.querySelectorAll('.reservation-row').forEach(row => {
                row.style.display = row.innerText.toLowerCase().includes(filter) ? '' : 'none';
            });
        });
    });
</script>

<style>
    .fc { --fc-border-color: #f1f5f9; --fc-today-bg-color: #f0fdf4; }
    .fc-header-toolbar h2 { font-weight: 900 !important; text-transform: uppercase; color: #0f172a; font-size: 1.1rem !important; letter-spacing: 0.05em; }
    .fc-button-primary { background-color: #065f46 !important; border: none !important; border-radius: 12px !important; font-weight: 800 !important; font-size: 0.7rem !important; padding: 10px 18px !important; transition: all 0.2s ease; }
    .fc-event { border: none !important; padding: 6px 10px !important; border-radius: 10px !important; font-weight: 800 !important; font-size: 10px !important; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
</style>
@endsection