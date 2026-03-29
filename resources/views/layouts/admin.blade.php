<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>La Casona - Admin</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style> 
        body { font-family: 'Plus Jakarta Sans', sans-serif; } 
        [x-cloak] { display: none !important; }
        
        .sidebar-transition { 
            transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1); 
            will-change: width;
        }

        /* Scrollbar estética para el nav */
        nav::-webkit-scrollbar { width: 4px; }
        nav::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 10px; }
    </style>
</head>
<body class="bg-gray-100/50 text-slate-900 antialiased" 
    x-data="{ 
        sidebarOpen: localStorage.getItem('sidebarState') === null ? true : localStorage.getItem('sidebarState') === 'true' 
    }"
    x-init="$watch('sidebarOpen', value => localStorage.setItem('sidebarState', value))">

    <div class="flex h-screen overflow-hidden">
        
        <aside 
            x-cloak
            :class="sidebarOpen ? 'w-64' : 'w-20'" 
            class="sidebar-transition bg-emerald-950 text-white flex flex-col shadow-xl z-30 relative shrink-0 overflow-x-hidden"
        >
            {{-- Botón colapsador --}}
            <button 
                @click="sidebarOpen = !sidebarOpen" 
                class="absolute -right-3 top-10 bg-emerald-500 text-white rounded-full p-1.5 shadow-lg hover:scale-110 transition-all z-50 border-2 border-emerald-950"
            >
                <svg xmlns="http://www.w3.org/2000/svg" :class="sidebarOpen ? 'rotate-180' : ''" class="h-4 w-4 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7" />
                </svg>
            </button>

            {{-- Logo --}}
            <div class="p-4 border-b border-emerald-900/50 flex justify-center items-center h-32 shrink-0 overflow-hidden relative">
                <img src="/images/logo.png" 
                     alt="Logo La Casona" 
                     :class="sidebarOpen ? 'h-24 w-auto scale-100' : 'h-10 w-10 object-contain scale-90'" 
                     class="transition-all duration-300 brightness-0 invert drop-shadow-2xl">
            </div>
            
            {{-- Navegación Principal --}}
            <nav class="flex-1 px-4 py-6 space-y-3 overflow-y-auto overflow-x-hidden">
                
                {{-- Dashboard --}}
                <a href="{{ route('admin.dashboard') }}" 
                   class="flex items-center rounded-xl transition-all p-3 group {{ request()->routeIs('admin.dashboard') ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-900/50' : 'text-emerald-100 hover:bg-emerald-900' }}"
                   :class="sidebarOpen ? 'justify-start' : 'justify-center'">
                    <svg class="w-6 h-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
                    </svg>
                    <span x-show="sidebarOpen" x-transition.opacity.duration.200ms class="ml-3 font-bold text-sm uppercase tracking-wider whitespace-nowrap">Dashboard</span>
                </a>

                <div x-show="sidebarOpen" x-transition.opacity class="pt-4 pb-2 text-xs font-black text-slate-400 uppercase tracking-[0.2em] px-4">
                    Administración
                </div>

                {{-- Reservaciones (Habitaciones) --}}
                <a href="{{ route('reservations.index') }}" 
                   class="flex items-center rounded-xl transition-all p-3 group {{ request()->routeIs('reservations.*') ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-900/50' : 'text-emerald-100 hover:bg-emerald-900' }}"
                   :class="sidebarOpen ? 'justify-start' : 'justify-center'">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2v12a2 2 0 002 2z" />
                    </svg>
                    <span x-show="sidebarOpen" x-transition.opacity.duration.200ms class="ml-3 font-bold text-sm uppercase tracking-wider whitespace-nowrap">Reservaciones</span>
                </a>

                {{-- Habitaciones --}}
                <a href="{{ route('rooms.index') }}" 
                   class="flex items-center rounded-xl transition-all p-3 group {{ request()->routeIs('rooms.*') ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-900/50' : 'text-emerald-100 hover:bg-emerald-900' }}"
                   :class="sidebarOpen ? 'justify-start' : 'justify-center'">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    <span x-show="sidebarOpen" x-transition.opacity.duration.200ms class="ml-3 font-bold text-sm uppercase tracking-wider whitespace-nowrap">Habitaciones</span>
                </a>

                <div x-show="sidebarOpen" x-transition.opacity class="pt-4 pb-2 text-xs font-black text-slate-400 uppercase tracking-[0.2em] px-4">
                    Eventos
                </div>

                {{-- PALAPA / EVENTOS (ACTUALIZADO) --}}
                <a href="{{ route('events.index') }}" 
                   class="flex items-center rounded-xl transition-all p-3 group {{ request()->routeIs('events.*') ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-900/50' : 'text-emerald-100 hover:bg-emerald-900' }}"
                   :class="sidebarOpen ? 'justify-start' : 'justify-center'">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span x-show="sidebarOpen" x-transition.opacity.duration.200ms class="ml-3 font-bold text-sm uppercase tracking-wider whitespace-nowrap">Palapa / Eventos</span>
                </a>
            </nav>

            {{-- Logout --}}
            <div class="p-4 border-t border-emerald-900/50">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center w-full p-3 text-emerald-300 rounded-xl hover:bg-red-500/20 hover:text-red-400 transition"
                            :class="sidebarOpen ? 'justify-start' : 'justify-center'">
                        <svg class="w-6 h-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                        </svg>
                        <span x-show="sidebarOpen" x-transition.opacity.duration.200ms class="ml-3 font-bold text-sm uppercase tracking-wider whitespace-nowrap">Cerrar Sesión</span>
                    </button>
                </form>
            </div>
        </aside>

        <main class="flex-1 flex flex-col overflow-hidden bg-slate-100/80">
            {{-- Header --}}
            <header class="h-20 bg-white border-b border-slate-200 flex items-center justify-between px-10 shrink-0">
                <h1 class="text-xl font-bold text-slate-800">Panel de Control</h1>
                <div class="flex items-center gap-3 bg-slate-50 border border-slate-200 px-4 py-2 rounded-2xl">
                    <div class="text-right hidden sm:block">
                        <p class="text-xs font-bold text-emerald-600 uppercase tracking-tighter">Administrador</p>
                        <p class="text-sm font-bold text-slate-700">{{ Auth::user()->name }}</p>
                    </div>
                    <div class="h-10 w-10 rounded-xl bg-emerald-500 text-white flex items-center justify-center font-bold shadow-lg shadow-emerald-200 text-lg">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                </div>
            </header>
            
            {{-- Content Area --}}
            <div class="flex-1 overflow-y-auto p-4 md:p-10">
                @if(session('success'))
                    <div class="max-w-4xl mx-auto mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-6 py-4 rounded-2xl flex items-center shadow-sm" x-data="{ show: true }" x-show="show" x-cloak>
                        <svg class="w-5 h-5 mr-3 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="font-bold flex-1 text-sm">{{ session('success') }}</span>
                        <button @click="show = false" class="text-emerald-400 hover:text-emerald-600 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="max-w-4xl mx-auto mb-6 bg-red-50 border border-red-200 text-red-700 px-6 py-4 rounded-2xl flex items-center shadow-sm" x-data="{ show: true }" x-show="show" x-cloak>
                        <svg class="w-5 h-5 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                        <span class="font-bold flex-1 text-sm">{{ session('error') }}</span>
                        <button @click="show = false" class="text-red-400 hover:text-red-600 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </button>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

</body>
</html>