<?php

namespace App\Http\Controllers;

use App\Models\DispoMasuk;
use Illuminate\Http\Request;

class DispoMasukController extends Controller
{
    public function delete(DispoMasuk $dispo_masuk) 
    {
        try {
            $dispo_masuk->delete();
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Data gagal di hapus');
        }
        return redirect()->back()->with('success', 'Data berhasil di hapus');
    }
}
