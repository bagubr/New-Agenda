<?php

namespace App\Http\Controllers;

use App\Models\Asal;
use App\Models\DispoKeluar;
use App\Models\Disposisi;
use App\Models\NotulenFile;
use App\Models\RuangRapat;
use App\Models\SuratKeluar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SuratKeluarController extends Controller
{
    public function index(Request $request)
    {
        $data['surat_keluar'] = SuratKeluar::orderBy('no_agenda', 'desc')->get();
        return view('surat-keluar.index', compact('data'));
    }

    public function data(Request $request)
    {
        $draw = $request->get('draw');
        $start = @$request->get("start") ?? 0;
        $rowperpage = @$request->get("length") ?? 0;
        $search_arr = $request->get('search');
        $startDate = @$request->get('startDate');
        $endDate = @$request->get('endDate');

        $searchValue = @$search_arr['value'] ?? '';
        DB::statement('SET @row_number = ' . $start);
        $surat_keluar = SuratKeluar::query();
        $surat_keluar->select(
            DB::raw('@row_number := @row_number + 1 AS row_id'),
            'surat_keluar.*'
        );
        $surat_keluar->when($startDate != '' && $endDate != '', function ($query) use ($startDate, $endDate) {
            $query->whereBetween('tanggal', [$startDate, $endDate]);
        });
        $surat_keluar->when($searchValue != '', function ($query) use ($searchValue) {
            $query->where(function ($query) use ($searchValue) {
                $query->orWhere('surat_keluar.no_agenda', 'like', '%' . $searchValue . '%');
                $query->orWhere('surat_keluar.asal', 'like', '%' . $searchValue . '%');
                $query->orWhereDate('surat_keluar.no_surat', 'like', '%' . $searchValue . '%');
                $query->orWhere('surat_keluar.perihal', 'like', '%' . $searchValue . '%');
                $query->orWhere('surat_keluar.tmpt', 'like', '%' . $searchValue . '%');
                $query->orWhere('surat_keluar.jam', 'like', '%' . $searchValue . '%');
                $query->orWhere('surat_keluar.acara', 'like', '%' . $searchValue . '%');
            });
        });
        if (Auth::user()->role != 'superadmin' && Auth::user()->role != 'kepala_dinas') {
            $surat_keluar->where('asal', Auth::user()->devisi);
        }

        $totalRecords = $surat_keluar->count();

        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecords,
            "aaData" => $surat_keluar->skip($start)
                ->take($rowperpage)
                ->orderBy('no_agenda', 'desc')
                ->get(),
        );
        return $response;
        echo json_encode($response);
    }

    public function create()
    {
        $lastNoAgenda = SuratKeluar::orderBy('no_agenda', 'desc')->first();
        $no_agenda = $lastNoAgenda ? $lastNoAgenda->no_agenda + 1 : 1;
        $disposisi = Disposisi::groupBy('disposisi')->orderBy('id')->get();
        $ruangrapat = RuangRapat::orderBy('id')->get();
        $asal = Asal::orderBy('id')->get();
        $penandatangan = DB::table('surat_keluar')->select('penandatangan')->orderBy('penandatangan')->get()->pluck('penandatangan', 'penandatangan')->unique();
        return view('surat-keluar.create', compact('disposisi', 'no_agenda', 'asal', 'penandatangan', 'ruangrapat'));
    }

    public function edit(SuratKeluar $surat_keluar)
    {
        $disposisi = Disposisi::groupBy('disposisi')->orderBy('id')->get();
        $asal = Asal::orderBy('id')->get();
        return view('surat-keluar.update', compact('disposisi', 'surat_keluar', 'asal'));
    }

    public function post(Request $request)
    {
        $data = $request->validate([
            'jns'           => 'required',
            'perihal'       => 'required',
            'tanggal'       => 'required',
            'tgl_agenda'    => 'sometimes',
            'jam'           => 'sometimes',
            'tmpt'          => 'required',
            'acara'         => 'required',
            'no_surat'      => 'required|unique:surat_keluar,no_surat',
            'idruang'       => 'sometimes|exists:ruangrapat,id',
            'asal'          => 'required',
            'tujuan'        => 'sometimes',
            'publish'       => 'sometimes',
            'penandatangan' => 'sometimes',
            'note'          => 'sometimes'
        ]);
        try {
            DB::beginTransaction();
            $lastNoAgenda = SuratKeluar::orderBy('no_agenda', 'desc')->first();
            $data['no_agenda'] = $lastNoAgenda ? $lastNoAgenda->no_agenda + 1 : 1;
            $data['user'] = Auth::user()->username;
            $data['periode'] = env('APP_PERIODE');
            
            SuratKeluar::create($data);
            if ($request->disposisi) {
                $disposisi = $request->validate([
                    'disposisi'     => 'required|array',
                    'disposisi.*'   => 'required|exists:disposisi,id',
                    'ket'           => 'sometimes|array',
                    'ket.*'         => 'sometimes|string',
                ]);
                foreach ($disposisi['disposisi'] as $key => $value) {
                    $dispokeluar = [
                        'periode'   => env('APP_PERIODE'),
                        'noagenda'  => $data['no_agenda'],
                        'nomor'     => $data['no_surat'],
                        'disposisi' => $value,
                        'role'      => Auth::user()->role,
                        'user'      => Auth::user()->username,
                        'ket'       => $disposisi['ket'][$key]
                    ];
                    DispoKeluar::create($dispokeluar);
                }
            }
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['status' => 'error', 'message' => 'gagal disimpan', 'error' => $th->getMessage()]);
        }
        return response()->json(['status' => 'success', 'message' => 'berhasil disimpan']);
    }

    public function update(Request $request, SuratKeluar $surat_keluar)
    {
        $data = $request->validate([
            'jns'           => 'required',
            'perihal'       => 'required',
            'tanggal'       => 'required',
            'tgl_agenda'    => 'sometimes',
            'jam'           => 'sometimes',
            'tmpt'          => 'required',
            'acara'         => 'required',
            'no_surat'      => 'required',
            'idruang'       => 'sometimes|exists:ruangrapat,id',
            'asal'          => 'required',
            'tujuan'        => 'required',
            'penandatangan' => 'sometimes',
            'publish'       => 'sometimes',
            'note'          => 'sometimes'
        ]);
        if (!isset($data['publish'])) {
            $data['publish'] = '0';
        }
        try {
            DB::beginTransaction();
            $surat_keluar->update($data);
            if ($request->disposisi) {
                $disposisi = $request->validate([
                    'disposisi'     => 'required|array',
                    'disposisi.*'   => 'required|exists:disposisi,id',
                    'ket'           => 'sometimes|array',
                    'ket.*'         => 'sometimes',
                    'id'            => 'sometimes|array',
                    'id.*'          => 'sometimes|string',
                ]);
                foreach ($disposisi['disposisi'] as $key => $value) {
                    $update = [
                        'id'        => @$disposisi['id'][$key],
                        'disposisi' => $value,
                    ];
                    $dispokeluar = [
                        'noagenda'  => $surat_keluar->no_agenda,
                        'periode'   => env('APP_PERIODE'),
                        'nomor'     => $data['no_surat'],
                        'disposisi' => $value,
                        'role'      => Auth::user()->role,
                        'user'      => Auth::user()->username,
                        'ket'       => $disposisi['ket'][$key]
                    ];
                    DispoKeluar::updateOrCreate($update, $dispokeluar);
                }
            }
            DB::commit();
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Gagal Di ubah');
        }
        return redirect()->back()->with('success', 'Berhasil Di ubah');
    }

    public function delete(SuratKeluar $surat_keluar) 
    {
        try {
            if($surat_keluar->dispokeluar()->count() > 0) {
                $surat_keluar->dispokeluar()->delete();
            }
            $surat_keluar->delete();
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Data gagal di hapus');
        }
        return redirect()->back()->with('success', 'Data berhasil di hapus');
    }
}
