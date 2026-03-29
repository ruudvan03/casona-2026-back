<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Crear el Admin Principal
        \App\Models\User::factory()->create([
            'name' => 'Administrador',
            'username' => 'admin',  // <--- Este es el que usarás para entrar
            'email' => 'admin@lacasona.com',
            'password' => bcrypt('password'), // La contraseña será: password
        ]);
    }
}
