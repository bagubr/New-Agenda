<?php

namespace App\Http\Controllers;

use App\Models\DispoKeluar;
use Illuminate\Http\Request;

class DispoKeluarController extends Controller
{
    public function delete(DispoKeluar $dispo_keluar) 
    {
        try {
            $dispo_keluar->delete();
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Data gagal di hapus');
        }
        return redirect()->back()->with('success', 'Data berhasil di hapus');
    }
}
