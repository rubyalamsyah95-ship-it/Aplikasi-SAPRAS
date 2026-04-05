<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow sm:rounded-lg">
                <h2 class="font-bold text-xl mb-6">Kirim Aspirasi Baru</h2>
                
                <form action="{{ route('aspirasi.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-4">
                        <label class="block font-bold mb-1">Pilih Kategori</label>
                        <select name="id_kategori" class="w-full border-gray-300 rounded shadow-sm" required>
                            <option value="">-- Pilih --</option>
                            @foreach($kategoris as $kat)
                                <option value="{{ $kat->id_kategori }}">{{ $kat->nama_kategori }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block font-bold mb-1">Lokasi</label>
                        <input type="text" name="lokasi" class="w-full border-gray-300 rounded shadow-sm" required>
                    </div>

                    <div class="mb-4">
                        <label class="block font-bold mb-1">Keterangan</label>
                        <textarea name="keterangan" rows="4" class="w-full border-gray-300 rounded shadow-sm" required></textarea>
                    </div>

                    <div class="mb-4">
                        <label class="block font-bold mb-1">Foto Bukti (Opsional)</label>
                        <input type="file" name="foto" class="w-full border-gray-300 rounded shadow-sm p-1" accept="image/*">
                        <p class="text-xs text-gray-500 mt-1">*Format: JPG, PNG, JPEG (Maks. 2MB)</p>
                    </div>

                    <div class="flex justify-end gap-2">
                        <a href="{{ route('aspirasi.index') }}" class="px-4 py-2 bg-gray-200 rounded">Batal</a>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Kirim Laporan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>