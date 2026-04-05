<x-app-layout>
    <style>
        /* CSS Khusus Halaman Welcome */
        .welcome-body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #0f172a;
            background-image: 
                radial-gradient(at 0% 0%, rgba(30, 64, 175, 0.3) 0, transparent 50%), 
                radial-gradient(at 100% 100%, rgba(124, 58, 237, 0.2) 0, transparent 50%);
            min-height: 100vh;
            color: #e2e8f0;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .hover-glow:hover {
            box-shadow: 0 0 20px rgba(59, 130, 246, 0.4);
            transform: translateY(-5px);
            transition: all 0.3s ease;
        }
    </style>

    <div class="welcome-body antialiased">
        <nav class="p-6">
            <div class="max-w-7xl mx-auto flex justify-between items-center">
                <div class="flex items-center space-x-2">
                    <div class="bg-blue-600 p-2 rounded-lg shadow-lg shadow-blue-500/50">
                        <i class="bi bi-megaphone-fill text-white"></i>
                    </div>
                    <span class="text-xl font-bold tracking-tighter text-white uppercase italic">SAPRAS</span>
                </div>

                @if (Route::has('login'))
                    <div class="space-x-4">
                        @auth
                            <a href="{{ Auth::user()->role === 'admin' ? route('admin.dashboard') : route('dashboard') }}" 
                               class="text-sm font-semibold hover:text-blue-400 transition">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="text-sm font-semibold hover:text-blue-400 transition">Log in</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="bg-white text-slate-900 px-5 py-2 rounded-full text-sm font-bold hover:bg-blue-50 transition shadow-lg">Daftar Sekarang</a>
                            @endif
                        @endauth
                    </div>
                @endif
            </div>
        </nav>

        <main class="max-w-7xl mx-auto px-6 pt-20 pb-32 flex flex-col items-center text-center">
            <div class="inline-flex items-center space-x-2 bg-blue-500/10 border border-blue-500/20 px-4 py-1.5 rounded-full mb-8">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-500"></span>
                </span>
                <span class="text-xs font-bold text-blue-400 uppercase tracking-widest">Suara Anda Adalah Prioritas Kami</span>
            </div>

            <h1 class="text-5xl md:text-7xl font-extrabold text-white mb-6 tracking-tight">
                Sampaikan Aspirasi <br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-indigo-500">
                    Untuk Sekolah Lebih Baik
                </span>
            </h1>

            <p class="max-w-2xl text-gray-400 text-lg mb-10 leading-relaxed">
                Platform digital khusus siswa untuk melaporkan keluhan fasilitas, memberikan saran, dan memantau perbaikan secara transparan.
            </p>

            <div class="flex flex-col sm:flex-row gap-4">
                <a href="{{ route('login') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-4 rounded-xl font-bold shadow-xl shadow-blue-600/20 transition-all flex items-center justify-center text-decoration-none">
                    Mulai Lapor <i class="bi bi-arrow-right ml-2"></i>
                </a>
                <a href="#features" class="glass-card px-8 py-4 rounded-xl font-bold text-white hover:bg-white/10 transition flex items-center justify-center text-decoration-none">
                    Pelajari Fitur
                </a>
            </div>

            <div id="features" class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-32 w-full">
                <div class="glass-card p-8 rounded-2xl text-left hover-glow">
                    <div class="w-12 h-12 bg-blue-500/20 rounded-lg flex items-center justify-center mb-6 border border-blue-500/30">
                        <i class="bi bi-lightning-charge text-blue-400 text-xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3">Laporan Cepat</h3>
                    <p class="text-gray-400 text-sm">Proses penginputan aspirasi yang simpel tanpa prosedur yang berbelit-belit.</p>
                </div>

                <div class="glass-card p-8 rounded-2xl text-left hover-glow">
                    <div class="w-12 h-12 bg-indigo-500/20 rounded-lg flex items-center justify-center mb-6 border border-indigo-500/30">
                        <i class="bi bi-eye text-indigo-400 text-xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3">Transparan</h3>
                    <p class="text-gray-400 text-sm">Pantau status laporan kamu mulai dari 'Pending', 'Diproses', hingga 'Selesai'.</p>
                </div>

                <div class="glass-card p-8 rounded-2xl text-left hover-glow">
                    <div class="w-12 h-12 bg-emerald-500/20 rounded-lg flex items-center justify-center mb-6 border border-emerald-500/30">
                        <i class="bi bi-shield-check text-emerald-400 text-xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3">Verifikasi Dua Arah</h3>
                    <p class="text-gray-400 text-sm">Siswa berhak menyetujui atau menolak hasil perbaikan jika belum sesuai.</p>
                </div>
            </div>
        </main>

        <footer class="border-t border-white/5 py-12">
            <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row justify-between items-center text-gray-500 text-sm">
                <p>&copy; 2026 Aplikasi SAPRAS. Project UKK RPL.</p>
                <div class="flex space-x-6 mt-4 md:mt-0">
                    <a href="#" class="hover:text-white transition">Panduan Penggunaan</a>
                    <a href="#" class="hover:text-white transition">Kebijakan Privasi</a>
                </div>
            </div>
        </footer>
    </div>
</x-app-layout>