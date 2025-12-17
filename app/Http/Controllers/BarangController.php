<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BarangController extends Controller
{
    /**
     * 🧾 Tampilkan semua barang
     */
    public function index()
    {
        $barang = DB::table('barang')->orderBy('id', 'desc')->get();
        return view('barang.index', compact('barang'));
    }

    /**
     * ➕ Form tambah barang
     */
    public function create()
    {
        return view('barang.create');
    }

    /**
     * 💾 Simpan barang baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:100',
            'kategori'    => 'required|string|max:50', // ✅ Tambah validasi kategori
            'harga'       => 'required|integer',
            'stok'        => 'required|integer',
            'gambar'      => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $gambar = null;
        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('barang'), $filename);
            $gambar = 'barang/' . $filename;
        }

        DB::table('barang')->insert([
            'nama_barang' => $request->nama_barang,
            'kategori'    => $request->kategori, // ✅ Simpan kategori
            'harga'       => $request->harga,
            'stok'        => $request->stok,
            'gambar'      => $gambar,
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);

        return redirect()->route('admin.barang.index')
                         ->with('success', 'Barang berhasil ditambahkan!');
    }

    /**
     * ✏️ Form edit barang
     */
    public function edit($id)
    {
        $barang = DB::table('barang')->where('id', $id)->first();
        return view('barang.edit', compact('barang'));
    }

    /**
     * 🔁 Update barang
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:100',
            'kategori'    => 'required|string|max:50', // ✅ Tambah validasi kategori
            'harga'       => 'required|integer',
            'stok'        => 'required|integer',
            'gambar'      => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $barang = DB::table('barang')->where('id', $id)->first();
        $gambar = $barang->gambar;

        if ($request->hasFile('gambar')) {
            // Hapus gambar lama
            if ($gambar && file_exists(public_path($gambar))) {
                unlink(public_path($gambar));
            }

            $file = $request->file('gambar');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('barang'), $filename);
            $gambar = 'barang/' . $filename;
        }

        // Update data barang
        DB::table('barang')->where('id', $id)->update([
            'nama_barang' => $request->nama_barang,
            'kategori'    => $request->kategori, // ✅ Update kategori
            'harga'       => $request->harga,
            'stok'        => $request->stok,
            'gambar'      => $gambar,
            'updated_at'  => now(),
        ]);

        return redirect()->route('admin.barang.index')
                         ->with('success', 'Barang berhasil diperbarui!');
    }

    /**
     * 🗑️ Hapus barang
     */
    public function destroy($id)
    {
        $barang = DB::table('barang')->where('id', $id)->first();

        if ($barang) {
            if ($barang->gambar && file_exists(public_path($barang->gambar))) {
                unlink(public_path($barang->gambar));
            }
            DB::table('barang')->where('id', $id)->delete();
        }

        return redirect()->route('admin.barang.index')
                         ->with('success', 'Barang berhasil dihapus!');
    }

    /**
     * 🔍 Pencarian barang (live search AJAX)
     */
    public function search(Request $request)
    {
        $query = $request->get('query', '');

        $barang = DB::table('barang')
            ->when($query, function ($q, $query) {
                // ✅ Tambahkan pencarian berdasarkan kategori
                return $q->where('nama_barang', 'like', "%{$query}%")
                         ->orWhere('kategori', 'like', "%{$query}%") 
                         ->orWhere('harga', $query)
                         ->orWhere('stok', $query);
            })
            ->orderBy('nama_barang', 'asc')
            ->get();

        return response()->json($barang);
    }
}