<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    // Muestra la vista del login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Procesa el formulario
    public function login(Request $request)
    {
        // 1. Validar los datos
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        // 2. Intentar loguear (Auth::attempt hace la magia de verificar pass y usuario)
        // El tercer parámetro 'true' es para "Recordarme" (opcional)
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // 3. Si es correcto, mandar al admin
            return redirect()->intended('admin');
        }

        // 4. Si falla, regresar con error
        return back()->withErrors([
            'username' => 'Las credenciales no coinciden con nuestros registros.',
        ])->onlyInput('username');
    }
    
    // Cerrar sesión
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}