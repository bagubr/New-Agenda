<?php

namespace App\Http\Controllers;

use App\Models\SuratKeluar;
use App\Models\SuratMasuk;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function dashboard() {
        $data['surat_masuk'] = SuratMasuk::whereDate('tgl_agenda', date('Y-m-d'))->where('jns', 1)->orderBy('no_agenda', 'desc')->get();
        $data['surat_keluar'] = SuratKeluar::whereDate('tgl_agenda', date('Y-m-d'))->where('jns', 1)->get();
        $data['surat_belum_disposisi'] = SuratMasuk::whereDate('tgl_agenda', '>', date('Y-m-d'))->where('jns', 1)->orderBy('no_agenda', 'desc')->get();
        $data['surat_selesai'] = SuratMasuk::whereDate('tgl_agenda', date('Y-m-d'))->where('jns', 1)->orderBy('no_agenda', 'desc')->get();
        $data['grafik_surat_masuk'] = DB::table('surat_masuk')->select(DB::raw('DATE_FORMAT(tgl_agenda, "%Y-%m") as month, COUNT(*) as count'))->whereYear('tgl_agenda', date('Y'))->where('jns', 1)->groupBy('month')->orderBy('month', 'asc')->get()->pluck('count');
        $data['grafik_surat_keluar'] = DB::table('surat_keluar')->select(DB::raw('DATE_FORMAT(tgl_agenda, "%Y-%m") as month, COUNT(*) as count'))->whereYear('tgl_agenda', date('Y'))->where('jns', 1)->groupBy('month')->orderBy('month', 'asc')->get()->pluck('count');
        return view('welcome', compact('data'));
    }
}
