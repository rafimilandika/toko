<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/keranjang.css">
            <link rel="icon" type="image/svg+xml" href="/asset/logo.svg" />
    <title>Keranjang Belanja</title>
    <style>
        
    </style>
</head>
<body>
    <header class="header-keranjang">
        <a href="/" class="kembali-btn">Kembali</a>
        <h1 class="judul-halaman">Keranjang</h1>
    </header>

    @if($keranjang && count($keranjang) > 0)
        @php
            $totalHarga = 0;
            foreach ($keranjang as $item) {
                $totalHarga += $item['harga'] * $item['kuantitas'];
            }
        @endphp

        <div class="total-container">
            <h2>Total Pembayaran</h2>
            <p>Rp {{ number_format($totalHarga, 0, ',', '.') }}</p>
        </div>

        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Qty</th>
                    <th>Harga</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @php $no = 1; @endphp
                @foreach($keranjang as $item)
                    <tr>
                        <td>{{ $no++ }}</td>
                        <td>{{ $item['nama'] }}</td>
                        <td>{{ $item['kuantitas'] }}</td>
                        <td>Rp {{ number_format($item['harga'], 0, ',', '.') }}</td>
                        @php $harga_total_barang = $item['kuantitas'] * $item['harga']; @endphp
                        <td>Rp {{ number_format($harga_total_barang, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="empty-cart">
            <h1>Tidak ada barang di keranjang.</h1>
        </div>
    @endif
</body>
</html>