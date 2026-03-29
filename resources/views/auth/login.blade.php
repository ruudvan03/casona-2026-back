<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - La Casona</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-screen w-full overflow-hidden font-sans">

    <div class="relative min-h-screen flex items-center justify-center bg-cover bg-center" 
         style="background-image: url('/images/login.png');">
        
        <div class="absolute inset-0 bg-black/70 backdrop-blur-sm"></div>

        <div class="relative z-10 w-full max-w-md p-6">
            
            <div class="text-center mb-8">
                <img src="/images/logo.png" alt="Logo La Casona" class="mx-auto h-24 w-auto mb-4 brightness-0 invert">
                
                <h2 class="text-4xl font-bold text-white tracking-tight">Bienvenido</h2>
                <p class="text-green-200/80 mt-2 text-sm">Sistema Administrativo Palapa "La Casona"</p>
            </div>

            @if ($errors->any())
                <div class="mb-4 bg-red-500/20 border border-red-500 text-red-200 px-4 py-3 rounded-lg text-sm text-center">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login.process') }}" class="space-y-6">
                @csrf

                <div class="group">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <input id="username" type="text" name="username" value="{{ old('username') }}" required autofocus 
                            class="block w-full pl-10 pr-3 py-4 border border-gray-600 rounded-xl leading-5 bg-gray-900/50 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 sm:text-sm transition duration-200" 
                            placeholder="Nombre de usuario">
                    </div>
                </div>

                <div class="group">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <input id="password" type="password" name="password" required 
                            class="block w-full pl-10 pr-3 py-4 border border-gray-600 rounded-xl leading-5 bg-gray-900/50 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 sm:text-sm transition duration-200" 
                            placeholder="Contraseña">
                    </div>
                </div>

                <div>
                    <button type="submit" 
                        class="w-full flex justify-center py-4 px-4 border border-transparent text-sm font-bold rounded-full text-white bg-gradient-to-r from-green-600 to-emerald-800 hover:from-green-500 hover:to-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 shadow-lg transform hover:-translate-y-0.5 transition duration-200">
                        Ingresar al Sistema
                    </button>
                </div>

                <div class="text-center mt-4">
                    <a href="#" class="font-medium text-green-400 hover:text-green-300 text-xs">
                        ¿Olvidaste tu contraseña?
                    </a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>