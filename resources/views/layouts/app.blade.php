<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'SAPRAS') }} {{ isset($header) ? ' - ' . strip_tags($header) : '' }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    <!-- Styles -->
    <style>
        [x-cloak] { display: none !important; }
        
        a { text-decoration: none; }

        .sidebar-scroll::-webkit-scrollbar { width: 4px; }
        .sidebar-scroll::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.2); border-radius: 10px; }
        
        .nav-item { color: #ffffff; transition: all 0.2s ease-in-out; }
        .nav-item:hover { background-color: #ffffff; color: #2563eb; } 
        .nav-item.active { background-color: #ffffff; color: #2563eb; font-weight: bold; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); }
    </style>

    <!-- Scripts & Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Jalur Paksa CSS (Penting agar desain muncul di Railway) -->
    @if(app()->isProduction() || env('RAILWAY_ENVIRONMENT'))
        <link rel="stylesheet" href="{{ asset('build/assets/app-DNg7CCpm.css') }}">
    @endif
    
</head>
<body class="antialiased {{ request()->routeIs('welcome') ? '' : 'bg-slate-50' }}" x-data="{ sidebarOpen: true }">

    @if(request()->routeIs('welcome') || request()->routeIs('login'))
        
        {{ $slot }}

    @else
        <div class="flex h-screen overflow-hidden">
            
            <!-- SIDEBAR -->
            <aside 
                class="flex-shrink-0 bg-blue-600 transition-all duration-300 z-50 flex flex-col shadow-xl"
                :class="sidebarOpen ? 'w-64' : 'w-20'">
                
                <div class="h-16 flex items-center justify-center flex-shrink-0 border-b border-blue-500 bg-blue-700">
                    <button @click="sidebarOpen = !sidebarOpen" class="text-white text-2xl hover:bg-white/10 p-2 rounded-lg transition-colors focus:outline-none">
                        <i class="bi bi-list"></i>
                    </button>
                </div>

                <nav class="flex-1 overflow-y-auto sidebar-scroll py-6">
                    <div class="px-3 space-y-3">
                        @auth
                            <!-- DASHBOARD -->
                            <a href="{{ Auth::user()->role === 'admin' ? route('admin.dashboard') : route('dashboard') }}" 
                               class="nav-item flex items-center py-3 rounded-xl {{ request()->routeIs('*dashboard*') ? 'active' : '' }}"
                               :class="sidebarOpen ? 'px-4' : 'justify-center'">
                                <div class="flex justify-center items-center h-6 w-6"><i class="bi bi-grid-1x2-fill text-xl"></i></div>
                                <span x-show="sidebarOpen" class="ml-3 text-sm font-semibold uppercase tracking-wider">Dashboard</span>
                            </a>

                            <!-- RIWAYAT -->
                            <a href="{{ Auth::user()->role === 'admin' ? route('admin.riwayat') : route('aspirasi.riwayat') }}" 
                               class="nav-item flex items-center py-3 rounded-xl {{ request()->routeIs('*riwayat*') ? 'active' : '' }}"
                               :class="sidebarOpen ? 'px-4' : 'justify-center'">
                                <div class="flex justify-center items-center h-6 w-6"><i class="bi bi-clock-history text-xl"></i></div>
                                <span x-show="sidebarOpen" class="ml-3 text-sm font-semibold uppercase tracking-wider">Riwayat</span>
                            </a>

                            <!-- KELOLA SISWA (Hanya Admin) -->
                            @if(Auth::user()->role === 'admin')
                                <a href="{{ route('admin.siswa.index') }}" 
                                   class="nav-item flex items-center py-3 rounded-xl {{ request()->routeIs('admin.siswa.*') ? 'active' : '' }}"
                                   :class="sidebarOpen ? 'px-4' : 'justify-center'">
                                    <div class="flex justify-center items-center h-6 w-6">
                                        <i class="bi bi-people-fill text-xl"></i>
                                    </div>
                                    <span x-show="sidebarOpen" class="ml-3 text-sm font-semibold uppercase tracking-wider">Data Siswa</span>
                                </a>
                            @endif

                            <!-- CHATBOT (Hanya Admin) -->
                            @if(Auth::user()->role === 'admin')
                                <a href="{{ route('admin.chatbot') }}" 
                                   class="nav-item flex items-center py-3 rounded-xl {{ request()->routeIs('admin.chatbot') ? 'active' : '' }}"
                                   :class="sidebarOpen ? 'px-4' : 'justify-center'">
                                    <div class="flex justify-center items-center h-6 w-6"><i class="bi bi-chat-dots-fill text-xl"></i></div>
                                    <span x-show="sidebarOpen" class="ml-3 text-sm font-semibold uppercase tracking-wider">AI Assistant</span>
                                </a>
                            @endif

                        @endauth
                    </div>
                </nav>

                <!-- LOGOUT BOTTOM -->
                <div class="p-4 border-t border-blue-500 flex-shrink-0 bg-blue-700">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="nav-item flex items-center w-full py-2 rounded-xl hover:bg-red-500 transition-colors" :class="sidebarOpen ? 'px-4' : 'justify-center'">
                            <div class="flex justify-center items-center h-6 w-6"><i class="bi bi-power text-xl"></i></div>
                            <span x-show="sidebarOpen" class="ml-3 text-sm font-bold uppercase">Logout</span>
                        </button>
                    </form>
                </div>
            </aside>

            <!-- MAIN CONTENT AREA -->
            <div class="flex-1 flex flex-col min-w-0 overflow-hidden bg-white">
                
                <!-- TOP NAVBAR -->
                <header class="h-16 bg-white border-b border-slate-100 flex items-center justify-between px-8 shadow-sm z-40 sticky top-0">
                    
                    <div class="flex items-center gap-6 flex-1">
                        <div class="flex items-center">
                            <div class="w-11 h-11 bg-blue-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/20 border border-blue-700 flex-shrink-0">
                                <svg class="w-7 h-7 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M11 5L6 9H2v6h4l5 4V5z"></path>
                                    <path d="M15.54 8.46a5 5 0 0 1 0 7.07"></path>
                                    <path d="M19.07 4.93a10 10 0 0 1 0 14.14"></path>
                                </svg>
                            </div>
                            <span class="ml-3 font-extrabold text-blue-600 text-2xl tracking-tighter uppercase whitespace-nowrap italic">SAPRAS</span>
                        </div>

                        @if (isset($header))
                            <div class="h-8 w-px bg-slate-200 hidden md:block"></div>
                            <div class="flex-1 font-semibold text-slate-700">
                                {{ $header }}
                            </div>
                        @endif
                    </div>

                    <!-- USER PROFILE DROPDOWN -->
                    <div class="flex items-center ml-4" x-data="{ userMenuOpen: false }">
                        <div class="relative">
                            @auth
                                <button @click="userMenuOpen = !userMenuOpen" @click.away="userMenuOpen = false" 
                                    class="flex items-center focus:outline-none group">
                                    <div class="w-11 h-11 bg-gradient-to-tr from-blue-700 to-blue-500 rounded-xl flex items-center justify-center font-bold text-white shadow-lg border-2 border-white transition-all group-hover:-translate-y-0.5">
                                        {{ substr(Auth::user()->name, 0, 1) }}
                                    </div>
                                </button>

                                <div x-show="userMenuOpen" 
                                    x-cloak
                                    x-transition:enter="transition ease-out duration-200"
                                    x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                                    x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                                    x-transition:even-end="opacity-100 scale-100 translate-y-0"
                                    class="absolute right-0 mt-3 w-52 bg-white rounded-2xl shadow-2xl border border-slate-100 py-2 z-50">
                                    
                                    <div class="px-5 py-4 border-b border-slate-50 mb-1">
                                        <p class="text-sm font-bold text-slate-800 truncate uppercase">{{ Auth::user()->name }}</p>
                                        <p class="text-[10px] text-blue-600 font-extrabold uppercase mt-0.5">{{ Auth::user()->role }}</p>
                                    </div>

                                    <div class="px-2">
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="flex items-center w-full px-4 py-2.5 text-sm text-red-500 hover:bg-red-50 rounded-lg transition-colors font-bold mt-1 text-left">
                                                <i class="bi bi-box-arrow-right mr-3 text-lg"></i> Logout
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endauth
                        </div>
                    </div>
                </header>

                <!-- MAIN CONTENT -->
                <main class="flex-1 overflow-y-auto p-6 md:p-8 bg-slate-50/50">
                    <div class="max-w-7xl mx-auto">
                        {{ $slot }}
                    </div>
                </main>
            </div>
        </div>
    @endif

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>