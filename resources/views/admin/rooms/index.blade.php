@extends('layouts.admin')

@section('content')
<div class="max-w-6xl mx-auto">
    {{-- Encabezado del Listado --}}
    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-3xl font-black text-slate-900 leading-tight">Habitaciones</h2>
            <p class="text-slate-500 font-medium">Gestiona los espacios y galerías de La Casona</p>
        </div>
        <a href="{{ route('rooms.create') }}" class="bg-emerald-600 text-white px-6 py-4 rounded-2xl font-black uppercase tracking-widest shadow-lg shadow-emerald-200 hover:bg-emerald-700 transition-all hover:-translate-y-0.5 flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Nueva Habitación
        </a>
    </div>

    {{-- Grid de Habitaciones --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach($rooms as $room)
        {{-- Inicializamos Alpine.js para controlar el estado del carrusel en cada tarjeta --}}
        <div x-data="{ activeIndex: 0, total: {{ $room->images->count() }} }" 
             class="bg-white rounded-[2.5rem] border border-slate-200 shadow-xl shadow-slate-200/50 overflow-hidden group hover:shadow-2xl transition-all duration-500">
            
            {{-- Contenedor de Imagen con Navegación --}}
            <div class="h-56 overflow-hidden relative bg-slate-100">
                @if($room->images->count() > 0)
                    @foreach($room->images as $index => $image)
                        <img x-show="activeIndex === {{ $index }}" 
                             src="{{ asset('storage/' . $image->path) }}" 
                             alt="{{ $room->name }}" 
                             class="absolute inset-0 w-full h-full object-cover transition-opacity duration-500"
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0"
                             x-transition:enter-end="opacity-100">
                    @endforeach

                    {{-- Flechas de Navegación (Solo visibles en hover y si hay más de 1 imagen) --}}
                    <div x-show="total > 1" class="absolute inset-0 flex items-center justify-between px-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300 z-30">
                        <button @click.stop="activeIndex = (activeIndex === 0) ? total - 1 : activeIndex - 1" 
                                class="p-2 rounded-full bg-white/90 backdrop-blur shadow-sm text-emerald-700 hover:bg-emerald-600 hover:text-white transition-all transform hover:scale-110">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                            </svg>
                        </button>
                        <button @click.stop="activeIndex = (activeIndex === total - 1) ? 0 : activeIndex + 1" 
                                class="p-2 rounded-full bg-white/90 backdrop-blur shadow-sm text-emerald-700 hover:bg-emerald-600 hover:text-white transition-all transform hover:scale-110">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                            </svg>
                        </button>
                    </div>

                    {{-- Indicador de posición (Ej: 1 / 4) --}}
                    <div class="absolute bottom-4 left-4 bg-slate-900/60 backdrop-blur-sm px-3 py-1 rounded-xl text-[10px] font-black text-white uppercase tracking-widest border border-white/20 z-20">
                        <span x-text="activeIndex + 1"></span> / <span x-text="total"></span>
                    </div>
                @else
                    <div class="w-full h-full flex flex-col items-center justify-center text-slate-400 p-4 text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mb-2 opacity-20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.581-1.581a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span class="text-[10px] font-black uppercase tracking-[0.2em]">Sin fotos</span>
                    </div>
                @endif

                {{-- Badge de Precio --}}
                <div class="absolute top-4 right-4 bg-white/95 backdrop-blur-md px-4 py-1.5 rounded-full text-xs font-black text-emerald-700 shadow-sm border border-emerald-100 z-20">
                    ${{ number_format($room->price_per_night, 2) }} <span class="text-[10px] text-emerald-500/70 font-bold uppercase ml-1">noche</span>
                </div>
            </div>

            {{-- Información de la Habitación --}}
            <div class="p-8">
                <div class="flex justify-between items-start mb-2">
                    <h3 class="text-xl font-black text-slate-800 leading-tight">{{ $room->name }}</h3>
                    <span class="flex items-center text-slate-500 text-[11px] font-black uppercase tracking-tighter bg-slate-50 px-2 py-1 rounded-lg border border-slate-100">
                        <svg class="w-3.5 h-3.5 mr-1 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        {{ $room->capacity }}
                    </span>
                </div>
                
                <p class="text-slate-400 text-sm mb-6 line-clamp-2 italic leading-relaxed min-h-[2.5rem]">
                    {{ $room->description ?? 'Sin descripción disponible.' }}
                </p>
                
                {{-- Acciones --}}
                <div class="grid grid-cols-2 gap-4 border-t border-slate-100 pt-6">
                    <a href="{{ route('rooms.edit', $room) }}" class="py-3 bg-slate-50 text-slate-600 rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-slate-100 transition-all text-center border border-slate-200/50">
                        Editar
                    </a>
                    
                    <form action="{{ route('rooms.destroy', $room) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar esta habitación y toda su galería?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full py-3 bg-red-50 text-red-600 rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-red-500 hover:text-white transition-all border border-red-100 shadow-sm shadow-red-100">
                            Borrar
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Estado Vacío --}}
    @if($rooms->isEmpty())
        <div class="text-center py-24 bg-white rounded-[3rem] border-2 border-dashed border-slate-200 mt-10">
            <div class="inline-flex p-6 bg-slate-50 rounded-full mb-4">
                <svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
            <p class="text-slate-500 font-black uppercase tracking-[0.2em]">No hay habitaciones registradas.</p>
        </div>
    @endif
</div>
@endsection