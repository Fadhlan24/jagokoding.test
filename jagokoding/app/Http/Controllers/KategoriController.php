<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Kategori;
use App\Models\Barang;

class KategoriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $rsetKategori = Kategori::select('id','deskripsi','kategori',
            \DB::raw('(CASE
                WHEN kategori = "M" THEN "Modal"
                WHEN kategori = "A" THEN "Alat"
                WHEN kategori = "BHP" THEN "Bahan Habis Pakai"
                ELSE "Bahan Tidak Habis Pakai"
                END) AS ketKategori'))
            ->paginate(10);
        return view('v_kategori.index', compact('rsetKategori'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $aKategori = array('blank'=>'Pilih Kategori',
                            'M'=>'Barang Modal',
                            'A'=>'Alat',
                            'BHP'=>'Bahan Habis Pakai',
                            'BTHP'=>'Bahan Tidak Habis Pakai'
                            );
        return view('v_kategori.create',compact('aKategori'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'deskripsi'   => 'required',
            'kategori'     => 'required | in:M,A,BHP,BTHP',
        ]);

        Kategori::create([
            'deskripsi'  => $request->deskripsi,
        ]);

        return redirect()->route('kategori.index')->with(['success' => 'Data Berhasil Disimpan!']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $rsetKategori = Kategori::findOrFail($id);
        return view('v_kategori.show', compact('rsetKategori'));
    }



    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $rsetKategori = Kategori::find($id);
        return view('v_kategori.edit', compact('rsetKategori'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->validate($request, [
            'deskripsi' => 'required',
            'kategori' => 'required|in:M,A,BHP,BTHP',
        ]);

        $rsetKategori = Kategori::findOrFail($id);
        $rsetKategori->deskripsi = $request->deskripsi;
        $rsetKategori->kategori = $request->kategori;
        $rsetKategori->save();

        return redirect()->route('kategori.index')->with(['success' => 'Data Berhasil Diupdate!']);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)

    {
        if (DB::table('barang')->where('kategori_id', $id)->exists()){
            return redirect()->route('kategori.index')->with(['Gagal' => 'Data Gagal Dihapus!']);

        } else {

            $rsetKategori = Kategori::find($id);
            $rsetKategori->delete();

            return redirect()->route('kategori.index')->with(['success' => 'Data Berhasil Dihapus!']);

        }

    }

}
