<?php

namespace App\Http\Controllers;

use App\Models\ArsipSurat;
use App\Models\DispoMasuk;
use App\Models\Disposisi;
use App\Models\NotulenFile;
use App\Models\NotulenMasuk;
use App\Models\SuratMasuk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Imports\SuratMasukImport;
use Maatwebsite\Excel\Facades\Excel;

class SuratMasukController extends Controller
{
    public function index(Request $request)
    {
        return view('surat-masuk.index');
    }

    public function disposisi(Request $request)
    {
        return view('surat-masuk.disposisi');
    }

    public function terlewat(Request $request)
    {
        return view('surat-masuk.terlewat');
    }

    public function surat_masuk_all(Request $request)
    {
        return view('surat-masuk.total-surat-masuk');
    }

    public function all(Request $request)
    {
        $draw = $request->get('draw');
        $start = @$request->get("start") ?? 0;
        $rowperpage = @$request->get("length") ?? 10;
        $search_arr = $request->get('search');
        $startDate = @$request->get('startDate');
        $endDate = @$request->get('endDate');
        $month = @$request->get('month');
        $year = @$request->get('year');
        $jenis = @$request->get('jenis');

        $searchValue = @$search_arr['value'] ?? '';
        DB::statement('SET @row_number = ' . $start);
        $surat_masuk = SuratMasuk::query();
        $surat_masuk->select(
            DB::raw('@row_number := @row_number + 1 AS row_id'),
            'surat_masuk.*'
        );
        $surat_masuk->when($startDate != '' && $endDate != '', function ($query) use ($startDate, $endDate) {
            $query->whereBetween('time', [$startDate, $endDate]);
        });
        $surat_masuk->when($month != '', function ($query) use ($month) {
            $query->whereMonth('time', $month);
        });
        $surat_masuk->when($year != '', function ($query) use ($year) {
            $query->whereYear('time', $year);
        });
        $surat_masuk->when($jenis != '', function ($query) use ($jenis) {
            $query->where('jns', $jenis);
        });
        if (Auth::user()->role != 'superadmin' && Auth::user()->role != 'kepala_dinas') {
            $surat_masuk->has('dispomasuk');
            $surat_masuk->whereHas('dispomasuk.dispo', function ($query) {
                if (Auth::user()->role != 'admin') {
                    $query->where('role', Auth::user()->role);
                }
                $query->where('devisi', Auth::user()->devisi);
            });
        }
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
    }

    public function data(Request $request)
    {
        $draw = $request->get('draw');
        $start = @$request->get("start") ?? 0;
        $rowperpage = @$request->get("length") ?? 10;
        $search_arr = $request->get('search');
        $startDate = @$request->get('startDate');
        $endDate = @$request->get('endDate');
        $month = @$request->get('month');
        $year = @$request->get('year');
        $jenis = @$request->get('jenis');

        $searchValue = @$search_arr['value'] ?? '';
        DB::statement('SET @row_number = ' . $start);
        $surat_masuk = SuratMasuk::query();
        $surat_masuk->select(
            DB::raw('@row_number := @row_number + 1 AS row_id'),
            'surat_masuk.*'
        );
        $surat_masuk->whereDate('surat_masuk.time', date('Y-m-d'));
        $surat_masuk->when($startDate != '' && $endDate != '', function ($query) use ($startDate, $endDate) {
            $query->whereBetween('time', [$startDate, $endDate]);
        });
        $surat_masuk->when($month != '', function ($query) use ($month) {
            $query->whereMonth('time', $month);
        });
        $surat_masuk->when($year != '', function ($query) use ($year) {
            $query->whereYear('time', $year);
        });
        $surat_masuk->when($jenis != '', function ($query) use ($jenis) {
            $query->where('jns', $jenis);
        });
        if (Auth::user()->role != 'superadmin' && Auth::user()->role != 'kepala_dinas') {
            $surat_masuk->has('dispomasuk');
            $surat_masuk->whereHas('dispomasuk.dispo', function ($query) {
                if (Auth::user()->role != 'admin') {
                    $query->where('role', Auth::user()->role);
                }
                $query->where('devisi', Auth::user()->devisi);
            });
        }
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
    }

    public function data_disposisi(Request $request)
    {
        $draw = $request->get('draw');
        $start = @$request->get("start") ?? 0;
        $rowperpage = @$request->get("length") ?? 10;
        $search_arr = $request->get('search');
        $startDate = @$request->get('startDate');
        $endDate = @$request->get('endDate');
        $month = @$request->get('month');
        $year = @$request->get('year');
        $jenis = @$request->get('jenis');

        $searchValue = @$search_arr['value'] ?? '';
        DB::statement('SET @row_number = ' . $start);
        $surat_masuk = SuratMasuk::query();
        $surat_masuk->select(
            DB::raw('@row_number := @row_number + 1 AS row_id'),
            'surat_masuk.*'
        );
        $surat_masuk->whereHas('dispomasuk', function ($query) {
            $query->whereNull('tindak')->orWhere('tindak', '');
        })->whereHas('dispomasuk', function ($query) {
            $query->whereNull('ket')->orWhere('ket', '');
        });
        $surat_masuk->when($startDate != '' && $endDate != '', function ($query) use ($startDate, $endDate) {
            $query->whereBetween('time', [$startDate, $endDate]);
        });
        $surat_masuk->when($month != '', function ($query) use ($month) {
            $query->whereMonth('time', $month);
        });
        $surat_masuk->when($year != '', function ($query) use ($year) {
            $query->whereYear('time', $year);
        });
        $surat_masuk->when($jenis != '', function ($query) use ($jenis) {
            $query->where('jns', $jenis);
        });
        if (Auth::user()->role != 'superadmin' && Auth::user()->role != 'kepala_dinas') {
            $surat_masuk->has('dispomasuk');
            $surat_masuk->whereHas('dispomasuk.dispo', function ($query) {
                if (Auth::user()->role != 'admin') {
                    $query->where('role', Auth::user()->role);
                }
                $query->where('devisi', Auth::user()->devisi);
            });
        }
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
    }

    public function data_terlewat(Request $request)
    {
        $draw = $request->get('draw');
        $start = @$request->get("start") ?? 0;
        $rowperpage = @$request->get("length") ?? 10;
        $search_arr = $request->get('search');
        $startDate = @$request->get('startDate');
        $endDate = @$request->get('endDate');
        $month = @$request->get('month');
        $year = @$request->get('year');
        $jenis = @$request->get('jenis');

        $searchValue = @$search_arr['value'] ?? '';
        DB::statement('SET @row_number = ' . $start);
        $surat_masuk = SuratMasuk::query();
        $surat_masuk->select(
            DB::raw('@row_number := @row_number + 1 AS row_id'),
            'surat_masuk.*'
        );
        $surat_masuk->whereHas('dispomasuk', function ($query) {
            $query->whereNull('tindak')->orWhere('tindak', '');
        })->whereHas('dispomasuk', function ($query) {
            $query->whereNull('ket')->orWhere('ket', '');
        });
        $surat_masuk->whereDate('tgl_agenda', '<', date('Y-m-d'));
        $surat_masuk->when($startDate != '' && $endDate != '', function ($query) use ($startDate, $endDate) {
            $query->whereBetween('time', [$startDate, $endDate]);
        });
        $surat_masuk->when($month != '', function ($query) use ($month) {
            $query->whereMonth('time', $month);
        });
        $surat_masuk->when($year != '', function ($query) use ($year) {
            $query->whereYear('time', $year);
        });
        $surat_masuk->when($jenis != '', function ($query) use ($jenis) {
            $query->where('jns', $jenis);
        });
        if (Auth::user()->role != 'superadmin' && Auth::user()->role != 'kepala_dinas') {
            $surat_masuk->has('dispomasuk');
            $surat_masuk->whereHas('dispomasuk.dispo', function ($query) {
                if (Auth::user()->role != 'admin') {
                    $query->where('role', Auth::user()->role);
                }
                $query->where('devisi', Auth::user()->devisi);
            });
        }
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
    }

    public function create()
    {
        $no_agenda = explode('/', @SuratMasuk::where('periode', date('Y'))->orderBy('no_agenda', 'desc')->first()->no_agenda)[0] ?? 0;
        if ($no_agenda == "") {
            $no_agenda = 0;
        }
        $no_agenda = str_pad($no_agenda + 1, 5, '0', STR_PAD_LEFT) . '/' . date('m') . '/' . date('Y');
        $disposisi = Disposisi::groupBy('disposisi')->orderBy('id')->where('aktif', 1)->get();
        $asal = DB::table('surat_masuk')->select('asal')->orderBy('asal')->get()->pluck('asal', 'asal')->unique();
        return view('surat-masuk.create', compact('disposisi', 'no_agenda', 'asal'));
    }

    public function edit(SuratMasuk $surat_masuk)
    {
        $disposisi = Disposisi::groupBy('disposisi')->where('aktif', 1)->orderBy('id')->get();
        return view('surat-masuk.update', compact('disposisi', 'surat_masuk'));
    }

    public function post(Request $request)
    {
        $data = $request->validate([
            'jns'           => 'sometimes',
            'perihal'       => 'required',
            'tanggal'       => 'sometimes',
            'tgl_agenda'    => 'sometimes',
            'jam'           => 'sometimes',
            'tmpt'          => 'required',
            'acara'         => 'required',
            'no_surat'      => 'required|unique:surat_masuk,no_surat',
            'asal'          => 'required',
            // 'penerima'      => 'required',
            'note'          => 'sometimes',
            'no_agenda'     => 'required|unique:surat_masuk,no_agenda',
        ]);
        $data['penerima'] = Auth::user()->username;
        try {
            DB::beginTransaction();
            $data['f_umum'] = 1;
            $data['user'] = Auth::user()->username;
            $data['periode'] = date('Y');

            SuratMasuk::create($data);
            if ($request->disposisi) {
                $disposisi = $request->validate([
                    'disposisi'     => 'required|array',
                    'disposisi.*'   => 'required|exists:disposisi,id',
                    'ket'           => 'sometimes|array',
                    'ket.*'         => 'sometimes',
                    'tindak'         => 'sometimes|array',
                    'tindak.*'       => 'sometimes',
                ]);
                foreach ($disposisi['disposisi'] as $key => $value) {
                    $dispomasuk = [
                        'no_agenda'  => $data['no_agenda'],
                        'nomor'     => $data['no_surat'],
                        'disposisi' => $value,
                        'role'      => Auth::user()->role,
                        'user'      => Auth::user()->username,
                        'ket'       => $disposisi['ket'][$key],
                        'tindak'    => $disposisi['tindak'][$key],
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
            'note'          => 'sometimes',
        ]);
        // dd($data);
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
                    'tindak'         => 'sometimes|array',
                    'tindak.*'       => 'sometimes',

                ]);
                // dd($disposisi);
                foreach ($disposisi['disposisi'] as $key => $value) {
                    $disposisi_id = @$disposisi['id'][$key];
                    $update = [
                        'id'        => $disposisi_id,
                        'disposisi' => $value,
                    ];
                    $dispomasuk = [
                        'no_agenda'  => $surat_masuk->no_agenda,
                        'nomor'     => $data['no_surat'],
                        'disposisi' => $value,
                        'role'      => Auth::user()->role,
                        'user'      => Auth::user()->username,
                        'ket'       => $disposisi['ket'][$key],
                    ];
                    if(@$disposisi['tindak'][$disposisi_id]){
                        $dispomasuk['tindak'] = json_encode(@$disposisi['tindak'][$disposisi_id])?? '';
                    }else{
                        $dispomasuk['tindak'] = '';
                    }
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
            if ($surat_masuk->dispomasuk()->count() > 0) {
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
                'file_dokument' => 'sometimes|file|mimes:pdf,doc,docx|max:2048',
                'files'          => 'sometimes|array',
                'files.*'        => 'sometimes|file|mimes:jpg,jpeg,png|max:2048',
            ]);
            if (isset($data['file_dokument'])) {
                $filename = 'notulen_' . $no_agenda . '_' . time() . '.' . $request->file('file_dokument')->getClientOriginalExtension();
                $request->file('file_dokument')->storeAs('notulen_masuk', $filename, 'public');
                $original_name = $request->file('file_dokument')->getClientOriginalName();
            }
            $surat_masuk = SuratMasuk::where('no_agenda', $no_agenda)->first();
            $notulen_masuk = NotulenMasuk::create([
                'periode'       => date('Y'),
                'noagenda'      => $no_agenda,
                'filename'      => $filename ?? "",
                'original_name' => $original_name ?? "",
                'note'          => $request->notulen,
                'user'          => Auth::user()->username,
            ]);
            if (!empty($data['files'])) {
                foreach ($data['files'] as $key => $value) {
                    $file = 'notulen_file_' . $no_agenda . '_' . time() . '_' . $key . '.' . $value->getClientOriginalExtension();
                    $value->storeAs('notulen_files', $file, 'public');
                    NotulenFile::create([
                        'notulen_id'       => $notulen_masuk->id,
                        'file'             => $file,
                        'jenis'            => 'IN',
                        'original_name'    => $value->getClientOriginalName(),
                    ]);
                }
            }
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'message' => 'Notulen gagal diambil', 'error' => $th->getMessage(), 'request' => $request->all()]);
        }
        return response()->json(['status' => 'success', 'message' => 'Notulen berhasil diambil', 'no_agenda' => $no_agenda]);
    }

    public function notulenData($no_agenda)
    {
        try {
            $notulen_masuk = NotulenMasuk::where('noagenda', $no_agenda)->first();

            if (!$notulen_masuk) {
                return response()->json(['status' => 'error', 'message' => 'Data tidak ditemukan']);
            }

            $notulen_masuk->files = NotulenFile::whereJenis('IN')->where('notulen_id', $notulen_masuk->id)->get();

            return response()->json(['status' => 'success', 'notulen' => $notulen_masuk]);
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'message' => 'Gagal mengambil data', 'error' => $th->getMessage()]);
        }
    }

    public function notulenUpdate(Request $request, $id)
    {
        try {
            $notulen_masuk = NotulenMasuk::find($id);

            if (!$notulen_masuk) {
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
            if ($request->hasFile('file_dokument')) {
                // Delete old file if exists
                if ($notulen_masuk->filename && Storage::disk('public')->exists('notulen_masuk/' . $notulen_masuk->filename)) {
                    Storage::disk('public')->delete('notulen_masuk/' . $notulen_masuk->filename);
                }

                $filename = 'notulen_' . $notulen_masuk->noagenda . '_' . time() . '.' . $request->file('file_dokument')->getClientOriginalExtension();
                $request->file('file_dokument')->storeAs('notulen_masuk', $filename, 'public');
                $notulen_masuk->filename = $filename;
                $notulen_masuk->original_name = $request->file('file_dokument')->getClientOriginalName();
            }

            $notulen_masuk->save();

            // Add new files if provided
            if (!empty($data['files'])) {
                foreach ($data['files'] as $key => $value) {
                    $file = 'notulen_file_' . $notulen_masuk->noagenda . '_' . time() . '_' . $key . '.' . $value->getClientOriginalExtension();
                    $value->storeAs('notulen_files', $file, 'public');
                    NotulenFile::create([
                        'notulen_id' => $notulen_masuk->id,
                        'file'             => $file,
                        'jenis'            => 'IN',
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

            if (!$notulen_file) {
                return response()->json(['status' => 'error', 'message' => 'File tidak ditemukan']);
            }

            // Delete file from storage
            if ($notulen_file->file && Storage::disk('public')->exists('notulen_files/' . $notulen_file->file)) {
                Storage::disk('public')->delete('notulen_files/' . $notulen_file->file);
            }

            // Delete record from database
            $notulen_file->delete();

            return response()->json(['status' => 'success', 'message' => 'File berhasil dihapus']);
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'message' => 'File gagal dihapus', 'error' => $th->getMessage()]);
        }
    }

    public function uploadFile(Request $request)
    {
        try {
            $request->validate([
                'file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png,gif|max:10240',
                'no_agenda' => 'sometimes|string',
            ]);

            if (!$request->hasFile('file')) {
                return response()->json(['status' => 'error', 'message' => 'File tidak ditemukan'], 400);
            }

            $file = $request->file('file');
            $originalName = $file->getClientOriginalName();
            $fileName = 'file_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('uploads', $fileName, 'public');

            // Save file information to database
            $arsip_surat = ArsipSurat::create([
                'file' => $fileName,
                'original_name' => $originalName,
                'file_size' => $file->getSize(),
                'file_type' => $file->getMimeType(),
                'no_agenda' => $request->input('no_agenda'),
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'File berhasil diunggah',
                'id' => $arsip_surat->id,
                'filename' => $fileName,
                'path' => $path,
                'original_name' => $originalName,
                'file_size' => $file->getSize()
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => 'File gagal diunggah: ' . $th->getMessage()
            ], 422);
        }
    }

    public function deleteFile($id)
    {
        try {
            $arsip_surat = ArsipSurat::find($id);

            if (!$arsip_surat) {
                return response()->json(['status' => 'error', 'message' => 'File tidak ditemukan']);
            }

            // Delete file from storage
            if ($arsip_surat->file && Storage::disk('public')->exists('uploads/' . $arsip_surat->file)) {
                Storage::disk('public')->delete('uploads/' . $arsip_surat->file);
            }

            // Delete record from database
            $arsip_surat->delete();

            return response()->json(['status' => 'success', 'message' => 'File berhasil dihapus']);
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'message' => 'File gagal dihapus', 'error' => $th->getMessage()]);
        }
    }

    /**
     * Return JSON list of arsip files for a given surat masuk id
     */
    public function files($id)
    {
        $files = ArsipSurat::where('no_agenda', $id)->get();
        return response()->json(['status' => 'success', 'files' => $files]);
    }

    public function import(Request $request)
    {
        try {
            $request->validate([
                'file' => 'required|file|mimes:xlsx,xls|max:10240',
            ]);

            $file = $request->file('file');
            Excel::import(new SuratMasukImport, $file);
            return redirect()->back()->with('success', 'File berhasil diimpor');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'File gagal diunggah: ' . $th->getMessage());
        }
    }
}
