@extends('layouts.admin')

@section('content')
<div class="max-w-2xl mx-auto">
    <h2 class="text-3xl font-black text-slate-900 mb-8">Editar Reserva #{{ $reservation->id }}</h2>

    <div class="bg-white rounded-[2.5rem] p-10 shadow-xl border border-slate-200">
        <form action="{{ route('reservations.update', $reservation) }}" method="POST">
            @csrf @method('PUT')
            
            <div class="space-y-6">
                <div>
                    <label class="block text-xs font-black text-slate-400 uppercase mb-2">Cliente</label>
                    <input type="text" name="customer_name" value="{{ $reservation->customer_name }}" class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-3">
                </div>

                <div>
                    <label class="block text-xs font-black text-slate-400 uppercase mb-2">Estado de la Reserva</label>
                    <select name="status" class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-3">
                        <option value="confirmed" {{ $reservation->status == 'confirmed' ? 'selected' : '' }}>Confirmada</option>
                        <option value="pending" {{ $reservation->status == 'pending' ? 'selected' : '' }}>Pendiente</option>
                        <option value="cancelled" {{ $reservation->status == 'cancelled' ? 'selected' : '' }}>Cancelada</option>
                    </select>
                </div>

                <div class="pt-6 flex gap-4">
                    <button type="submit" class="flex-1 bg-emerald-600 text-white font-black uppercase py-4 rounded-2xl shadow-lg hover:bg-emerald-700 transition">
                        Guardar Cambios
                    </button>
                    <a href="{{ route('reservations.index') }}" class="px-8 py-4 bg-slate-100 text-slate-500 font-black uppercase rounded-2xl">
                        Volver
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection