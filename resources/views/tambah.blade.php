<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/tambah.css">
            <link rel="icon" type="image/svg+xml" href="/asset/logo.svg" />

    <title>Tambah Barang</title>
</head>
<body>
    <div class="tambahKontener">
        <div class="header-tambah">

            <a href="/">kembali</a>
            <h2 class="judul-halaman">TAMBAH BARANG</h2>
        </div>
        <form action="/inputData" method="post" enctype="multipart/form-data">
            @csrf
            <div class="gambar_preview">
                <img src="/asset/photo.png" alt="">
            </div>
            <label for="">gambar</label>
            <input type="file" name="gambar" id="inputGambar" accept="image/*" capture="environment">
            <label for="">nama</label>
            <input type="text" name="nama">
            <label for="">harga</label>
            <input type="number" name="harga">
            <button type="submit">SIMPAN</button>
        </form>
    </div>
</body>
<script>
    const inputGambar = document.getElementById('inputGambar');
    const previewGambar = document.querySelector('.gambar_preview img');
    inputGambar.addEventListener('change', function(event) {
        const [file] = event.target.files;
        if (file) {
            previewGambar.src = URL.createObjectURL(file);
        }
    });
    </script>
</html>