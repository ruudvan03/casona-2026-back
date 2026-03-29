@extends('layouts.admin')

@section('content')
<div class="max-w-6xl mx-auto space-y-10">
    
    {{-- Encabezado --}}
    <div class="flex flex-col md:flex-row justify-between items-end gap-4">
        <div>
            <h2 class="text-3xl font-black text-slate-900 leading-tight">Nueva Reservación</h2>
            <p class="text-slate-500 font-medium">Verifica disponibilidad por fechas y capacidad.</p>
        </div>
        
        <a href="{{ route('reservations.index') }}" class="text-xs font-black text-slate-400 uppercase tracking-widest hover:text-emerald-600 transition flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Volver al Calendario
        </a>
    </div>

    {{-- Formulario de Búsqueda --}}
    <div class="bg-white rounded-[2.5rem] p-8 md:p-10 border border-slate-200 shadow-xl shadow-slate-200/50">
        <form action="{{ route('reservations.verify') }}" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-6 items-end">
            @csrf
            
            {{-- Check-in --}}
            <div>
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2">Llegada</label>
                <input type="date" name="check_in" value="{{ old('check_in', $checkIn ?? '') }}" required 
                       class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-4 font-bold text-slate-700 focus:ring-2 focus:ring-emerald-500 outline-none transition cursor-pointer">
            </div>

            {{-- Check-out --}}
            <div>
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2">Salida</label>
                <input type="date" name="check_out" value="{{ old('check_out', $checkOut ?? '') }}" required
                       class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-4 font-bold text-slate-700 focus:ring-2 focus:ring-emerald-500 outline-none transition cursor-pointer">
            </div>

            {{-- Capacidad --}}
            <div>
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2">Personas</label>
                <div class="relative">
                    <select name="guests" required class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-4 font-bold text-slate-700 focus:ring-2 focus:ring-emerald-500 outline-none transition appearance-none cursor-pointer">
                        <option value="1">1 Persona</option>
                        <option value="2" {{ (old('guests', $guests ?? '') == 2) ? 'selected' : '' }}>2 Personas</option>
                        <option value="4" {{ (old('guests', $guests ?? '') == 4) ? 'selected' : '' }}>3 - 4 Personas</option>
                        <option value="5" {{ (old('guests', $guests ?? '') == 5) ? 'selected' : '' }}>5 - 6 Personas</option>
                        <option value="10" {{ (old('guests', $guests ?? '') == 10) ? 'selected' : '' }}>Grupo (7+)</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-slate-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>
            </div>

            {{-- Botón Buscar --}}
            <button type="submit" class="w-full bg-emerald-600 text-white font-black uppercase tracking-widest py-4 rounded-2xl shadow-lg shadow-emerald-200 hover:bg-emerald-700 transition transform hover:-translate-y-1 active:scale-95 flex justify-center items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <span>Buscar</span>
            </button>
        </form>
    </div>

    {{-- RESULTADOS --}}
    @if(isset($availableRooms))
        <div class="space-y-8 animate-fade-in-up">
            <div class="flex items-center gap-4">
                <div class="h-10 w-1 bg-emerald-500 rounded-full"></div>
                <h3 class="text-xl font-black text-slate-800 uppercase tracking-wide">
                    Resultados Disponibles 
                    <span class="text-slate-400 text-sm normal-case ml-2 font-medium">({{ count($availableRooms) }} habitaciones encontradas)</span>
                </h3>
            </div>

            @if($availableRooms->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($availableRooms as $room)
                    
                    {{-- Preparamos las imágenes para AlpineJS --}}
                    @php
                        $slides = collect();
                        // 1. Si hay galería, la usamos
                        if($room->images->count() > 0){
                            $slides = $room->images->map(fn($img) => asset('storage/' . $img->path));
                        } 
                        // 2. Si no hay galería pero sí portada, la usamos
                        elseif($room->image_path) {
                            $slides->push(asset('storage/' . $room->image_path));
                        }
                    @endphp

                    <div class="bg-white rounded-[2.5rem] border border-slate-200 overflow-hidden hover:shadow-2xl hover:shadow-slate-200/50 transition-all duration-300 group flex flex-col h-full relative">
                        
                        {{-- ÁREA DE IMAGEN (CARRUSEL ALPINE) --}}
                        <div class="h-56 bg-slate-100 relative overflow-hidden shrink-0 group/slider" 
                             x-data="{ activeSlide: 0, slides: {{ $slides->toJson() }} }">
                            
                            {{-- Si hay imágenes --}}
                            <template x-if="slides.length > 0">
                                <div class="relative w-full h-full">
                                    {{-- Las Fotos --}}
                                    <template x-for="(slide, index) in slides" :key="index">
                                        <div x-show="activeSlide === index" 
                                             class="absolute inset-0 w-full h-full"
                                             x-transition:enter="transition ease-out duration-300"
                                             x-transition:enter-start="opacity-0"
                                             x-transition:enter-end="opacity-100"
                                             x-transition:leave="transition ease-in duration-300"
                                             x-transition:leave-start="opacity-100"
                                             x-transition:leave-end="opacity-0">
                                            <img :src="slide" class="w-full h-full object-cover">
                                            <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent"></div>
                                        </div>
                                    </template>

                                    {{-- Botones de Navegación (Solo si hay más de 1 foto) --}}
                                    <div x-show="slides.length > 1" class="absolute inset-0 flex items-center justify-between px-2 opacity-0 group-hover/slider:opacity-100 transition duration-300 z-10">
                                        <button @click.prevent="activeSlide = activeSlide === 0 ? slides.length - 1 : activeSlide - 1" 
                                                class="bg-white/80 hover:bg-white text-emerald-800 p-2 rounded-full shadow-lg backdrop-blur-sm transition hover:scale-110">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                                        </button>
                                        <button @click.prevent="activeSlide = activeSlide === slides.length - 1 ? 0 : activeSlide + 1" 
                                                class="bg-white/80 hover:bg-white text-emerald-800 p-2 rounded-full shadow-lg backdrop-blur-sm transition hover:scale-110">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                                        </button>
                                    </div>

                                    {{-- Indicadores (Puntitos) --}}
                                    <div x-show="slides.length > 1" class="absolute bottom-3 left-0 right-0 flex justify-center gap-1.5 z-10">
                                        <template x-for="(slide, index) in slides" :key="index">
                                            <button @click.prevent="activeSlide = index" 
                                                    class="h-1.5 rounded-full transition-all duration-300 shadow-sm"
                                                    :class="activeSlide === index ? 'bg-white w-4' : 'bg-white/50 w-1.5 hover:bg-white/80'">
                                            </button>
                                        </template>
                                    </div>
                                </div>
                            </template>

                            {{-- Si NO hay imágenes (Placeholder) --}}
                            <template x-if="slides.length === 0">
                                <div class="flex items-center justify-center h-full text-slate-300 bg-slate-50">
                                    <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                            </template>

                            {{-- Precio (Siempre encima) --}}
                            <div class="absolute top-4 right-4 bg-white/95 backdrop-blur-md text-emerald-800 font-black px-4 py-2 rounded-full text-xs shadow-sm z-20 border border-white/50">
                                ${{ number_format($room->price_per_night, 0) }} <span class="text-[9px] uppercase font-bold text-emerald-600/70">/ Noche</span>
                            </div>
                        </div>

                        {{-- Información y Formulario --}}
                        <div class="p-8 flex-1 flex flex-col">
                            <div class="flex-1">
                                <h4 class="text-xl font-black text-slate-900 mb-2 leading-tight">{{ $room->name }}</h4>
                                <div class="flex flex-wrap gap-2 mb-6">
                                    <span class="inline-flex items-center gap-1.5 text-[10px] font-bold text-slate-500 bg-slate-100 px-3 py-1.5 rounded-lg uppercase tracking-wider border border-slate-200">
                                        <svg class="w-3 h-3 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                        {{ $room->capacity_label ?? $room->capacity . ' Huéspedes' }}
                                    </span>
                                </div>
                            </div>
                            
                            <form action="{{ route('reservations.store') }}" method="POST" class="space-y-3 pt-6 border-t border-slate-100">
                                @csrf
                                <input type="hidden" name="room_id" value="{{ $room->id }}">
                                <input type="hidden" name="check_in" value="{{ $checkIn }}">
                                <input type="hidden" name="check_out" value="{{ $checkOut }}">
                                
                                <input type="text" name="customer_name" placeholder="Nombre Completo" required class="w-full text-xs font-bold bg-slate-50 border-transparent rounded-xl px-4 py-3 focus:bg-white focus:ring-2 focus:ring-emerald-500 transition placeholder:text-slate-400">
                                <div class="grid grid-cols-2 gap-3">
                                    <input type="email" name="customer_email" placeholder="Correo" required class="w-full text-xs font-bold bg-slate-50 border-transparent rounded-xl px-4 py-3 focus:bg-white focus:ring-2 focus:ring-emerald-500 transition placeholder:text-slate-400">
                                    <input type="tel" name="customer_phone" placeholder="Teléfono" required class="w-full text-xs font-bold bg-slate-50 border-transparent rounded-xl px-4 py-3 focus:bg-white focus:ring-2 focus:ring-emerald-500 transition placeholder:text-slate-400">
                                </div>

                                <button type="submit" class="w-full bg-slate-900 text-white text-xs font-black uppercase tracking-widest py-4 rounded-xl hover:bg-emerald-600 transition flex justify-center items-center gap-2 mt-2 group-hover:shadow-lg shadow-emerald-200">
                                    <span>Reservar</span>
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="bg-red-50 text-red-600 p-10 rounded-[2.5rem] text-center border border-red-100 flex flex-col items-center">
                    <div class="bg-red-100 p-4 rounded-full mb-4">
                        <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h4 class="font-black text-lg uppercase tracking-wide mb-1">Sin Disponibilidad</h4>
                    <p class="text-sm font-medium opacity-80">No encontramos habitaciones libres para esas fechas y capacidad.</p>
                    <a href="{{ route('reservations.check') }}" class="mt-6 text-xs font-black text-red-700 uppercase tracking-widest hover:underline">Intentar nueva búsqueda</a>
                </div>
            @endif
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 text-red-700 p-4 rounded-xl font-bold text-center">
            {{ session('error') }}
        </div>
    @endif
</div>

<style>
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    .animate-fade-in-up { animation: fadeInUp 0.5s ease-out forwards; }
</style>
@endsection