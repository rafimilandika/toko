<?php

namespace App\Http\Controllers;

use App\Models\home;
use App\Models\Barang;
use App\Http\Requests\StorehomeRequest;
use App\Http\Requests\UpdatehomeRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Cloudinary\Cloudinary;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $barangs = Barang::all();
        $keranjang = session()->get('keranjang', []);
        return view('home',[
            'barangs' => $barangs,
            'keranjang' => $keranjang
        ]);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function tambah()
    {
        return view('tambah');
        // dd("tambah");
    }
    public function inputData(Request $request)
    {
        $request->validate([
            'nama' => 'required|string',
            'harga' => 'required|numeric',
            'gambar' => 'required|image',
            ]);
        // $pathGambar = $request->file('gambar')->store('gambar', 'public');

        $cloudinary = new Cloudinary([
    'cloud' => [
        'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
        'api_key'    => env('CLOUDINARY_API_KEY'),
        'api_secret' => env('CLOUDINARY_API_SECRET'),
    ],
]);

$pathGambar = $cloudinary->uploadApi()->upload(
    $request->file('gambar')->getRealPath()
)['secure_url'];

        try{
                Barang::create([
                    'nama' => $request->nama,
                    'harga' => $request->harga,
                    'gambar' => $pathGambar,
                ]);
return redirect('/')->with('success', 'Data berhasil ditambahkan');
        }catch (\Exception $e) { 
        return redirect()->back()->with('error', 'Gagal menambahkan barang: ' . $e->getMessage());
    }
    }
    public function editBarang(Request $request)
    {
        $id = $request->id;
        $idBarangs = Barang::where('id', $id)->get();
        return view('editBarang',[
            'barangs' => $idBarangs[0],
        ]);
    }
    public function updatetData(Request $request)
    {
        $request->validate([
            'nama' => 'required|string',
            'harga' => 'required|numeric',
            ]);
            if ($request->file('gambar')) {
                 $validator = Validator::make($request->only('gambar'), [
                'gambar' => 'image',
            ]);
            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }
            if($request->gambarLama)
            {
                Storage::delete($request->gambar_lama);
            }
            // $pathGambar = $request->file('gambar')->store('gambar', 'public');

            $cloudinary = new Cloudinary([
    'cloud' => [
        'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
        'api_key'    => env('CLOUDINARY_API_KEY'),
        'api_secret' => env('CLOUDINARY_API_SECRET'),
    ],
]);

$pathGambar = $cloudinary->uploadApi()->upload(
    $request->file('gambar')->getRealPath()
)['secure_url'];
            }
            else{
                $pathGambar = $request->gambar_lama;
            }
            try {
                $data = Barang::find($request->id);
        $data["nama"] = $request->nama;
        $data["harga"] = $request->harga;
        $data["gambar"] = $pathGambar;
        $data->save();
        return redirect('/')->with('success', 'Data berhasil diubah');
            } catch (\Throwable $e) {
                return redirect()->back()->with('error', 'Gagal mengedit barang: ' . $e->getMessage());
            }
        
        // dd($request);
    }
    public function hapusBarang(Request $request)
    {
        $id = $request->id;           
        $idBarangs=Barang::findOrFail($id);
        Storage::disk('public')->delete($idBarangs->gambar);
        $idBarangs->delete();
        return redirect('/')->with('success', 'Data berhasil dihapus');
    }
    public function search(Request $request)
    {
        $search = $request->search;
        $keranjang = session()->get('keranjang', []);
        $cariHasil = Barang::where('nama', 'like', '%' . $search . '%')->get();
         return view('hasilSearch',[
            'hasil' => $cariHasil,
            'keranjang' => $keranjang
        ]);
    }
    public function tambahKeKeranjang(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:barangs,id',
            'kuantitas' => 'required|integer|min:1'
        ]);
        $barang = Barang::findOrFail($request->id);
        $keranjang = session()->get('keranjang', []);

        // 2. Cek apakah barang sudah ada di keranjang
        if(isset($keranjang[$barang->id])) {
            $keranjang[$barang->id]['kuantitas'] = $request->kuantitas;
        } else {
            // Jika belum ada, tambahkan barang baru
            $keranjang[$barang->id] = [
                'id' => $barang->id,
                'nama' => $barang->nama,
                'harga' => $barang->harga,
                'kuantitas' => $request->kuantitas
            ];
        }
        session()->put('keranjang', $keranjang);

        $totalHarga = 0;
        $totalBanyakBarang = 0;

        foreach ($keranjang as $item) {
            $totalHarga += $item['harga'] * $item['kuantitas'];
            $totalBanyakBarang += $item['kuantitas'];
        }
        return response()->json([
            'success' => 'Barang berhasil ditambahkan ke keranjang!',
            'keranjang' => $keranjang,
            'total_harga' => $totalHarga,
            'total_banyak_barang' => $totalBanyakBarang
        ]);
    }
    public function hapusDariKeranjang(Request $request)
{
    // Validasi input
    $request->validate([
        'id' => 'required|integer|exists:barangs,id',
    ]);

    $keranjang = session()->get('keranjang', []);

    // Periksa apakah barang ada di keranjang, lalu hapus
    if(isset($keranjang[$request->id])) {
        unset($keranjang[$request->id]);
    }

    // Simpan kembali keranjang yang sudah diperbarui ke dalam session
    session()->put('keranjang', $keranjang);

    // Hitung ulang total harga dan total banyak barang
    $totalHarga = 0;
    $totalBanyakBarang = 0;
    foreach ($keranjang as $item) {
        $totalHarga += $item['harga'] * $item['kuantitas'];
        $totalBanyakBarang += $item['kuantitas'];
    }

    // Kirim respons JSON
    return response()->json([
        'success' => 'Barang berhasil dihapus dari keranjang!',
        'keranjang' => $keranjang,
        'total_harga' => $totalHarga,
        'total_banyak_barang' => $totalBanyakBarang
    ]);
}
    public function hapusKeranjang(Request $request)
    {
        session()->forget('keranjang');
        $totalHarga = 0;
    $totalBanyakBarang = 0;
    
    return response()->json([
        'success' => 'Keranjang berhasil dikosongkan!',
        'total_harga' => $totalHarga,
        'total_banyak_barang' => $totalBanyakBarang
    ]);
    }
    public function lihatKeranjang(Request $request)
    {
        $keranjang = session()->get('keranjang', []);
        // dd($keranjang);
        return view('keranjang',[
            'keranjang' => $keranjang
        ]);
    }
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorehomeRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(home $home)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(home $home)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatehomeRequest $request, home $home)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(home $home)
    {
        //
    }
}
