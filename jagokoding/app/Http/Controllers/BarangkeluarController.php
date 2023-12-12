<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barangkeluar;
use App\Models\Barang;

class BarangkeluarController extends Controller
{
    public function index()
    {
        return view('vbarangkeluar.index', compact('barangkeluar'));
    }

    public function create()
    {
        $merkBarang = Barang::pluck('merk', 'id');
        return view('vbarangkeluar.create', compact('merkBarang'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'tgl_keluar' => 'required',
            'qty_keluar' => 'required|numeric|min:0',
            'barang_id' => 'required',
        ]);

        $barangKeluar = new Barangkeluar();
        $barangKeluar->tgl_keluar = $request->tgl_keluar;
        $barangKeluar->qty_keluar = $request->qty_keluar;
        $barangKeluar->barang_id = $request->barang_id;
        $barangKeluar->save();

        $barang = Barang::find($request->barang_id);
        $barang->stok -= $request->qty_keluar;

        return redirect()->route('barangkeluar.index')->with('success', 'Barang keluar berhasil ditambahkan');
    }

    public function show($id)
    {
        $barangkeluar = Barangkeluar::findOrFail($id);
        return view('vbarangkeluar.show', compact('barangkeluar'));
    }

    public function edit($id)
    {
        $barangkeluar = Barangkeluar::findOrFail($id);
        $merkBarang = Barang::pluck('merk', 'id');

        return view('vbarangkeluar.edit', compact('barangkeluar', 'merkBarang'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'tgl_keluar' => 'required',
            'qty_keluar' => 'required|numeric|min:0',
        ]);

        $barangkeluar = Barangkeluar::findOrFail($id);
        $barangkeluar->tgl_keluar = $request->tgl_keluar;
        $barangkeluar->qty_keluar = $request->qty_keluar;
        $barangkeluar->save();

        $barang = Barang::find($request->barang_id);
        $barang->stok += abs($request->qty_keluar - $barangkeluar->qty_keluar);
        $barang->save();

        return redirect()->route('barangkeluar.index')->with('success', 'Barang keluar berhasil diperbarui');
    }

    public function destroy($id)
    {
        $barangkeluar = Barangkeluar::findOrFail($id);
        $barangkeluar->delete();

        return redirect()->route('barangkeluar.index')->with('success', 'Barang keluar berhasil dihapus');
    }
}
