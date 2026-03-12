<?php

namespace App\Http\Controllers;

use App\Models\Asal;
use Illuminate\Http\Request;

class AsalController extends Controller
{

    public function index()
    {
        $asal = Asal::all();
        return view('asal.index', compact('asal'));
    }

    public function create()
    {
        return view('asal.create');
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'kode' => 'required|string|max:255',
        ]);

        Asal::create([
            'name' => $request->name,
            'kode' => $request->kode,
        ]);

        return redirect()->back()->with('success', 'Asal surat berhasil ditambahkan.');
    }   

    public function edit($id)
    {
        $asal = Asal::findOrFail($id);
        return view('asal.edit', compact('asal'));
    }

    public function update(Request $request, $id)
    {
        $asal = Asal::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'kode' => 'required|string|max:255',
        ]);

        $asal->update([
            'name' => $request->name,
            'kode' => $request->kode,
        ]);

        return redirect()->back()->with('success', 'Asal surat berhasil diperbarui.');
    }   
    
    public function destroy($id)
    {
        $asal = Asal::findOrFail($id);
        $asal->delete();

        return redirect()->back()->with('success', 'Asal surat berhasil dihapus.');
    }
}
