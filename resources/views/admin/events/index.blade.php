@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto space-y-10">
    
    {{-- Encabezado --}}
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-3xl font-black text-slate-900 leading-tight">Módulo de Palapa / Eventos</h2>
            <p class="text-slate-500 font-medium text-sm">Gestiona la agenda y finanzas de las áreas comunes.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
        
        {{-- Lado Izquierdo: Formulario de Registro --}}
        <div class="lg:col-span-1">
            <div class="bg-white rounded-[2.5rem] border border-slate-200 shadow-xl p-8 sticky top-10">
                <h3 class="text-lg font-black text-slate-800 uppercase tracking-widest mb-6 flex items-center gap-2">
                    <span class="w-8 h-8 bg-emerald-100 text-emerald-600 rounded-lg flex items-center justify-center text-sm">+</span>
                    Nuevo Evento
                </h3>

                <form action="{{ route('events.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-2">Cliente</label>
                        <input type="text" name="customer_name" required value="{{ old('customer_name') }}" class="w-full bg-slate-50 border-2 border-slate-100 rounded-2xl px-5 py-3 font-bold outline-none focus:border-emerald-500 transition-all">
                    </div>

                    <div>
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-2">Teléfono</label>
                        <input type="text" name="customer_phone" value="{{ old('customer_phone') }}" placeholder="Ej. 9581234567" class="w-full bg-slate-50 border-2 border-slate-100 rounded-2xl px-5 py-3 font-bold outline-none focus:border-emerald-500 transition-all">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-2">Tipo</label>
                            <select name="event_type" class="w-full bg-slate-50 border-2 border-slate-100 rounded-2xl px-5 py-3 font-bold outline-none">
                                <option value="Boda">Boda</option>
                                <option value="XV Años">XV Años</option>
                                <option value="Bautizo">Bautizo</option>
                                <option value="Reunión">Reunión</option>
                                <option value="Albercada">Albercada</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-2">Precio Total</label>
                            <input type="number" name="total_price" required value="{{ old('total_price') }}" class="w-full bg-slate-50 border-2 border-slate-100 rounded-2xl px-5 py-3 font-bold outline-none focus:border-emerald-500">
                        </div>
                    </div>

                    {{-- Áreas Adicionales con Iconos SVG --}}
                    <div class="space-y-3">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-2">Áreas Adicionales</label>
                        <div class="grid grid-cols-2 gap-3">
                            <label class="flex items-center p-3 rounded-2xl bg-slate-50 border-2 border-slate-100 cursor-pointer hover:border-blue-500 transition-all group">
                                <input type="checkbox" name="include_pool" value="1" class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                                <div class="ml-2 flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M21 12c0 1.657-3.134 3-7 3s-7-1.343-7-3 3.134-3 7-3 7 1.343 7 3zM3 12c0 1.657 3.134 3 7 3s7-1.343 7-3-3.134-3-7-3-7 1.343-7 3z"/><path d="M21 12c0 1.657-3.134 3-7 3s-7-1.343-7-3m14 0c0 1.657-3.134 3-7 3s-7-1.343-7-3"/></svg>
                                    <span class="text-[10px] font-black uppercase text-slate-700">Alberca</span>
                                </div>
                            </label>
                            <label class="flex items-center p-3 rounded-2xl bg-slate-50 border-2 border-slate-100 cursor-pointer hover:border-amber-500 transition-all group">
                                <input type="checkbox" name="include_kitchen" value="1" class="w-4 h-4 rounded border-slate-300 text-amber-600 focus:ring-amber-500">
                                <div class="ml-2 flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                                    <span class="text-[10px] font-black uppercase text-slate-700">Cocina</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div>
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-2">Anticipo (50% Sugerido)</label>
                        <input type="number" name="down_payment" value="{{ old('down_payment') }}" placeholder="Vacío para calcular 50%" class="w-full bg-emerald-50/50 border-2 border-emerald-100 rounded-2xl px-5 py-3 font-bold outline-none focus:border-emerald-500 placeholder:text-slate-400 placeholder:font-medium">
                    </div>

                    <div>
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-2">Fecha</label>
                        <input type="date" name="event_date" required value="{{ old('event_date') }}" class="w-full bg-slate-50 border-2 border-slate-100 rounded-2xl px-5 py-3 font-bold outline-none focus:border-emerald-500">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-2">Inicia</label>
                            <input type="time" name="start_time" required value="{{ old('start_time') }}" class="w-full bg-slate-50 border-2 border-slate-100 rounded-2xl px-5 py-3 font-bold outline-none">
                        </div>
                        <div>
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-2">Termina</label>
                            <input type="time" name="end_time" required value="{{ old('end_time') }}" class="w-full bg-slate-50 border-2 border-slate-100 rounded-2xl px-5 py-3 font-bold outline-none">
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-slate-900 text-white font-black uppercase tracking-widest py-4 rounded-2xl hover:bg-emerald-600 transition shadow-lg shadow-slate-200 mt-2">
                        Agendar Evento
                    </button>
                </form>
            </div>
        </div>

        {{-- Lado Derecho: Calendario --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-[2.5rem] border border-slate-200 shadow-xl p-8 h-full">
                <div id="calendarPalapa"></div>
            </div>
        </div>
    </div>

    {{-- Listado de Gestión --}}
    <div class="bg-white rounded-[2.5rem] border border-slate-200 shadow-xl overflow-hidden">
        <div class="p-8 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
            <h3 class="text-lg font-black text-slate-800 uppercase tracking-widest">Listado de Gestión</h3>
            <div class="flex gap-4 text-[10px] font-black uppercase tracking-widest">
                <div class="flex items-center gap-1.5"><span class="w-2 h-2 bg-emerald-500 rounded-full"></span> Liquidado</div>
                <div class="flex items-center gap-1.5"><span class="w-2 h-2 bg-amber-500 rounded-full"></span> Pendiente</div>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[950px]">
                <thead>
                    <tr class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] bg-slate-50">
                        <th class="px-8 py-5">Folio / Cliente</th>
                        <th class="px-6 py-5">Tipo / Áreas</th>
                        <th class="px-6 py-5">Horario y Fecha</th>
                        <th class="px-6 py-5">Finanzas (Total / Anticipo)</th>
                        <th class="px-6 py-5 text-center">Estado</th>
                        <th class="px-8 py-5 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($events as $event)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-8 py-6">
                            <div class="text-[10px] font-black text-indigo-600 uppercase mb-1">{{ $event->folio }}</div>
                            <div class="font-bold text-slate-900 leading-tight">{{ $event->customer_name }}</div>
                            @if($event->customer_phone)
                                @php
                                    $msg = "¡Hola {$event->customer_name}! Confirmamos tu reserva para el evento {$event->event_type} en La Casona el día " . \Carbon\Carbon::parse($event->event_date)->format('d/m/Y') . ". Folio: {$event->folio}";
                                    $waUrl = "https://wa.me/52" . preg_replace('/\D/', '', $event->customer_phone) . "?text=" . urlencode($msg);
                                @endphp
                                <a href="{{ $waUrl }}" target="_blank" class="inline-flex items-center text-[10px] font-black text-emerald-500 uppercase tracking-wider hover:text-emerald-700 mt-1">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.588-5.946 0-6.556 5.332-11.888 11.888-11.888 3.176 0 6.161 1.237 8.404 3.484 2.247 2.247 3.484 5.232 3.484 8.405 0 6.556-5.332 11.89-11.888 11.89-2.014 0-3.991-.51-5.757-1.478l-6.23 1.635zm6.383-3.664c1.611.955 3.178 1.434 4.743 1.434 5.456 0 9.896-4.44 9.896-9.896s-4.44-9.896-9.896-9.896c-5.455 0-9.896 4.44-9.896 9.896 0 2.115.659 4.103 1.906 5.741l-.145.214-1.035 3.777 3.864-1.012-.137-.058z"/></svg>
                                    {{ $event->customer_phone }}
                                </a>
                            @else
                                <div class="text-[11px] text-slate-400 font-medium italic">Sin teléfono</div>
                            @endif
                        </td>
                        <td class="px-6 py-6">
                            <span class="px-3 py-1 rounded-full text-[10px] font-black bg-slate-100 text-slate-600 border border-slate-200 uppercase whitespace-nowrap inline-block mb-2">
                                {{ $event->event_type }}
                            </span>
                            <div class="flex flex-wrap gap-1.5">
                                @if($event->include_pool) 
                                    <div class="flex items-center gap-1 px-1.5 py-0.5 bg-blue-50 border border-blue-100 rounded-md" title="Alberca">
                                        <svg class="w-3 h-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M21 12c0 1.657-3.134 3-7 3s-7-1.343-7-3 3.134-3 7-3 7 1.343 7 3zM3 12c0 1.657 3.134 3 7 3s7-1.343 7-3-3.134-3-7-3-7 1.343-7 3z"/><path d="M21 12c0 1.657-3.134 3-7 3s-7-1.343-7-3m14 0c0 1.657-3.134 3-7 3s-7-1.343-7-3"/></svg>
                                        <span class="text-[8px] font-black text-blue-600 uppercase tracking-tighter">Alberca</span>
                                    </div>
                                @endif
                                @if($event->include_kitchen) 
                                    <div class="flex items-center gap-1 px-1.5 py-0.5 bg-amber-50 border border-amber-100 rounded-md" title="Cocina">
                                        <svg class="w-3 h-3 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                                        <span class="text-[8px] font-black text-amber-700 uppercase tracking-tighter">Cocina</span>
                                    </div>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-6">
                            <div class="text-sm font-bold text-slate-700">{{ \Carbon\Carbon::parse($event->event_date)->format('d M, Y') }}</div>
                            <div class="text-[10px] text-slate-400 font-black uppercase tracking-tighter">
                                {{ \Carbon\Carbon::parse($event->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($event->end_time)->format('h:i A') }}
                            </div>
                        </td>
                        <td class="px-6 py-6">
                            <div class="text-sm font-black text-slate-900">${{ number_format($event->total_price, 2) }}</div>
                            <div class="text-[10px] font-bold text-emerald-600 uppercase tracking-tighter">Anticipo: ${{ number_format($event->down_payment, 2) }}</div>
                        </td>
                        <td class="px-6 py-6 text-center">
                            <span class="px-3 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest border {{ $event->status == 'confirmed' ? 'bg-emerald-100 text-emerald-700 border-emerald-200' : 'bg-amber-100 text-amber-700 border-amber-200' }}">
                                {{ $event->status == 'confirmed' ? 'Liquidado' : 'Pendiente' }}
                            </span>
                        </td>
                        <td class="px-8 py-6 text-right whitespace-nowrap">
                            <div class="flex justify-end items-center gap-2">
                                <a href="{{ route('events.pdf', $event) }}" class="w-10 h-10 flex items-center justify-center bg-blue-50 text-blue-600 rounded-xl hover:bg-blue-600 hover:text-white transition-all shadow-sm border border-blue-100" title="Descargar Contrato">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                </a>
                                <a href="#" class="w-10 h-10 flex items-center justify-center bg-slate-50 text-slate-600 rounded-xl hover:bg-slate-900 hover:text-white transition-all border border-slate-200" title="Editar">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                </a>
                                <form action="{{ route('events.destroy', $event) }}" method="POST" onsubmit="return confirm('¿Eliminar definitivamente este evento?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="w-10 h-10 flex items-center justify-center bg-red-50 text-red-500 rounded-xl hover:bg-red-600 hover:text-white transition-all border border-red-100" title="Eliminar">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-8 py-20 text-center text-slate-400 font-bold uppercase tracking-widest text-xs opacity-40">No hay eventos registrados en la agenda.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- FullCalendar Resources --}}
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/locales/es.global.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const calendarEl = document.getElementById('calendarPalapa');
        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'es', 
            headerToolbar: { 
                left: 'prev,next today', 
                center: 'title', 
                right: 'dayGridMonth,timeGridWeek' 
            },
            buttonText: { today: 'Hoy', month: 'Mes', week: 'Semana' },
            events: @json($calendarEvents),
            displayEventEnd: true,
            eventTimeFormat: { hour: 'numeric', minute: '2-digit', meridiem: 'short' },
            height: 'auto',
            dayMaxEvents: true
        });
        calendar.render();
    });
</script>

<style>
    .fc { --fc-border-color: #f1f5f9; --fc-today-bg-color: #f0fdf4; }
    .fc-header-toolbar h2 { font-weight: 900 !important; text-transform: uppercase; color: #0f172a; font-size: 1.1rem !important; letter-spacing: 0.05em; }
    .fc-button-primary { background-color: #065f46 !important; border: none !important; border-radius: 12px !important; font-weight: 800 !important; font-size: 0.7rem !important; padding: 10px 18px !important; transition: all 0.2s ease; }
    .fc-event { border: none !important; padding: 6px 10px !important; border-radius: 10px !important; font-weight: 800 !important; font-size: 10px !important; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
</style>
@endsection