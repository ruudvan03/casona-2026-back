@extends('layouts.admin')

@section('header', 'Nueva Habitación')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-[2rem] border border-slate-200 shadow-xl shadow-slate-200/50 overflow-hidden">
        <div class="p-10">
            <form action="{{ route('rooms.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Nombre de la Habitación</label>
                        <input type="text" name="name" required class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-3 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition" placeholder="Ej: Suite Presidencial">
                    </div>

                    <div>
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Precio por Noche (MXN)</label>
                        <input type="number" name="price_per_night" required class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-3 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition" placeholder="1200">
                    </div>

                    <div>
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Capacidad (Personas)</label>
                        <input type="number" name="capacity" required class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-3 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition" placeholder="2">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Descripción</label>
                        <textarea name="description" rows="3" class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-3 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition" placeholder="Describe los servicios incluidos..."></textarea>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Galería de Fotos</label>
                        <div class="relative group">
                            <input type="file" name="images[]" id="images" multiple required class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                            
                            <div class="w-full bg-slate-50 border-2 border-dashed border-slate-200 rounded-2xl px-5 py-8 flex flex-col items-center justify-center group-hover:border-emerald-400 group-hover:bg-emerald-50/30 transition duration-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-slate-300 group-hover:text-emerald-500 mb-2 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.581-1.581a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <p class="text-xs font-bold uppercase tracking-widest text-slate-400 group-hover:text-emerald-700 transition">Haz clic para subir múltiples imágenes</p>
                                <p id="file-count" class="text-[10px] text-emerald-600 mt-1 font-bold italic"></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-10 flex gap-4">
                    <button type="submit" class="flex-1 bg-emerald-600 text-white font-black uppercase tracking-widest py-4 rounded-2xl shadow-lg shadow-emerald-200 hover:bg-emerald-700 transition">
                        Guardar Habitación
                    </button>
                    <a href="{{ route('rooms.index') }}" class="px-8 py-4 bg-slate-100 text-slate-500 font-black uppercase tracking-widest rounded-2xl hover:bg-slate-200 transition text-center">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('images').addEventListener('change', function(e) {
        const count = e.target.files.length;
        document.getElementById('file-count').innerText = count > 0 ? `${count} fotos seleccionadas` : "";
    });
</script>
@endsection