<?php

namespace App\Http\Controllers;

use App\Models\RuangRapat;
use Illuminate\Http\Request;

class RuangRapatController extends Controller
{
    
    public function index(Request $request)
    {
        $ruang_rapat = RuangRapat::orderBy('id')->get();
        return view('ruang-rapat.index', compact('ruang_rapat'));
    }  

    public function create()
    {
        return view('ruang-rapat.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'ruangrapat' => 'required',
        ]);

        RuangRapat::create([
            'ruangrapat' => $request->ruangrapat,
        ]);

        return redirect()->route('ruang-rapat')->with('success', 'Ruang rapat berhasil ditambahkan');
    }

    public function edit($id)
    {
        $ruang_rapat = RuangRapat::findOrFail($id);
        return view('ruang-rapat.edit', compact('ruang_rapat'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'ruangrapat' => 'required',
        ]);

        $ruang_rapat = RuangRapat::findOrFail($id);
        $ruang_rapat->update([
            'ruangrapat' => $request->ruangrapat,
        ]);

        return redirect()->route('ruang-rapat')->with('success', 'Ruang rapat berhasil diupdate');
    }

    public function destroy($id)
    {
        $ruang_rapat = RuangRapat::findOrFail($id);
        $ruang_rapat->delete();

        return redirect()->route('ruang-rapat')->with('success', 'Ruang rapat berhasil dihapus');
    }
}
