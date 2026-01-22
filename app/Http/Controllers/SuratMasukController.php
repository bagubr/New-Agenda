<?php

namespace App\Http\Controllers;

use App\Models\DispoMasuk;
use App\Models\Disposisi;
use App\Models\NotulenFile;
use App\Models\NotulenMasuk;
use App\Models\SuratMasuk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SuratMasukController extends Controller
{
    public function index(Request $request)
    {
        $data['surat_masuk'] = SuratMasuk::orderBy('no_agenda', 'desc')->get();
        return view('surat-masuk.index', compact('data'));
    }

    public function data(Request $request)
    {
        $draw = $request->get('draw');
        $start = @$request->get("start") ?? 0;
        $rowperpage = @$request->get("length") ?? 0;
        $search_arr = $request->get('search');

        $searchValue = @$search_arr['value'] ?? '';
        DB::statement('SET @row_number = ' . $start);
        $surat_masuk = SuratMasuk::query();
        $surat_masuk->select(
            DB::raw('@row_number := @row_number + 1 AS row_id'),
            'surat_masuk.*'
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

        $totalRecords = $surat_masuk->count();

        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecords,
            "aaData" => $surat_masuk->skip($start)
                ->take($rowperpage)
                ->orderBy('no_agenda', 'desc')
                ->get(),
        );
        return $response;
        echo json_encode($response);
    }

    public function create()
    {
        $no_agenda = SuratMasuk::orderBy('no_agenda', 'desc')->first()->no_agenda + 1;
        $disposisi = Disposisi::groupBy('disposisi')->orderBy('id')->get();
        $asal = DB::table('surat_masuk')->select('asal')->orderBy('no_agenda', 'desc')->get()->pluck('asal', 'asal')->unique();
        return view('surat-masuk.create', compact('disposisi', 'no_agenda', 'asal'));
    }

    public function edit(SuratMasuk $surat_masuk)
    {
        $disposisi = Disposisi::groupBy('disposisi')->orderBy('id')->get();
        return view('surat-masuk.update', compact('disposisi', 'surat_masuk'));
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
            'no_surat'      => 'required|unique:surat_masuk,no_surat',
            'asal'          => 'required',
            'penerima'      => 'required',
            'publish'       => 'sometimes',
            'note'          => 'sometimes'
        ]);
        try {
            DB::beginTransaction();
            $data['no_agenda'] = SuratMasuk::orderBy('no_agenda', 'desc')->first()->no_agenda + 1;
            $data['f_umum'] = 1;
            $data['user'] = Auth::user()->username;
            $data['periode'] = env('APP_PERIODE');

            SuratMasuk::create($data);
            if ($request->disposisi) {
                $disposisi = $request->validate([
                    'disposisi'     => 'required|array',
                    'disposisi.*'   => 'required|exists:disposisi,id',
                    'ket'           => 'sometimes|array',
                    'ket.*'         => 'sometimes|string',
                ]);
                foreach ($disposisi['disposisi'] as $key => $value) {
                    $dispomasuk = [
                        'periode'   => env('APP_PERIODE'),
                        'noagenda'  => $data['no_agenda'],
                        'nomor'     => $data['no_surat'],
                        'disposisi' => $value,
                        'role'      => Auth::user()->role,
                        'user'      => Auth::user()->username,
                        'ket'       => $disposisi['ket'][$key]
                    ];
                    DispoMasuk::create($dispomasuk);
                }
            }
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['status' => 'error', 'message' => 'gagal disimpan', 'error' => $th->getMessage()]);
        }
        return response()->json(['status' => 'success', 'message' => 'berhasil disimpan']);
    }

    public function update(Request $request, SuratMasuk $surat_masuk)
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
            'penerima'      => 'required',
            'publish'       => 'sometimes',
            'note'          => 'sometimes'
        ]);
        if (!isset($data['publish'])) {
            $data['publish'] = '0';
        }
        try {
            DB::beginTransaction();
            $surat_masuk->update($data);
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
                    $dispomasuk = [
                        'noagenda'  => $surat_masuk->no_agenda,
                        'periode'   => env('APP_PERIODE'),
                        'nomor'     => $data['no_surat'],
                        'disposisi' => $value,
                        'role'      => Auth::user()->role,
                        'user'      => Auth::user()->username,
                        'ket'       => $disposisi['ket'][$key]
                    ];
                    DispoMasuk::updateOrCreate($update, $dispomasuk);
                }
            }
            DB::commit();
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Gagal Di ubah');
        }
        return redirect()->back()->with('success', 'Berhasil Di ubah');
    }

    public function delete(SuratMasuk $surat_masuk) 
    {
        try {
            if($surat_masuk->dispomasuk()->count() > 0) {
                $surat_masuk->dispomasuk()->delete();
            }
            $surat_masuk->delete();
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
            $request->file('file_dokument')->storeAs('notulen_masuk', $filename, 'public');
            $original_name = $request->file('file_dokument')->getClientOriginalName();
            $surat_masuk = SuratMasuk::where('no_agenda', $no_agenda)->first();
            $notulen_masuk = NotulenMasuk::create([
                'periode'       => env('APP_PERIODE'),
                'noagenda'      => $no_agenda,
                'filename'      => $filename,
                'original_name' => $original_name,
                'note'          => $request->notulen,
                'user'          => Auth::user()->username,
            ]);
            foreach ($data['files'] as $key => $value) {
                $file = 'notulen_file_' . $no_agenda . '_' . time() . '_' . $key . '.' . $value->getClientOriginalExtension();
                $value->storeAs('notulen_files', $file, 'public');
                NotulenFile::create([
                    'notulen_masuk_id' => $notulen_masuk->id,
                    'file'             => $file,
                    'original_name'    => $original_name,
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
            $notulen_masuk = NotulenMasuk::where('noagenda', $no_agenda)->first();
            
            if(!$notulen_masuk){
                return response()->json(['status' => 'error', 'message' => 'Data tidak ditemukan']);
            }

            $notulen_masuk->files = NotulenFile::where('notulen_masuk_id', $notulen_masuk->id)->get();
            
            return response()->json(['status' => 'success', 'notulen' => $notulen_masuk]);
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'message' => 'Gagal mengambil data', 'error' => $th->getMessage()]);
        }
    }

    public function notulenUpdate(Request $request, $id)
    {
        try {
            $notulen_masuk = NotulenMasuk::find($id);
            
            if(!$notulen_masuk){
                return response()->json(['status' => 'error', 'message' => 'Data tidak ditemukan']);
            }

            $data = $request->validate([
                'notulen'       => 'required|string',
                'file_dokument' => 'sometimes|file|mimes:pdf,doc,docx|max:2048',
                'files'         => 'sometimes|array',
                'files.*'       => 'sometimes|file|mimes:jpg,jpeg,png|max:2048',
            ]);

            // Update note
            $notulen_masuk->note = $data['notulen'];

            // Update file_dokument if provided
            if($request->hasFile('file_dokument')){
                // Delete old file if exists
                if($notulen_masuk->filename && Storage::disk('public')->exists('notulen_masuk/' . $notulen_masuk->filename)){
                    Storage::disk('public')->delete('notulen_masuk/' . $notulen_masuk->filename);
                }
                
                $filename = 'notulen_' . $notulen_masuk->noagenda . '_' . time() . '.' . $request->file('file_dokument')->getClientOriginalExtension();
                $request->file('file_dokument')->storeAs('notulen_masuk', $filename, 'public');
                $notulen_masuk->filename = $filename;
                $notulen_masuk->original_name = $request->file('file_dokument')->getClientOriginalName();
            }

            $notulen_masuk->save();

            // Add new files if provided
            if(!empty($data['files'])){
                foreach ($data['files'] as $key => $value) {
                    $file = 'notulen_file_' . $notulen_masuk->noagenda . '_' . time() . '_' . $key . '.' . $value->getClientOriginalExtension();
                    $value->storeAs('notulen_files', $file, 'public');
                    NotulenFile::create([
                        'notulen_masuk_id' => $notulen_masuk->id,
                        'file'             => $file,
                        'original_name'    => $value->getClientOriginalName(),
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
