<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Siswa') }}
        </h2>
    </x-slot>

    <!-- Import Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <!-- Custom CSS -->
    <style>
        .btn-hover-dark:hover {
            background-color: #1e3a8a !important; 
            color: white !important;
            border-color: #1e3a8a !important;
            transform: translateY(-1px);
            transition: all 0.2s ease-in-out;
        }
        /* Memastikan teks tabel terlihat jelas (Hitam) */
        .text-table-main { color: #000000 !important; font-weight: 700; }
        .text-table-sub { color: #000000 !important; opacity: 0.8; font-style: italic; }
    </style>

    <div class="py-12" x-data="{ showModal: false, modalImage: '' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Alert Success -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4 border-0 shadow-sm" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- BOX STATUS ASPIRASI SAYA -->
            <div class="mb-4 p-4 bg-blue-600 rounded-4 border border-blue-500 d-flex justify-content-between align-items-center shadow-md">
                <div>
                    <h5 class="fw-bold mb-0 text-white">Status Aspirasi Saya</h5>
                    <small class="text-blue-100 opacity-90">Pantau laporan yang sedang diproses di sini.</small>
                </div>
                <a href="{{ route('aspirasi.riwayat') }}" class="btn btn-light rounded-pill px-4 fw-bold text-blue-600 border-0 shadow-sm btn-hover-dark text-decoration-none">
                    <i class="bi bi-clock-history me-2"></i> Riwayat Laporan
                </a>
            </div>

            <!-- Card Utama Aspirasi -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border border-slate-100">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Aspirasi Anda</h3>
                        <p class="text-sm text-gray-500">Pantau status laporan fasilitas sekolah Anda di sini.</p>
                    </div>
                    <a href="{{ route('aspirasi.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-bold text-xs text-white uppercase tracking-widest hover:bg-blue-700 shadow-md transition text-decoration-none">
                        Buat Aspirasi
                    </a>
                </div>

                <!-- Tabel Aspirasi -->
                <div class="relative overflow-x-auto border border-gray-100 sm:rounded-lg shadow-sm">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b">
                            <tr>
                                <th class="px-6 py-4">Lokasi & Kategori</th>
                                <th class="px-6 py-4">Keterangan</th>
                                <th class="px-6 py-4">Status & Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($aspirasis as $item)
                                <tr class="bg-white border-b hover:bg-gray-50 transition">
                                    <td class="px-6 py-4">
                                        <div class="text-table-main">{{ $item->lokasi }}</div>
                                        <div class="text-[10px] text-blue-600 uppercase font-semibold">{{ $item->kategori->nama_kategori }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="line-clamp-2 text-xs text-table-sub">{{ $item->keterangan }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($item->status == 'Selesai')
                                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-green-100 text-green-800 border border-green-200">SELESAI</span>
                                        @elseif($item->status == 'Menunggu Verifikasi')
                                            <button class="btn btn-sm btn-dark text-[10px] fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#modalBukti{{ $item->id_pelaporan }}">
                                                CEK BUKTI PERBAIKAN
                                            </button>
                                        @elseif($item->status == 'Diproses')
                                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-blue-100 text-blue-700 border border-blue-200 uppercase">DIPROSES</span>
                                        @else
                                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-yellow-100 text-yellow-800 border border-yellow-200">PENDING</span>
                                        @endif
                                    </td>
                                </tr>

                                <!-- Modal Konfirmasi -->
                                @if($item->status == 'Menunggu Verifikasi')
                                <div class="modal fade" id="modalBukti{{ $item->id_pelaporan }}" tabindex="-1" aria-hidden="true" x-data="{ openTolak: false }">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content border-0 shadow-lg rounded-4">
                                            <div class="modal-header bg-primary text-white border-0">
                                                <h5 class="modal-title fw-bold small uppercase">Konfirmasi Perbaikan</h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body p-4 text-center">
                                                @if($item->foto_selesai)
                                                    <div class="mb-3">
                                                        <img src="{{ asset('storage/bukti_selesai/' . $item->foto_selesai) }}" class="rounded-3 border w-full max-h-60 object-cover cursor-pointer shadow-sm" @click="showModal = true; modalImage = '{{ asset('storage/bukti_selesai/' . $item->foto_selesai) }}'">
                                                        <p class="text-[10px] text-muted mt-2">Klik gambar untuk memperbesar</p>
                                                    </div>
                                                @endif

                                                <div class="bg-light p-3 rounded-3 text-sm italic mb-4 border text-slate-600">
                                                    "{{ $item->pesan_admin ?? 'Tidak ada pesan dari admin.' }}"
                                                </div>

                                                <div class="d-flex justify-content-center gap-2">
                                                    <form action="{{ route('aspirasi.setujui', $item->id_pelaporan) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="btn btn-success fw-bold px-4 rounded-pill">SETUJU</button>
                                                    </form>
                                                    <button type="button" @click="openTolak = !openTolak" class="btn btn-outline-danger fw-bold px-4 rounded-pill">TOLAK</button>
                                                </div>

                                                <div x-show="openTolak" x-cloak x-transition class="mt-4 p-3 border-top border-danger bg-red-50 rounded-3 text-start">
                                                    <form action="{{ route('aspirasi.tolak', $item->id_pelaporan) }}" method="POST">
                                                        @csrf
                                                        <label class="small fw-bold text-danger uppercase mb-1 d-block">Alasan Penolakan:</label>
                                                        <textarea name="alasan" class="form-control mb-2 border-danger shadow-sm" rows="3" required placeholder="Jelaskan bagian mana yang belum sesuai..."></textarea>
                                                        <button type="submit" class="btn btn-danger btn-sm w-100 fw-bold rounded-pill">KIRIM PENOLAKAN</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif

                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-10 text-center text-dark fw-bold italic">Belum ada data aspirasi.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Lightbox Full Image -->
        <div x-show="showModal" x-cloak class="fixed inset-0 z-[9999] flex items-center justify-center p-4 bg-black/90" @keydown.escape.window="showModal = false">
            <div class="relative max-w-4xl w-full text-center">
                <button @click="showModal = false" class="absolute -top-12 right-0 text-white text-4xl hover:text-gray-300 transition">&times;</button>
                <img :src="modalImage" class="mx-auto max-w-full rounded shadow-2xl border-2 border-white/20">
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</x-app-layout>