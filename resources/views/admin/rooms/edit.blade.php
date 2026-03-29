@extends('layouts.admin')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-8">
        <h2 class="text-3xl font-black text-slate-900">Editar Habitación</h2>
        <p class="text-slate-500">Actualiza la información o la galería de fotos.</p>
    </div>

    <div class="bg-white rounded-[2rem] border border-slate-200 shadow-xl shadow-slate-200/50 overflow-hidden">
        <div class="p-10">
            <form action="{{ route('rooms.update', $room) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Nombre</label>
                        <input type="text" name="name" value="{{ $room->name }}" required class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-3 focus:ring-2 focus:ring-emerald-500 outline-none transition">
                    </div>

                    <div>
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Precio por Noche</label>
                        <input type="number" name="price_per_night" value="{{ $room->price_per_night }}" required class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-3 focus:ring-2 focus:ring-emerald-500 outline-none transition">
                    </div>
                    <div>
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Capacidad</label>
                        <input type="number" name="capacity" value="{{ $room->capacity }}" required class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-3 focus:ring-2 focus:ring-emerald-500 outline-none transition">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Descripción</label>
                        <textarea name="description" rows="3" class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-3 focus:ring-2 focus:ring-emerald-500 outline-none transition">{{ $room->description }}</textarea>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-4">Fotos Actuales</label>
                        <div class="grid grid-cols-3 gap-4 mb-6">
                            @foreach($room->images as $image)
                                <div class="relative group h-24 rounded-xl overflow-hidden border border-slate-200">
                                    <img src="{{ asset('storage/' . $image->path) }}" class="w-full h-full object-cover">
                                    {{-- Aquí podrías poner un botón para borrar una sola foto después --}}
                                </div>
                            @endforeach
                        </div>

                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Añadir más fotos</label>
                        <div class="relative group">
                            <input type="file" name="images[]" multiple class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                            <div class="w-full bg-slate-50 border-2 border-dashed border-slate-200 rounded-2xl px-5 py-6 flex flex-col items-center justify-center group-hover:border-emerald-400 transition">
                                <p class="text-xs font-bold text-slate-400 uppercase">Seleccionar archivos</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-10 flex gap-4">
                    <button type="submit" class="flex-1 bg-emerald-600 text-white font-black uppercase tracking-widest py-4 rounded-2xl shadow-lg hover:bg-emerald-700 transition">
                        Actualizar Todo
                    </button>
                    <a href="{{ route('rooms.index') }}" class="px-8 py-4 bg-slate-100 text-slate-500 font-black uppercase tracking-widest rounded-2xl hover:bg-slate-200 transition text-center">
                        Volver
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection