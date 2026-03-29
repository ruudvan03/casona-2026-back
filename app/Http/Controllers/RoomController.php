<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\RoomImage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class RoomController extends Controller
{
    /**
     * Muestra el listado de habitaciones con su galería cargada.
     */
    public function index()
    {
        $rooms = Room::with('images')->latest()->get();
        return view('admin.rooms.index', compact('rooms'));
    }

    /**
     * Muestra el formulario para crear una nueva habitación.
     */
    public function create()
    {
        return view('admin.rooms.create');
    }

    /**
     * Almacena una nueva habitación y su galería de imágenes.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price_per_night' => 'required|numeric',
            'capacity' => 'required|integer',
            'description' => 'nullable|string',
            'images' => 'required|array', 
            'images.*' => 'image|mimes:jpeg,png,jpg|max:2048', 
        ]);

        // 1. Crear la habitación (inicialmente sin imagen de portada)
        $room = Room::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'price_per_night' => $request->price_per_night,
            'capacity' => $request->capacity,
            'capacity_label' => $request->capacity . ' Personas', // Etiqueta automática
            'description' => $request->description,
            'is_available' => true,
        ]);

        // 2. Procesar y guardar cada imagen en la galería
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $key => $file) {
                // Guardar archivo físico
                $path = $file->store('rooms', 'public');
                
                // Crear registro en tabla room_images
                RoomImage::create([
                    'room_id' => $room->id,
                    'path' => $path
                ]);

                // --- MAGIA: La primera imagen se convierte en la PORTADA ---
                if ($key === 0) {
                    $room->update(['image_path' => $path]);
                }
            }
        }

        return redirect()->route('rooms.index')->with('success', 'Habitación creada con portada y galería.');
    }

    /**
     * Muestra el formulario para editar.
     */
    public function edit(Room $room)
    {
        $room->load('images');
        return view('admin.rooms.edit', compact('room'));
    }

    /**
     * Actualiza los datos y añade más fotos a la galería sin borrar las anteriores.
     */
    public function update(Request $request, Room $room)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price_per_night' => 'required|numeric',
            'capacity' => 'required|integer',
            'description' => 'nullable|string',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $room->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'price_per_night' => $request->price_per_night,
            'capacity' => $request->capacity,
            'capacity_label' => $request->capacity . ' Personas',
            'description' => $request->description,
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $key => $file) {
                $path = $file->store('rooms', 'public');
                
                RoomImage::create([
                    'room_id' => $room->id,
                    'path' => $path
                ]);

                // Si la habitación NO tiene portada aún, usamos la primera nueva
                if (!$room->image_path && $key === 0) {
                    $room->update(['image_path' => $path]);
                }
            }
        }

        return redirect()->route('rooms.index')->with('success', 'Habitación actualizada correctamente.');
    }

    /**
     * Elimina la habitación y todos sus archivos físicos.
     */
    public function destroy(Room $room)
    {
        // Borramos todas las imágenes de la galería del disco
        foreach ($room->images as $image) {
            if (Storage::disk('public')->exists($image->path)) {
                Storage::disk('public')->delete($image->path);
            }
            $image->delete();
        }
        
        // También borramos la imagen de portada si existe y es diferente
        if ($room->image_path && Storage::disk('public')->exists($room->image_path)) {
            // Verificamos si no es la misma que ya borramos (caso raro pero posible)
            Storage::disk('public')->delete($room->image_path);
        }
        
        $room->delete();

        return redirect()->route('rooms.index')->with('success', 'Habitación y sus fotos eliminadas.');
    }

    /**
     * Eliminar una sola imagen de la galería.
     */
    public function deleteImage($id)
    {
        $image = RoomImage::findOrFail($id);
        
        if (Storage::disk('public')->exists($image->path)) {
            Storage::disk('public')->delete($image->path);
        }
        
        $image->delete();

        return back()->with('success', 'Imagen eliminada de la galería.');
    }
}