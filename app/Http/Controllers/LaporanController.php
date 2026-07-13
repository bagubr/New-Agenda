<?php

namespace App\Http\Controllers;

use App\Exports\SuratMasukExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class LaporanController extends Controller
{
    public function index(Request $request) 
    {
        return view('laporan.index');
    }

    public function export(Request $request) 
    {
        $suratMasuk = new SuratMasukController();
        $suratMasuk = $suratMasuk->data($request);
        return Excel::download(new SuratMasukExport($suratMasuk['aaData']), 'surat_masuk.xlsx');
    }

}
