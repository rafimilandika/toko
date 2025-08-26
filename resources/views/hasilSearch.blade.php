@if($hasil)
  @foreach($hasil as $hsl)

  <div class="item-card" data-id="{{$hsl->id}}">
    <div class="item-header">
        <div class="item-image" onclick="perbesarGambar(this)">
            <img src="{{ asset('storage/' . $hsl->gambar) }}" alt="{{ $hsl->nama }}">
        </div>
        <div class="item-details">
            <h4 class="item-name">{{$hsl->nama}}</h4>
            <p class="item-price">Rp {{ number_format($hsl->harga, 0, ',', '.') }}</p>
        </div>
    </div>
    
    <div class="item-actions">
        <div class="edit-kolom" style="display: none;">
            <div class="util">
                <form action="/editBarang" method="post">
                    @csrf
                    <input type="hidden" name="id" value="{{$hsl->id}}">
                    <button type="submit" class="edit-btn">Edit</button>
                </form>
                <form action="/hapusBarang" method="post">
                    @csrf
                    <input type="hidden" name="id" value="{{$hsl->id}}">
                    <button type="submit" class="hapus-btn">Hapus</button>
                </form>
            </div>
        </div>

        <div class="pilih-kolom">
            @if(isset($keranjang[$hsl->id]))
                <button type="button" class="pilih-barang" style="display: none;">Pilih</button>
                <div class="kuantitas-container">
                    <input type="number" value="{{$keranjang[$hsl->id]['kuantitas']}}" min="1" class="kuantitas-input">
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
        @endif