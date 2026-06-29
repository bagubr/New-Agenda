<?php

namespace App\Http\Controllers;

use App\Models\Disposisi;
use Illuminate\Http\Request;

class DisposisiController extends Controller
{
    public function index()
    {
        $disposisis = Disposisi::orderBy('id', 'asc')->get();
        return view('disposisi.index', compact('disposisis'));
    }

    public function create()
    {
        return view('disposisi.create');
    }

    public function store(Request $request)
    {
        // Validasi dan simpan data disposisi
        $data = $request->validate([
            'disposisi' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'aktif' => 'required|boolean',
        ]);
        Disposisi::create($data);
        return redirect()->route('disposisi.index')->with('success', 'Disposisi berhasil disimpan.');
    }

    public function edit($id)
    {
        // Ambil data disposisi berdasarkan ID dan tampilkan form edit
        $disposisi = Disposisi::findOrFail($id);
        return view('disposisi.edit', compact('disposisi'));
    }

    public function update(Request $request, $id)
    {
        // Validasi dan update data disposisi berdasarkan ID
        $data = $request->validate([
            'disposisi' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'aktif' => 'required|boolean',
        ]);
        $disposisi = Disposisi::findOrFail($id);
        $disposisi->update($data);
        return redirect()->route('disposisi.index')->with('success', 'Disposisi berhasil diperbarui.');
    }

    public function destroy($id)
    {
        // Hapus data disposisi berdasarkan ID
        $disposisi = Disposisi::findOrFail($id);
        $disposisi->delete();
        return redirect()->route('disposisi.index')->with('success', 'Disposisi berhasil dihapus.');
    }
}
