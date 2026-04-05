<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-end items-center">
            <a href="{{ url('admin/chatbot') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-xl font-bold text-xs text-white uppercase tracking-widest hover:bg-blue-700 shadow-md transition-all text-decoration-none">
                <i class="bi bi-robot me-2 text-white"></i> Tanya AI Chatbot
            </a>
        </div>
    </x-slot>

    <!-- Resources -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        .status-badge { font-size: 0.7rem; text-transform: uppercase; font-weight: 800; padding: 0.5em 1em; border-radius: 50px; }
        .table-hover tbody tr:hover { background-color: rgba(37, 99, 235, 0.02); }
        .btn-blue-solid { 
            background-color: #2563eb !important; 
            color: white !important; 
            border: none;
            font-size: 11px; 
            font-weight: 800; 
            letter-spacing: 0.5px; 
            padding: 8px 20px;
            border-radius: 12px;
            transition: all 0.3s;
        }
        .btn-blue-solid:hover { 
            background-color: #1d4ed8 !important; 
            transform: translateY(-2px); 
            box-shadow: 0 5px 15px rgba(37, 99, 235, 0.3);
            color: white !important;
        }
        .card-custom { border-radius: 20px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
        .modal-content { border-radius: 25px; border: none; }
        .alert-reason {
            background-color: #fff5f5;
            border-left: 4px solid #f87171;
            color: #991b1b;
            font-size: 11px;
            padding: 8px;
            margin-top: 8px;
            border-radius: 8px;
        }
        /* Memastikan teks tabel tetap hitam pekat */
        .text-black-custom { color: #000000 !important; font-weight: 500; }
        .text-muted-custom { color: #4b5563 !important; font-size: 12px; }
    </style>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4 shadow-sm border-0 border-start border-4 border-success rounded-4" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="mb-4 d-flex justify-content-between align-items-center p-4 bg-white rounded-4 shadow-sm border border-slate-100">
                <div>
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-1">
                        <i class="bi bi-tools text-blue-600 me-2"></i> Admin Dashboard - Daftar Aspirasi
                    </h2>
                    <p class="text-muted mb-0 small italic">Manajemen laporan fasilitas sekolah yang memerlukan tindak lanjut.</p>
                </div>
                
                <a href="{{ route('admin.riwayat') }}" class="btn btn-dark rounded-pill px-4 shadow-sm fw-bold small text-decoration-none d-flex align-items-center">
                    <i class="bi bi-archive-fill me-2"></i> LIHAT RIWAYAT
                </a>
            </div>

            <div class="card card-custom bg-white overflow-hidden border-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-slate-50">
                            <tr class="text-dark small uppercase" style="font-size: 10px; letter-spacing: 1px; font-weight: 700;">
                                <th class="ps-4">Nama Pelapor</th>
                                <th>kategori</th> 
                                <th>lokasi & Keterangan</th>
                                <th class="text-center">Lampiran</th>
                                <th>Status</th>
                                <th class="text-end pe-4">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody style="font-size: 13px;">
                            @forelse($laporan as $row)
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle bg-blue-100 text-blue-600 fw-bold d-flex align-items-center justify-content-center me-3" style="width: 35px; height: 35px; font-size: 12px;">
                                            {{ substr($row->user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark">{{ $row->user->name }}</div>
                                            <small class="text-muted">{{ $row->user->role }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <!-- Menampilkan Nama Kategori di bawah kolom Keterangan -->
                                    <span class="badge bg-light text-dark border px-2 py-1">
                                        {{ $row->kategori->nama_kategori }}
                                    </span>
                                </td>
                                <td>
                                    <!-- Teks Lokasi dan Keterangan dibuat Hitam (#000) -->
                                    <div class="text-black-custom mb-0" style="color: #000 !important;">{{ $row->lokasi }}</div>
                                    <div class="text-muted-custom italic" style="color: #000 !important; opacity: 0.8;">{{ $row->keterangan }}</div>
                                    
                                    @if($row->alasan_penolakan)
                                        <div class="alert-reason shadow-sm">
                                            <i class="bi bi-exclamation-triangle-fill me-1"></i>
                                            <strong>Ditolak Siswa:</strong> "{{ $row->alasan_penolakan }}"
                                        </div>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($row->foto)
                                        <button type="button" class="btn btn-primary bg-blue-600 border-0 shadow-sm" style="font-size: 10px; padding: 5px 12px; border-radius: 8px;" data-bs-toggle="modal" data-bs-target="#modalLihatFoto{{ $row->id_pelaporan }}">
                                            <i class="bi bi-image me-1 text-white"></i> LIHAT FOTO
                                        </button>
                                    @else
                                        <span class="text-muted opacity-50 small">TIDAK ADA FOTO</span>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $badges = [
                                            'Pending' => 'bg-warning text-dark',
                                            'Diproses' => 'bg-primary text-white',
                                            'Menunggu Verifikasi' => 'bg-info text-white',
                                            'Selesai' => 'bg-success text-white'
                                        ];
                                        $currentBadge = $badges[$row->status] ?? 'bg-secondary text-white';
                                    @endphp
                                    <span class="badge {{ $currentBadge }} status-badge shadow-sm">{{ $row->status }}</span>
                                </td>
                                <td class="text-end pe-4">
                                    <button class="btn btn-blue-solid shadow-md text-white" data-bs-toggle="modal" data-bs-target="#modalTanggapan{{ $row->id_pelaporan }}">
                                        <i class="bi bi-pencil-square me-2 text-white"></i> TANGGAPI
                                    </button>
                                </td>
                            </tr>

                            <!-- MODAL LIHAT FOTO -->
                            <div class="modal fade" id="modalLihatFoto{{ $row->id_pelaporan }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content shadow-lg">
                                        <div class="modal-header border-0">
                                            <h6 class="modal-title fw-bold">Lampiran Laporan #{{ $row->id_pelaporan }}</h6>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body p-0 text-center bg-light">
                                            <img src="{{ asset('storage/aspirasi/' . $row->foto) }}" class="img-fluid" style="max-height: 500px; width: auto;">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- MODAL TANGGAPAN -->
                            <div class="modal fade" id="modalTanggapan{{ $row->id_pelaporan }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-0 shadow-lg">
                                        <form action="{{ route('admin.tanggapi', $row->id_pelaporan) }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <div class="modal-header bg-dark text-white border-0 py-3 px-4">
                                                <h5 class="modal-title fs-6 fw-bold mb-0"><i class="bi bi-chat-left-dots me-2"></i>Berikan Tanggapan</h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body p-4">
                                                <div class="mb-4">
                                                    <label class="form-label fw-bold text-dark small">Update Status Laporan</label>
                                                    <select name="status" class="form-select rounded-3 border-slate-200" onchange="toggleSelesaiInput(this, {{ $row->id_pelaporan }})" required>
                                                        <option value="Pending" {{ $row->status == 'Pending' ? 'selected' : '' }}>Pending (Antrean)</option>
                                                        <option value="Diproses" {{ $row->status == 'Diproses' ? 'selected' : '' }}>Sedang Diproses</option>
                                                        <option value="Selesai" {{ $row->status == 'Menunggu Verifikasi' ? 'selected' : '' }}>Selesai (Kirim Bukti)</option>
                                                    </select>
                                                </div>

                                                <div id="inputSelesai{{ $row->id_pelaporan }}" style="display: {{ $row->status == 'Menunggu Verifikasi' ? 'block' : 'none' }};">
                                                    <div class="mb-4">
                                                        <label class="form-label fw-bold text-dark small">Foto Bukti Selesai <span class="text-danger">*</span></label>
                                                        <input type="file" name="foto_selesai" class="form-control rounded-3 border-slate-200">
                                                        <small class="text-muted mt-1 d-block">Wajib diunggah jika status 'Selesai'.</small>
                                                    </div>
                                                    <div class="mb-0">
                                                        <label class="form-label fw-bold text-dark small">Pesan Konfirmasi Admin</label>
                                                        <textarea name="pesan_admin" class="form-control rounded-3 border-slate-200" rows="3" placeholder="Contoh: Perbaikan AC sudah selesai dilakukan...">{{ $row->pesan_admin }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer border-0 p-4 pt-0">
                                                <button type="button" class="btn btn-light rounded-pill px-4 fw-bold small" data-bs-dismiss="modal">BATAL</button>
                                                <button type="submit" class="btn btn-blue-solid rounded-pill px-4 shadow-sm">SIMPAN PERUBAHAN</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-dark fw-bold">Belum ada aspirasi masuk.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleSelesaiInput(select, id) {
            const inputDiv = document.getElementById('inputSelesai' + id);
            const fileInput = inputDiv.querySelector('input[type="file"]');
            if (select.value === 'Selesai') {
                inputDiv.style.display = 'block';
                fileInput.required = true;
            } else {
                inputDiv.style.display = 'none';
                fileInput.required = false;
            }
        }
    </script>
</x-app-layout>