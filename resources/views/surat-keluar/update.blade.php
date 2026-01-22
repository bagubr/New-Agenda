@extends('templates.dashboard-layout')
@push('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush
@section('content')

<main class="app-main"> <!--begin::App Content Header-->
    <div class="app-content-header"> <!--begin::Container-->
        <div class="container-fluid"> <!--begin::Row-->
            <div class="row">
                @include('templates.alert')
                <div class="col-sm-6">
                    <h3 class="mb-0">Surat Keluar</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item" aria-current="page">
                            Surat Keluar
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Edit
                        </li>
                    </ol>
                </div>
            </div> <!--end::Row-->
        </div> <!--end::Container-->
    </div> <!--end::App Content Header--> <!--begin::App Content-->
    <div class="app-content"> <!--begin::Container-->
        <div class="container-fluid"> <!--begin::Row-->
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-header">
                        <h3 class="card-title">Edit</h3>
                    </div>
                    <!--begin::Form-->
                    <form action="{{route('surat-keluar.update', $surat_keluar->no_agenda)}}" method="POST">
                        @method('PUT')
                        @csrf
                        <!--begin::Body-->
                        <div class="card-body">
                            <div class="mb-1">
                                <label for="jns" class="form-label">Jenis</label>
                                <br>
                                <input type="radio" class="form-check-input" id="undangan" name="jns" value="1" {{($surat_keluar->jns == "1")?'checked':''}} required/>
                                <label class="form-check-label" for="undangan">Undangan</label>
                                <input type="radio" class="form-check-input" id="nonundangan" name="jns" value="0" {{($surat_keluar->jns == "0")?'checked':''}} required/>
                                <label class="form-check-label" for="nonundangan">Non Undangan</label>
                            </div>
                            <div class="mb-1">
                                <label for="no_agenda" class="form-label">No Agenda</label>
                                <input type="text" name="no_agenda" class="form-control" id="no_agenda" readonly value="{{$surat_keluar->no_agenda}}"/>
                            </div>
                            <div class="mb-1">
                                <label for="perihal" class="form-label">Perihal</label>
                                <textarea name="perihal" class="form-control" style="width: 100%; height:150px;" id="perihal" required>{!!$surat_keluar->perihal!!}</textarea>
                            </div>
                            <div class="mb-1">
                                <label for="tanggal" class="form-label">Tanggal Surat</label>
                                <input type="date" name="tanggal" class="form-control" id="tanggal" required value="{{date('Y-m-d', strtotime($surat_keluar->tanggal))}}">
                            </div>
                            <div class="mb-1">
                                <div class="row">
                                    <div class="col-6">
                                        <label for="tgl_agenda" class="form-label">Tanggal Agenda</label>
                                        <input type="date" name="tgl_agenda" class="form-control" id="tgl_agenda" value="{{date('Y-m-d', strtotime($surat_keluar->tgl_agenda))}}">
                                    </div>
                                    <div class="col-6">
                                        <label for="jam" class="form-label">Jam</label>
                                        <input type="time" name="jam" class="form-control" id="jam" value="{{date('H:i', strtotime($surat_keluar->jam))}}">
                                    </div>
                                </div>
                            </div>
                            <div class="mb-1">
                                <label for="tempat" class="form-label">Tempat Agenda / Undagan</label>
                                <textarea name="tempat" class="form-control" style="width: 100%; height:100px;" id="tempat" required>{{$surat_keluar->tempat}}</textarea>
                            </div>
                            <div class="mb-1">
                                <label for="acara" class="form-label">Acara</label>
                                <textarea name="acara" class="form-control" style="width: 100%; height:100px;" id="acara" required>{{$surat_keluar->acara}}</textarea>
                            </div>
                            <div class="mb-1">
                                <label for="no_surat" class="form-label">No Surat</label>
                                <input type="text" name="no_surat" class="form-control" id="no_surat" required value="{{$surat_keluar->no_surat}}"/>
                            </div>
                            <div class="mb-1">
                                <label for="asal" class="form-label">Asal</label>
                                <input type="text" name="asal" class="form-control" id="asal" required value="{{$surat_keluar->asal}}"/>
                            </div>
                            <div class="mb-1">
                                <label for="penerima" class="form-label">Penerima</label>
                                <input type="text" name="penerima" class="form-control" id="penerima" value="{{$surat_keluar->penerima}}"/>
                            </div>
                            <div class="mb-1">
                                <label for="note" class="form-label">Catatan</label>
                                <textarea name="note" class="form-control" style="width: 100%; height:150px;" id="note">@if (isset($surat_keluar->note)){!!$surat_keluar->note!!}@else<p></p>@endif
                                </textarea>
                            </div>
                            <div class="mb-1">
                                <label for="publish" class="form-label">Publish ke Tvtrone ?</label>
                                <input type="checkbox" class="form-check-input" id="publish" name="publish" value="1" {{($surat_keluar->publish == "1")?'checked':''}}/>
                            </div>
                            <div class="card p-2 mb-1">
                                @foreach ($surat_keluar->disposisi as $key => $item)
                                <br>
                                {{$item->disposisi_name}}
                                <input type="hidden" name="disposisi[]" class="form-control m-1" value="{{$item->disposisi}}"/>
                                <div class="input-group">
                                    <input type="text" name="ket[]" class="form-control" value="{{$item->ket}}"/>
                                    <input type="hidden" name="id[]" class="form-control" value="{{$item->id}}"/>
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-danger">
                                            <a class="" onclick="deleteDispo({{ $item->id }}, '{{ route('dispo-masuk-delete', $item->id) }}')"><i class="bi bi-trash" style="color: white;"></i></a>
                                        </div>
                                    </div>
                                </div>
                                
                                @endforeach
                                <br>
                                <label for="disposisi" class="form-label"><b>Disposisi</b></label>
                                <select  class="form-control" id="disposisi" name="disposisi[]" multiple>
                                    @foreach ($disposisi as $item)
                                        <option value="{{sprintf('%02d',  $item->id)}}" class="options">{{$item->disposisi}}</option>
                                    @endforeach
                                </select>
                                <div class="m-1" id="disposisi_ket">
    
                                </div>
                            </div>
                        </div>
                        <!--end::Body-->
                        <!--begin::Footer-->
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary float-end">Submit</button>
                        </div>
                        <!--end::Footer-->
                    </form>
                    <!--end::Form-->
                </div> <!-- /.card -->
            </div>
        </div>
    </div>
</main> <!--end::App Main--> <!--begin::Footer-->
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    function deleteDispo(id, url){
        Swal.fire({
            title: 'Yakin?',
            text: 'Data disposisi akan dihapus permanen.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                var form = document.createElement('form');
                form.method = 'POST';
                form.action = url;
                var token = document.createElement('input');
                token.type = 'hidden';
                token.name = '_token';
                token.value = '{{ csrf_token() }}';
                form.appendChild(token);
                var method = document.createElement('input');
                method.type = 'hidden';
                method.name = '_method';
                method.value = 'DELETE';
                form.appendChild(method);
                document.body.appendChild(form);
                form.submit();
            }
        });
    }
    $(document).ready(function(e) {
        $('#disposisi').select2();
    });
    $('#disposisi').on('change', function() {
        $('#disposisi_ket').empty();
        var html = '<b>Keterangan</b> <br>';
        $(this).find('option:selected').each(function() {
            var value = $(this).text();
            html += value;
            html += '<input type="text" name="ket[]" class="form-control m-1" id="ket'+this.value+'"/>'
        });
        $('#disposisi_ket').html(html);
    });
</script>
@endpush
