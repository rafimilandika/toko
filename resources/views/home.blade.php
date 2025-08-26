<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/home.css">
        <link rel="icon" type="image/svg+xml" href="/asset/logo.svg" />
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/767843534c.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Dashboard</title>
</head>
<body>
    <div class="homeKontener">
    <div class="zoomGambar" style="display: none;">
        <img src="" alt="" onclick="sembunyikanGambar()">
    </div>
    <div class="atas">

        <div class="topbar">
            <a href="/tambah">TAMBAH</a>
            <button id="edit-tabel-btn">EDIT</button>
        </div>
        <div class="search">
        <i class="fa-solid fa-xmark"></i>
            <input type="text" id="search" placeholder="Cari nama barang ..." autofocus>
        </div>
    </div>
        <div class="tabel">
  @foreach($barangs as $barang)
<div class="item-card" data-id="{{$barang->id}}">
    <div class="item-header">
        <div class="item-image" onclick="perbesarGambar(this)">
            <img src="{{ asset('storage/' . $barang->gambar) }}" alt="{{ $barang->nama }}">
        </div>
        <div class="item-details">
            <h4 class="item-name">{{$barang->nama}}</h4>
            <p class="item-price">Rp {{ number_format($barang->harga, 0, ',', '.') }}</p>
        </div>
    </div>
    
    <div class="item-actions">
        <div class="edit-kolom" style="display: none;">
            <div class="util">
                <form action="/editBarang" method="post">
                    @csrf
                    <input type="hidden" name="id" value="{{$barang->id}}">
                    <button type="submit" class="edit-btn">Edit</button>
                </form>
                <form action="/hapusBarang" method="post">
                    @csrf
                    <input type="hidden" name="id" value="{{$barang->id}}">
                    <button type="submit" class="hapus-btn">Hapus</button>
                </form>
            </div>
        </div>

        <div class="pilih-kolom">
            @if(isset($keranjang[$barang->id]))
                <button type="button" class="pilih-barang" style="display: none;">Pilih</button>
                <div class="kuantitas-container">
                    <input type="number" value="{{$keranjang[$barang->id]['kuantitas']}}" min="1" class="kuantitas-input">
                    <button type="button" class="tambah-keranjang">Tambah</button>
                    <button type="button" class="batal-checkout">Batal</button>
                </div>
            @else
                <button type="button" class="pilih-barang">Pilih</button>
                <div class="kuantitas-container" style="display: none;">
                    <input type="number" value="1" min="1" class="kuantitas-input">
                    <button type="button" class="tambah-keranjang">Tambah</button>
                    <button type="button" class="batal-checkout">Batal</button>
                </div>
            @endif
        </div>
    </div>
</div>
@endforeach

        </div>
    </div>
    <div class="checkout">
        <div class="data">

            <h3>Keranjang Belanja</h3>
            <div id="keranjang-info">
                @php
                $totalHarga = 0;
                $totalBanyakBarang = 0;
                foreach ($keranjang as $item) {
                    $totalHarga += $item['harga'] * $item['kuantitas'];
                    $totalBanyakBarang += $item['kuantitas'];
                }
                @endphp
                
                <p>Total Barang : <span id="total-barang">{{ $totalBanyakBarang }}</span></p>
                <p>Total Harga  : Rp <span id="total-harga">{{ number_format($totalHarga, 0, ',', '.') }}</span></p>
            </div>
        </div>
    <div class="util-keranjang">
        <button class="hapus-keranjang">HAPUS</button>
        <a href="/lihat-keranjang">KERANJANG</a>
    </div>
    </div>
</body>
<script src="js/checkout.js"></script>
<script src="js/editToogle.js"></script>
<script src="js/zoomGambar.js"></script>
<script>
    $(document).ready(function() {
    // Menangkap klik pada ikon silang
    $('.search i').on('click', function() {
        const searchInput = $(this).siblings('input');
        searchInput.val('');
        searchInput.trigger('keyup'); 
    });
});

$(document).ready(function() {
    // Menangkap klik pada tombol Hapus
    $(document).on("click", ".hapus-btn", function(e) {
        // Mencegah form untuk submit secara default
        e.preventDefault(); 
        
        const form = $(this).closest('form');
        
        // Menampilkan dialog konfirmasi SweetAlert
        swal({
            title: 'Apakah Anda yakin?',
            text: "Data yang dihapus tidak bisa dikembalikan!",
            icon: 'warning',
            buttons: true, // Menampilkan tombol konfirmasi dan batal
            dangerMode: true, // Memberikan warna merah pada tombol konfirmasi
        })
        .then((willDelete) => {
            // Jika pengguna mengklik "OK" atau tombol konfirmasi
            if (willDelete) {
                // Submit form hapus
                form.submit();
            }
        });
    });
});
</script>

@if(Session::has('success'))
<script>
    swal("Sukses", "{{ Session::get('success')}}", "success", {
        button: "ok",
    });
    </script>
    @endif
@if(Session::has('error'))
<script>
    swal("Gagal", "{{ Session::get('error')}}", "error", {
        button: "ok",
    });
    </script>
    @endif

</html>