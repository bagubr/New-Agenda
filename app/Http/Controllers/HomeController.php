<?php

namespace App\Http\Controllers;

use App\Models\SuratKeluar;
use App\Models\SuratMasuk;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function dashboard()
    {
        $query = SuratMasuk::query();
        $query->join('dispo_masuk', 'surat_masuk.no_agenda', '=', 'dispo_masuk.no_agenda')
            ->join('disposisi', 'disposisi.id', '=', 'dispo_masuk.disposisi');
        if (@Auth::user()->role != 'superadmin') {
            if (@Auth::user()->role != 'admin') {
                $query->where('disposisi.role', @Auth::user()->role);
            }
            $query->where('devisi', @Auth::user()->devisi);
        }
        $data['total_surat'] = $query->groupBy('surat_masuk.id')->get();
        $data['surat_masuk'] = $query->whereDate('surat_masuk.time', date('Y-m-d'))->orderBy('surat_masuk.id', 'desc')->groupBy('surat_masuk.id')->get();

        $data['surat_terlewat'] = SuratMasuk::join('dispo_masuk', 'surat_masuk.no_agenda', '=', 'dispo_masuk.no_agenda')
            ->join('disposisi', 'disposisi.id', '=', 'dispo_masuk.disposisi')
            ->when(@Auth::user()->role != 'superadmin', function ($terlewat) {
                if (@Auth::user()->role != 'admin') {
                    $terlewat->where('disposisi.role', @Auth::user()->role);
                }
                $terlewat->where('devisi', @Auth::user()->devisi);
            })->where(function ($terlewat) {
                $terlewat->whereNull('tindak')
                    ->orWhere('tindak', '');
            })->where(function ($terlewat) {
                $terlewat->whereNull('ket')
                    ->orWhere('ket', '');
            })
            ->whereDate('tgl_agenda', '<', date('Y-m-d'))
            ->orderBy('surat_masuk.id', 'desc')
            ->groupBy('surat_masuk.id')
            ->get();

        $data['surat_belum_disposisi'] = SuratMasuk::join('dispo_masuk', 'surat_masuk.no_agenda', '=', 'dispo_masuk.no_agenda')
            ->join('disposisi', 'disposisi.id', '=', 'dispo_masuk.disposisi')
            ->when(@Auth::user()->role != 'superadmin', function ($belum_disposisi) {
                if (@Auth::user()->role != 'admin') {
                    $belum_disposisi->where('disposisi.role', @Auth::user()->role);
                }
                $belum_disposisi->where('devisi', @Auth::user()->devisi);
            })->where(function ($terlewat) {
                $terlewat->whereNull('tindak')
                    ->orWhere('tindak', '');
            })->where(function ($terlewat) {
                $terlewat->whereNull('ket')
                    ->orWhere('ket', '');
            })
            ->whereDate('tgl_agenda', '>', date('Y-m-d'))
            ->orderBy('surat_masuk.id', 'desc')
            ->groupBy('surat_masuk.id')
            ->get();
            
        $data['grafik_surat_masuk'] = DB::table('surat_masuk')->select(DB::raw('DATE_FORMAT(tgl_agenda, "%Y-%m") as month, COUNT(*) as count'))->where('jns', 1)->whereYear('tgl_agenda', date('Y'))->groupBy('month')->orderBy('month', 'asc')->get()->pluck('count')->toArray();
        $data['grafik_surat_masuk_non'] = DB::table('surat_masuk')->select(DB::raw('DATE_FORMAT(tgl_agenda, "%Y-%m") as month, COUNT(*) as count'))->where('jns', 2)->whereYear('tgl_agenda', date('Y'))->groupBy('month')->orderBy('month', 'asc')->get()->pluck('count')->toArray();
        $data['grafik_surat_masuk_usulan'] = DB::table('surat_masuk')->select(DB::raw('DATE_FORMAT(tgl_agenda, "%Y-%m") as month, COUNT(*) as count'))->where('jns', 3)->whereYear('tgl_agenda', date('Y'))->groupBy('month')->orderBy('month', 'asc')->get()->pluck('count')->toArray();
        return view('welcome', compact('data'));
    }
}
