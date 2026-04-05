<x-app-layout>
    <x-slot name="header">
       <div class="flex justify-end items-center">
            <a href="{{ url('admin/chatbot') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-xl font-bold text-xs text-white uppercase tracking-widest hover:bg-blue-700 shadow-md transition-all text-decoration-none">
                <i class="bi bi-robot me-2 text-white"></i> Tanya AI Chatbot
            </a>
        </div>
    </x-slot>

    <!-- Resources: Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        .card-custom { border-radius: 25px; border: none; box-shadow: 0 15px 35px rgba(0,0,0,0.05); }
        .table thead th { background-color: #0f172a; color: #ffffff; text-transform: uppercase; font-size: 10px; letter-spacing: 1.5px; padding: 20px; border: none; }
        .badge-lokasi { background-color: #2563eb; color: #ffffff; padding: 6px 14px; border-radius: 10px; font-weight: 700; font-size: 11px; display: inline-block; }
        .btn-detail { background-color: #f8fafc; color: #1e293b; border-radius: 12px; font-weight: 800; font-size: 11px; border: 1px solid #e2e8f0; transition: all 0.3s; }
        .btn-detail:hover { background-color: #0f172a; color: white; transform: translateY(-3px); }
        .avatar-circle { width: 40px; height: 40px; border-radius: 50%; background: #f1f5f9; display: flex; align-items: center; justify-content: center; font-weight: 800; color: #475569; }
        .modal-content { border-radius: 30px; border: none; overflow: hidden; }
        .feedback-admin { background-color: #f1f5f9; border-radius: 15px; padding: 15px; border-left: 5px solid #10b981; }
    </style>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Statistik Singkat -->
            <div class="row g-4 mb-5">
                <div class="col-md-4">
                    <div class="bg-white p-4 rounded-4 shadow-sm border-0 border-bottom border-4 border-success">
                        <p class="text-muted text-xs font-bold uppercase tracking-widest mb-1">Total Laporan Selesai</p>
                        <h3 class="text-dark font-black mb-0">{{ $riwayat->count() }} <small class="text-muted fs-6">Aspirasi</small></h3>
                    </div>
                </div>
                <div class="col-md-8 text-end d-flex align-items-center justify-content-end">
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-dark rounded-pill px-4 fw-bold shadow-sm text-decoration-none">
                        <i class="bi bi-arrow-left me-2"></i> KEMBALI KE DASHBOARD
                    </a>
                </div>
            </div>

            <!-- Judul di Atas Tabel -->
            <div class="mb-4 p-4 bg-white rounded-4 shadow-sm border border-slate-100">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-0">
                    <i class="bi bi-archive-fill text-blue-600 me-2"></i> Arsip Riwayat Aspirasi (Selesai)
                </h2>
            </div>

            <!-- Tabel Riwayat -->
            <div class="card card-custom bg-white">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-4">Tanggal</th>
                                    <th>Pelapor</th>
                                    <th>Isi Pelapor</th>
                                    <th>Lokasi</th>
                                    <th class="text-center pe-4">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($riwayat as $item)
                                <tr>
                                    <td class="ps-4 py-4">
                                        <div class="fw-bold text-dark">{{ $item->created_at->format('d/m/Y') }}</div>
                                        <small class="text-muted" style="font-size: 10px;">{{ $item->created_at->format('H:i') }} WIB</small>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-circle me-3">
                                                {{ substr($item->user->name, 0, 1) }}
                                            </div>
                                            <div class="fw-bold text-dark">{{ $item->user->name }}</div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="fw-semibold text-slate-700">{{ Str::limit($item->keterangan, 45) }}</div>
                                        <span class="text-success fw-bold" style="font-size: 9px; letter-spacing: 0.5px;">
                                            <i class="bi bi-check2-all me-1"></i>VERIFIED
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge-lokasi">
                                            <i class="bi bi-geo-alt-fill me-1"></i>{{ strtoupper($item->lokasi) }}
                                        </span>
                                    </td>
                                    <td class="text-center pe-4">
                                        <button class="btn btn-detail px-4 py-2" data-bs-toggle="modal" data-bs-target="#modalRiwayat{{ $item->id_pelaporan }}">
                                            <i class="bi bi-search me-2"></i>LIHAT DETAIL
                                        </button>
                                    </td>
                                </tr>

                                <!-- MODAL DETAIL -->
                                <div class="modal fade" id="modalRiwayat{{ $item->id_pelaporan }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content shadow-lg">
                                            <div class="modal-header border-0 pt-4 px-4">
                                                <h5 class="modal-title fw-bold text-dark small uppercase">Arsip Laporan #{{ $item->id_pelaporan }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body px-4 pb-4">
                                                <label class="fw-bold text-muted small uppercase mb-2">Foto Hasil Perbaikan:</label>
                                                <div class="rounded-4 overflow-hidden mb-4 border-4 border-white shadow-sm bg-light">
                                                    @php 
                                                        // Logika Folder: Gunakan bukti_selesai jika ada foto_selesai
                                                        $folder = $item->foto_selesai ? 'bukti_selesai' : 'aspirasi';
                                                        $namaFoto = $item->foto_selesai ? $item->foto_selesai : $item->foto;
                                                    @endphp
                                                    <img src="{{ asset('storage/' . $folder . '/' . $namaFoto) }}" 
                                                         class="img-fluid w-100" 
                                                         style="max-height: 350px; object-fit: cover;"
                                                         onerror="this.src='https://placehold.co/600x400?text=Foto+Tidak+Ditemukan'">
                                                </div>
                                                <div class="feedback-admin">
                                                    <label class="fw-bold text-success small uppercase mb-1 d-block">Pesan Konfirmasi Admin:</label>
                                                    <p class="mb-0 text-dark italic" style="font-size: 14px;">
                                                        "{{ $item->pesan_admin ?? 'Laporan telah diselesaikan dengan baik.' }}"
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- END MODAL -->

                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted italic">Belum ada riwayat aspirasi yang selesai.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</x-app-layout>