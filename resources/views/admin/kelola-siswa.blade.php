<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between w-full">
            <div class="flex items-center gap-2">
                <i class="bi bi-people-fill text-blue-600 text-xl"></i>
                <h2 class="font-bold text-xl text-slate-800 leading-tight">{{ __('Data Akun Siswa') }}</h2>
            </div>
            <a href="{{ route('admin.chatbot') }}" class="flex items-center gap-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-4 py-2 rounded-xl shadow-md">
                <i class="bi bi-robot text-lg"></i>
                <span class="text-sm font-bold hidden md:block">TANYA AI</span>
            </a>
        </div>
    </x-slot>

    <div class="py-6 px-4 md:px-8">
        <!-- Fitur Atas: Search & Tombol Aksi -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-6">
            <!-- Search Bar -->
            <div class="lg:col-span-1">
                <form action="{{ route('admin.siswa.index') }}" method="GET" class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, NIS, kelas..." 
                        class="w-full pl-10 pr-4 py-2.5 rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 shadow-sm">
                    <i class="bi bi-search absolute left-3 top-3 text-slate-400"></i>
                </form>
            </div>

            <!-- Tombol Tambah, Import & Hapus Massal -->
            <div class="lg:col-span-2 flex flex-wrap gap-2 justify-end">
                <!-- Fitur Baru: Hapus Per Angkatan -->
                <button onclick="openModal('modalHapusAngkatan')" class="bg-rose-50 hover:bg-rose-100 text-rose-600 px-4 py-2.5 rounded-xl text-sm font-bold transition-all flex items-center gap-2 border border-rose-200">
                    <i class="bi bi-trash-fill"></i> HAPUS PER ANGKATAN
                </button>

                <button onclick="openModal('modalTambah')" class="bg-emerald-500 hover:bg-emerald-600 text-white px-4 py-2.5 rounded-xl text-sm font-bold transition-all flex items-center gap-2 shadow-lg shadow-emerald-100">
                    <i class="bi bi-person-plus-fill"></i> TAMBAH MURID
                </button>
                
                <div class="bg-white p-2 rounded-xl border border-slate-200 flex items-center gap-2 shadow-sm">
                    <form action="{{ route('admin.siswa.import') }}" method="POST" enctype="multipart/form-data" class="flex items-center gap-2">
                        @csrf
                        <input type="file" name="file_excel" required class="text-[10px] w-40">
                        <button type="submit" class="bg-blue-600 text-white px-3 py-1.5 rounded-lg text-[10px] font-bold uppercase">Import</button>
                    </form>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-4 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl flex items-center gap-3">
                <i class="bi bi-check-circle-fill"></i>
                <span class="font-bold text-sm">{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 p-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-xl flex items-center gap-3">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <span class="font-bold text-sm">{{ session('error') }}</span>
            </div>
        @endif

        <!-- Tabel -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-slate-50 text-slate-500 text-[11px] font-black tracking-widest border-b border-slate-200 uppercase">
                            <th class="px-6 py-4">Siswa</th>
                            <th class="px-6 py-4 text-center">Identitas</th>
                            <th class="px-6 py-4">Kelas / Angkatan</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($siswas as $siswa)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-800">{{ $siswa->name }}</div>
                                <div class="text-xs text-slate-400">{{ $siswa->email ?? 'Tidak ada email' }}</div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="bg-blue-50 text-blue-600 px-3 py-1 rounded-full text-xs font-mono border border-blue-100">
                                    {{ $siswa->nis }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-semibold text-slate-700">{{ $siswa->kelas }}</div>
                                <div class="text-[10px] text-slate-400 font-bold uppercase">{{ $siswa->angkatan }}</div>
                            </td>
                            <td class="px-6 py-4 flex justify-center gap-2">
                                <button onclick="openResetModal('{{ $siswa->id }}', '{{ $siswa->name }}')" class="p-2 bg-orange-50 text-orange-600 rounded-lg hover:bg-orange-600 hover:text-white transition-all" title="Ganti Password">
                                    <i class="bi bi-key-fill"></i>
                                </button>
                                
                                <form action="{{ route('admin.siswa.destroy', $siswa->id) }}" method="POST" onsubmit="return confirm('Hapus permanen akun ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 bg-rose-50 text-rose-600 rounded-lg hover:bg-rose-600 hover:text-white transition-all">
                                        <i class="bi bi-trash3-fill"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-slate-400 italic">Data tidak ditemukan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- MODAL TAMBAH MURID -->
    <div id="modalTambah" class="fixed inset-0 bg-slate-900/50 hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl w-full max-w-md p-6 shadow-2xl">
            <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
                <i class="bi bi-person-plus text-blue-600"></i> Tambah Murid Pindahan
            </h3>
            <form action="{{ route('admin.siswa.store') }}" method="POST" class="space-y-4">
                @csrf
                <input type="text" name="name" placeholder="Nama Lengkap" class="w-full rounded-xl border-slate-200" required>
                <div class="grid grid-cols-2 gap-4">
                    <input type="text" name="nis" placeholder="NIS (Username)" class="w-full rounded-xl border-slate-200" required>
                    <input type="email" name="email" placeholder="Email (Opsional)" class="w-full rounded-xl border-slate-200">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <input type="text" name="kelas" placeholder="Kelas (Contoh: XII RPL 1)" class="w-full rounded-xl border-slate-200" required>
                    <input type="number" name="angkatan" placeholder="Angkatan (Tahun)" class="w-full rounded-xl border-slate-200" required>
                </div>
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" onclick="closeModal('modalTambah')" class="px-4 py-2 text-slate-500 font-bold text-sm">BATAL</button>
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-xl font-bold text-sm">SIMPAN DATA</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL HAPUS PER ANGKATAN -->
    <div id="modalHapusAngkatan" class="fixed inset-0 bg-slate-900/50 hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl w-full max-w-md p-6 shadow-2xl">
            <h3 class="text-lg font-bold text-slate-800 mb-2 flex items-center gap-2 text-rose-600">
                <i class="bi bi-exclamation-triangle-fill"></i> Hapus Per Angkatan
            </h3>
            <p class="text-sm text-slate-500 mb-4">Semua akun siswa pada tahun angkatan yang Anda masukkan akan dihapus permanen.</p>
            <form action="{{ route('admin.siswa.destroyByAngkatan') }}" method="POST" onsubmit="return confirm('PERINGATAN: Apakah Anda yakin ingin menghapus SEMUA siswa di angkatan tersebut?')">
                @csrf @method('DELETE')
                <div class="mb-4">
                    <label class="block text-xs font-bold text-slate-400 uppercase mb-1">Tahun Angkatan</label>
                    <input type="number" name="angkatan" placeholder="Contoh: 2022" class="w-full rounded-xl border-slate-200" required>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeModal('modalHapusAngkatan')" class="px-4 py-2 text-slate-500 font-bold text-sm">BATAL</button>
                    <button type="submit" class="bg-rose-600 text-white px-6 py-2 rounded-xl font-bold text-sm">HAPUS DATA</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL RESET PASSWORD -->
    <div id="modalReset" class="fixed inset-0 bg-slate-900/50 hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl w-full max-w-sm p-6 shadow-2xl">
            <h3 class="text-lg font-bold text-slate-800 mb-1">Ganti Password</h3>
            <p id="resetSiswaName" class="text-sm text-slate-500 mb-4"></p>
            <form id="formResetPass" method="POST">
                @csrf
                <div class="relative">
                    <input type="password" name="new_password" id="inputPass" placeholder="Masukkan Password Baru" class="w-full rounded-xl border-slate-200 pr-10" required minlength="4">
                    <button type="button" onclick="togglePass()" class="absolute right-3 top-3 text-slate-400"><i class="bi bi-eye" id="eyeIcon"></i></button>
                </div>
                <div class="flex justify-end gap-2 mt-6">
                    <button type="button" onclick="closeModal('modalReset')" class="px-4 py-2 text-slate-500 font-bold text-sm">BATAL</button>
                    <button type="submit" class="bg-orange-600 text-white px-6 py-2 rounded-xl font-bold text-sm">UPDATE PASSWORD</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal(id) { document.getElementById(id).classList.remove('hidden'); }
        function closeModal(id) { document.getElementById(id).classList.add('hidden'); }

        function openResetModal(id, name) {
            document.getElementById('resetSiswaName').innerText = "Update password untuk: " + name;
            document.getElementById('formResetPass').action = "/admin/siswa/reset/" + id;
            openModal('modalReset');
        }

        function togglePass() {
            const input = document.getElementById('inputPass');
            const icon = document.getElementById('eyeIcon');
            if (input.type === "password") {
                input.type = "text";
                icon.classList.replace('bi-eye', 'bi-eye-slash');
            } else {
                input.type = "password";
                icon.classList.replace('bi-eye-slash', 'bi-eye');
            }
        }
    </script>
</x-app-layout>