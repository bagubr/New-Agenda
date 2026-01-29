<?php

namespace App\Http\Controllers;

use App\Models\DispoKeluar;
use App\Models\Disposisi;
use App\Models\NotulenFile;
use App\Models\NotulenKeluar;
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

        $searchValue = @$search_arr['value'] ?? '';
        DB::statement('SET @row_number = ' . $start);
        $surat_keluar = SuratKeluar::query();
        $surat_keluar->select(
            DB::raw('@row_number := @row_number + 1 AS row_id'),
            'surat_keluar.*'
        );
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
        $asal = Disposisi::groupBy('disposisi')->orderBy('id')->get()->pluck('disposisi', 'disposisi')->unique();
        $penandatangan = DB::table('surat_keluar')->select('penandatangan')->orderBy('penandatangan')->get()->pluck('penandatangan', 'penandatangan')->unique();
        return view('surat-keluar.create', compact('disposisi', 'no_agenda', 'asal', 'penandatangan'));
    }

    public function edit(SuratKeluar $surat_keluar)
    {
        $disposisi = Disposisi::groupBy('disposisi')->orderBy('id')->get();
        return view('surat-keluar.update', compact('disposisi', 'surat_keluar'));
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
            'asal'          => 'required',
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

    public function notulen(Request $request, $no_agenda)
    {
        try {
            $data = $request->validate([
                'notulen'       => 'required|string',
                'file_dokument' => 'required|file|mimes:pdf,doc,docx|max:2048',
                'files'          => 'sometimes|array',
                'files.*'        => 'sometimes|file|mimes:jpg,jpeg,png|max:2048',
            ]);
            $filename = 'notulen_' . $no_agenda . '_' . time() . '.' . $request->file('file_dokument')->getClientOriginalExtension();
            $request->file('file_dokument')->storeAs('notulen_keluar', $filename, 'public');
            $original_name = $request->file('file_dokument')->getClientOriginalName();
            $surat_keluar = SuratKeluar::where('no_agenda', $no_agenda)->first();
            $notulen_keluar = NotulenKeluar::create([
                'periode'       => env('APP_PERIODE'),
                'noagenda'      => $no_agenda,
                'filename'      => $filename,
                'original_name' => $original_name,
                'note'          => $request->notulen,
                'user'          => Auth::user()->username,
            ]);
            foreach ($data['files'] ?? [] as $key => $value) {
                $file = 'notulen_file_' . $no_agenda . '_' . time() . '_' . $key . '.' . $value->getClientOriginalExtension();
                $value->storeAs('notulen_files', $file, 'public');
                NotulenFile::create([
                    'notulen_id'        => $notulen_keluar->id,
                    'file'              => $file,
                    'jenis'             => 'OUT',
                    'original_name'     => $value->getClientOriginalName(),
                ]);
            }
            
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'message' => 'Notulen gagal diambil', 'error' => $th->getMessage()]);
        }
        return response()->json(['status' => 'success', 'message' => 'Notulen berhasil diambil', 'no_agenda' => $no_agenda]);
        
    }

    public function notulenData($no_agenda)
    {
        try {
            $notulen_keluar = NotulenKeluar::where('noagenda', $no_agenda)->first();
            
            if(!$notulen_keluar){
                return response()->json(['status' => 'error', 'message' => 'Data tidak ditemukan']);
            }

            $notulen_keluar->files = NotulenFile::whereJenis('OUT')->where('notulen_id', $notulen_keluar->id)->get();
            
            return response()->json(['status' => 'success', 'notulen' => $notulen_keluar]);
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'message' => 'Gagal mengambil data', 'error' => $th->getMessage()]);
        }
    }

    public function notulenUpdate(Request $request, $id)
    {
        try {
            $notulen_keluar = NotulenKeluar::find($id);
            
            if(!$notulen_keluar){
                return response()->json(['status' => 'error', 'message' => 'Data tidak ditemukan']);
            }

            $data = $request->validate([
                'notulen'       => 'required|string',
                'file_dokument' => 'sometimes|file|mimes:pdf,doc,docx|max:2048',
                'files'         => 'sometimes|array',
                'files.*'       => 'sometimes|file|mimes:jpg,jpeg,png|max:2048',
            ]);

            // Update note
            $notulen_keluar->note = $data['notulen'];

            // Update file_dokument if provided
            if($request->hasFile('file_dokument')){
                // Delete old file if exists
                if($notulen_keluar->filename && Storage::disk('public')->exists('notulen_keluar/' . $notulen_keluar->filename)){
                    Storage::disk('public')->delete('notulen_keluar/' . $notulen_keluar->filename);
                }
                
                $filename = 'notulen_' . $notulen_keluar->noagenda . '_' . time() . '.' . $request->file('file_dokument')->getClientOriginalExtension();
                $request->file('file_dokument')->storeAs('notulen_keluar', $filename, 'public');
                $notulen_keluar->filename = $filename;
                $notulen_keluar->original_name = $request->file('file_dokument')->getClientOriginalName();
            }

            $notulen_keluar->save();

            // Add new files if provided
            if(!empty($data['files'])){
                foreach ($data['files'] as $key => $value) {
                    $file = 'notulen_file_' . $notulen_keluar->noagenda . '_' . time() . '_' . $key . '.' . $value->getClientOriginalExtension();
                    $value->storeAs('notulen_files', $file, 'public');
                    NotulenFile::create([
                        'notulen_id' => $notulen_keluar->id,
                        'file'              => $file,
                        'jenis'             => 'OUT',
                        'original_name'     => $value->getClientOriginalName(),
                    ]);
                }
            }

            return response()->json(['status' => 'success', 'message' => 'Notulen berhasil diperbarui']);
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'message' => 'Notulen gagal diperbarui', 'error' => $th->getMessage()]);
        }
    }

    public function notulenFileDelete($id)
    {
        try {
            $notulen_file = NotulenFile::find($id);
            
            if(!$notulen_file){
                return response()->json(['status' => 'error', 'message' => 'File tidak ditemukan']);
            }

            // Delete file from storage
            if($notulen_file->file && Storage::disk('public')->exists('notulen_files/' . $notulen_file->file)){
                Storage::disk('public')->delete('notulen_files/' . $notulen_file->file);
            }

            // Delete record from database
            $notulen_file->delete();

            return response()->json(['status' => 'success', 'message' => 'File berhasil dihapus']);
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'message' => 'File gagal dihapus', 'error' => $th->getMessage()]);
        }
    }
}
