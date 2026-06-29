<?php

namespace App\Http\Controllers;

use App\Models\DispoMasuk;
use App\Models\Disposisi;
use App\Models\Pegawai;
use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

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

    public function cetak(DispoMasuk $dispo_masuk)
    {
        $data = [
            'dispo_masuk' => $dispo_masuk,
            'logo' => base64_encode(file_get_contents(public_path('logodispo.png')))
        ];
        $pdf = Pdf::loadView('disposisi.cetak', $data);
        return $pdf->stream('disposisi-masuk-' . $dispo_masuk->id . '.pdf');
    }

    public function pilihUser(Request $request, DispoMasuk $dispo_masuk)
    {
        $users = User::where('role', 'user')->get();
    }

    public function pilihUserData($dispo_masuk)
    {
        $disposisi = Disposisi::find($dispo_masuk);
        $search = request()->input('search');
        if(Auth::user()->role == 'kepala_dinas') {
            $users = Pegawai::where('nama', 'like', '%' . $search . '%')->get();
        } else {
            $users = Pegawai::where('devisi', $disposisi->devisi)->where('nama', 'like', '%' . $search . '%')->get();
        }

        return response()->json(['data' => $users]);
        // $users = Pegawai::where('devisi', $dispo_masuk->dispo->devisi)->get();
    }
}
