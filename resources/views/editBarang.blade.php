<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/editBarang.css">
            <link rel="icon" type="image/svg+xml" href="/asset/logo.svg" />

    <title>Edit Barang</title>
</head>
<body>
    <div class="editBarangKontener">
        <div class="header-edit">

            <a href="/">kembali</a>
            <h1 class="judul-halaman">Edit Barang</h1>
        </div>
        <form action="/updatetData" method="post" enctype="multipart/form-data">
            @csrf
            <div class="gambar_preview">
                <img src="{{ asset('storage/' . $barangs->gambar) }}" alt="{{ $barangs->nama }} ">
            </div>
            <input type="hidden" name="gambar_lama" value="{{$barangs->gambar}}">
            <input type="hidden" name="id" value="{{$barangs->id}}">
            <label for="">gambar</label>
            <input type="file" name="gambar" id="inputGambar">
            <label for="">nama</label>
            <input type="text" name="nama" value="{{$barangs->nama}}">
            <label for="">harga</label>
            <input type="number" name="harga" value="{{$barangs->harga}}">
            <button type="submit">SIMPAN</button>
        </form>
    </div>
</body>
<script>
    // Ambil elemen-elemen yang dibutuhkan dari DOM
    const inputGambar = document.getElementById('inputGambar');
    const previewGambar = document.querySelector('.gambar_preview img');

    // Tambahkan event listener untuk mendeteksi perubahan pada input file
    inputGambar.addEventListener('change', function(event) {
        // Cek apakah ada file yang diinputkan
        const [file] = event.target.files;

        if (file) {
            // Jika ada file, baca sebagai URL data dan atur ke src gambar
            previewGambar.src = URL.createObjectURL(file);
        }
    });
</script>
</html>
