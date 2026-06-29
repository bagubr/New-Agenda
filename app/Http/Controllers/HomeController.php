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

        $data['surat_keluar'] = SuratKeluar::whereDate('time', date('Y-m-d'))->orderBy('no_agenda', 'desc')->get();
        $data['surat_masuk'] = SuratMasuk::whereDate('time', date('Y-m-d'))->orderBy('no_agenda', 'desc')->get();
        // $query = DB::table('surat_masuk')
        $query = SuratMasuk::query();
        $query->join('dispo_masuk', 'surat_masuk.no_agenda', '=', 'dispo_masuk.noagenda')
            ->join('disposisi', 'disposisi.id', '=', 'dispo_masuk.disposisi');
        $query->where('jns', 1);
        if(Auth::user()->role != 'superadmin'){
            if(Auth::user()->role != 'admin'){
                $query->where('disposisi.role', Auth::user()->role);
            }
            $query->where('devisi', Auth::user()->devisi);
        }
        $query->where(function ($query) {
            $query->whereNull('tindak')
            ->orWhere('tindak', '');
        })->where(function ($query) {
            $query->whereNull('ket')
            ->orWhere('ket', '');
        });
        $data['surat_belum_disposisi'] = $query->orderBy('no_agenda', 'desc')->get();
        $data['surat_selesai'] = $query->whereDate('tgl_agenda', '<', date('Y-m-d'))->orderBy('no_agenda', 'desc')->get();


        $data['grafik_surat_masuk'] = DB::table('surat_masuk')->select(DB::raw('DATE_FORMAT(tgl_agenda, "%Y-%m") as month, COUNT(*) as count'))->whereYear('tgl_agenda', date('Y'))->where('jns', 1)->groupBy('month')->orderBy('month', 'asc')->get()->pluck('count')->toArray();
        $data['grafik_surat_keluar'] = DB::table('surat_keluar')->select(DB::raw('DATE_FORMAT(tgl_agenda, "%Y-%m") as month, COUNT(*) as count'))->whereYear('tgl_agenda', date('Y'))->where('jns', 1)->groupBy('month')->orderBy('month', 'asc')->get()->pluck('count')->toArray();
        return view('welcome', compact('data'));
    }

    public function data_dashboard(Request $request)
    {

        $draw = $request->get('draw');
        $start = @$request->get("start") ?? 0;
        $rowperpage = @$request->get("length") ?? 10;
        $search_arr = $request->get('search');
        $startDate = @$request->get('startDate');
        $endDate = @$request->get('endDate');

        $searchValue = @$search_arr['value'] ?? '';
        DB::statement('SET @row_number = ' . $start);
        $surat_masuk = SuratMasuk::query();
        $surat_masuk->join('dispo_masuk', 'surat_masuk.no_agenda', '=', 'dispo_masuk.noagenda')
            ->join('disposisi', 'disposisi.id', '=', 'dispo_masuk.disposisi');
        if (Auth::user()->role != 'superadmin') {
                if (Auth::user()->role != 'admin') {
                    $surat_masuk->where('disposisi.role', Auth::user()->role);
                }
                $surat_masuk->where('devisi', Auth::user()->devisi);
        }
        $surat_masuk->where('jns', 1);
        $surat_masuk->select(
            DB::raw('@row_number := @row_number + 1 AS row_id'),
            'surat_masuk.id as id',
            'surat_masuk.no_agenda',
            'surat_masuk.tanggal',
            'surat_masuk.no_surat',
            'surat_masuk.asal',
            'surat_masuk.perihal',
            'surat_masuk.perihal',
            'surat_masuk.penerima',
            'surat_masuk.time',
            'surat_masuk.user',
            'surat_masuk.periode',
            'surat_masuk.jns',
            'surat_masuk.tgl_agenda',
            'surat_masuk.tmpt',
            'surat_masuk.jam',
            'surat_masuk.acara',
            'surat_masuk.note',
            'disposisi.role',
            'disposisi.devisi',
        );
        $surat_masuk->when($searchValue != '', function ($query) use ($searchValue) {
            $query->where(function ($query) use ($searchValue) {
                $query->orWhere('surat_masuk.no_agenda', 'like', '%' . $searchValue . '%');
                $query->orWhere('surat_masuk.asal', 'like', '%' . $searchValue . '%');
                $query->orWhereDate('surat_masuk.no_surat', 'like', '%' . $searchValue . '%');
                $query->orWhere('surat_masuk.perihal', 'like', '%' . $searchValue . '%');
                $query->orWhere('surat_masuk.tmpt', 'like', '%' . $searchValue . '%');
                $query->orWhere('surat_masuk.jam', 'like', '%' . $searchValue . '%');
                $query->orWhere('surat_masuk.acara', 'like', '%' . $searchValue . '%');
            });
        });

        $totalRecords = $surat_masuk->whereDate('surat_masuk.time', date('Y-m-d'))->count();

        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecords,
            "aaData" => $surat_masuk->whereDate('surat_masuk.time', date('Y-m-d'))->skip($start)
                ->take($rowperpage)
                ->orderBy('row_id')
                ->groupBy('id', 'no_agenda')
                ->get(),
        );
        echo json_encode($response);
    }
}
