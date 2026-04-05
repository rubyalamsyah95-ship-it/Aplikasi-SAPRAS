<div class="container">
    <h2>Kirim Aspirasi SAPRAS</h2>
    <form action="{{ route('aspirasi.store') }}" method="POST">
        @csrf
        <select name="id_kategori" required>
            <option value="">-- Pilih Tingkat Kepentingan --</option>
            @foreach($kategori as $item)
                <option value="{{ $item->id_kategori }}">{{ $item->nama_kategori }}</option>
            @endforeach
        </select>
        <input type="text" name="lokasi" placeholder="Lokasi (Contoh: Kelas XI-A)" required>
        <textarea name="keterangan" placeholder="Isi Aspirasi Anda" required></textarea>
        <button type="submit">Kirim Sekarang</button>
    </form>
</div>