<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <i class="bi bi-journal-text text-blue-600 me-2"></i>{{ __('Panel Riwayat Siswa') }}
        </h2>
    </x-slot>

    <!-- Resources: Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        .card-custom { border-radius: 20px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
        .table thead th { background-color: #f8f9fa; text-transform: uppercase; font-size: 11px; letter-spacing: 1px; color: #6c757d; border-bottom: 2px solid #f1f5f9; }
        .badge-lokasi { background-color: #2563eb; color: #ffffff; padding: 5px 12px; border-radius: 8px; font-weight: 600; font-size: 12px; display: inline-block; }
        .status-pill { padding: 6px 14px; border-radius: 50px; font-size: 11px; font-weight: 800; text-transform: uppercase; }
        .btn-lihat-foto { background-color: #f1f5f9; color: #475569; border-radius: 12px; font-weight: 700; font-size: 12px; border: 1px solid #e2e8f0; transition: all 0.2s; }
        .btn-lihat-foto:hover { background-color: #2563eb; color: white; border-color: #2563eb; transform: translateY(-2px); }
        .modal-content { border-radius: 25px; border: none; overflow: hidden; }
        .feedback-box { background-color: #f8fafc; border-left: 4px solid #2563eb; padding: 15px; border-radius: 0 12px 12px 0; }
    </style>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Judul Halaman -->
            <div class="mb-4 d-flex align-items-center">
                <div class="bg-blue-600 p-3 rounded-4 shadow-sm me-3">
                    <i class="bi bi-archive-fill text-white fs-4"></i>
                </div>
                <div>
                    <h3 class="fw-bold mb-0 text-dark">Riwayat Aspirasi</h3>
                    <p class="text-muted mb-0">Pantau perkembangan laporan fasilitas yang telah kamu kirim.</p>
                </div>
            </div>

            <div class="card card-custom bg-white">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-4 py-4">Tanggal</th>
                                    <th class="py-4">Laporan Kerusakan</th>
                                    <th class="py-4">Lokasi</th>
                                    <th class="py-4 text-center">Foto Bukti</th>
                                    <th class="py-4 pe-4 text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody style="border-top: 0;">
                                @forelse($riwayat as $item)
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold text-dark">{{ $item->created_at->format('d M Y') }}</div>
                                        <small class="text-muted">{{ $item->created_at->format('H:i') }} WIB</small>
                                    </td>
                                    <td>
                                        <div class="fw-semibold text-slate-700">{{ Str::limit($item->keterangan, 50) }}</div>
                                        <div class="text-primary small fw-bold mt-1" style="font-size: 10px;">
                                            #{{ $item->kategori->nama_kategori ?? 'UMUM' }}
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge-lokasi">
                                            <i class="bi bi-geo-alt-fill me-1"></i>{{ strtoupper($item->lokasi) }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-lihat-foto px-3 py-2 shadow-sm" 
                                                data-bs-toggle="modal" data-bs-target="#modalDetail{{ $item->id_pelaporan }}">
                                            <i class="bi bi-eye-fill me-2"></i>LIHAT FOTO
                                        </button>
                                    </td>
                                    <td class="text-center pe-4">
                                        @php
                                            $statusStyle = [
                                                'Pending' => 'bg-warning text-dark',
                                                'Diproses' => 'bg-primary text-white',
                                                'Selesai' => 'bg-success text-white'
                                            ];
                                            $pill = $statusStyle[$item->status] ?? 'bg-secondary text-white';
                                        @endphp
                                        <span class="status-pill {{ $pill }}">{{ $item->status }}</span>
                                    </td>
                                </tr>

                                <!-- MODAL DETAIL (Foto + Feedback) -->
                                <div class="modal fade" id="modalDetail{{ $item->id_pelaporan }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-md">
                                        <div class="modal-content shadow-lg">
                                            <div class="modal-header border-0 bg-white pt-4 px-4">
                                                <h5 class="modal-title fw-bold text-dark uppercase small">Detail Laporan #{{ $item->id_pelaporan }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body px-4 pb-4">
                                                <!-- Gambar Laporan (LOGIKA PERBAIKAN FOTO) -->
                                                <div class="rounded-4 overflow-hidden mb-3 shadow-sm border bg-light text-center">
                                                    @php
                                                        // Jika Selesai tampilkan foto dari folder bukti_selesai, jika tidak tampilkan foto awal (aspirasi)
                                                        $folder = ($item->status == 'Selesai') ? 'bukti_selesai' : 'aspirasi';
                                                        $namaFoto = ($item->status == 'Selesai') ? ($item->foto_selesai ?? $item->foto) : $item->foto;
                                                    @endphp
                                                    
                                                    <img src="{{ asset('storage/' . $folder . '/' . $namaFoto) }}" 
                                                         class="img-fluid w-100" 
                                                         style="max-height: 350px; object-fit: cover;"
                                                         onerror="this.src='https://placehold.co/600x400?text=Foto+Tidak+Ditemukan'">
                                                </div>

                                                <!-- Feedback Admin -->
                                                <div class="mt-3">
                                                    <label class="fw-bold text-muted small uppercase mb-2">Tanggapan Admin:</label>
                                                    <div class="feedback-box">
                                                        <p class="mb-0 text-dark" style="font-size: 14px; line-height: 1.6;">
                                                            @if($item->pesan_admin)
                                                                <i class="bi bi-chat-left-quote-fill text-blue-600 me-2"></i>"{{ $item->pesan_admin }}"
                                                            @else
                                                                <span class="text-muted italic small">Belum ada pesan tambahan dari admin.</span>
                                                            @endif
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer border-0 bg-light py-3">
                                                <button type="button" class="btn btn-dark rounded-pill px-4 fw-bold small" data-bs-dismiss="modal">TUTUP</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- END MODAL -->

                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <div class="opacity-50">
                                            <i class="bi bi-inbox fs-1"></i>
                                            <p class="mt-2 fw-bold">Belum ada riwayat laporan.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Script Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</x-app-layout>