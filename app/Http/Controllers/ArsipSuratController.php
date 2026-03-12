<?php

namespace App\Http\Controllers;

use App\Models\ArsipSurat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ArsipSuratController extends Controller
{
    public function index() {
        $arsip_surat = \App\Models\ArsipSurat::orderBy('id', 'desc')->get();
        return view('arsip-surat.index', compact('arsip_surat'));
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
        $arsip_surat = ArsipSurat::query();
        $arsip_surat->select(
            DB::raw('@row_number := @row_number + 1 AS row_id'),
            'arsip_surat.*'
        );
        $arsip_surat->when($startDate != '' && $endDate != '', function ($query) use ($startDate, $endDate) {
            $query->whereBetween('tgl_agenda', [$startDate, $endDate]);
        });
        $arsip_surat->when($searchValue != '', function ($query) use ($searchValue) {
            $query->where(function ($query) use ($searchValue) {
                $query->orWhere('arsip_surat.no_agenda', 'like', '%' . $searchValue . '%');
                $query->orWhere('arsip_surat.asal_surat', 'like', '%' . $searchValue . '%');
                $query->orWhereDate('arsip_surat.no_surat', 'like', '%' . $searchValue . '%');
                $query->orWhere('arsip_surat.perihal', 'like', '%' . $searchValue . '%');
                $query->orWhere('arsip_surat.keterangan', 'like', '%' . $searchValue . '%');
            });
        });

        $totalRecords = $arsip_surat->count();

        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecords,
            "aaData" => $arsip_surat->skip($start)
                ->take($rowperpage)
                ->orderBy('no_agenda', 'desc')
                ->get(),
        );
        return $response;
        echo json_encode($response);
    }
}
