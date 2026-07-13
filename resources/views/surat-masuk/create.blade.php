@extends('templates.dashboard-layout')
@push('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/47.3.0/ckeditor5.css" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-5-theme/1.3.0/select2-bootstrap-5-theme.min.css" />

    <style>
        .main-container {
            width: 795px;
            margin-left: auto;
            margin-right: auto;
        }
    </style>
@endpush
@section('content')
    <main class="app-main"> <!--begin::App Content Header-->
        <div class="app-content-header"> <!--begin::Container-->
            <div class="container-fluid"> <!--begin::Row-->
                <div class="row">
                    @include('templates.alert')
                    <div class="col-sm-6">
                        <h3 class="mb-0">Surat Masuk</h3>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item" aria-current="page">
                                Surat Masuk
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                Tambah
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
                            <h3 class="card-title">Tambah</h3>
                            <div class="card-tools">
                                <a href="#" class="btn btn-sm btn-success" data-bs-toggle="modal"
                                    data-bs-target="#importModal">Import Data</a>
                                <a href="{{ route('surat-masuk') }}" class="btn btn-sm btn-light">Kembali</a>
                            </div>
                        </div>
                        <!--begin::Form-->
                        <form id="suratForm" action="{{ route('surat-masuk-post') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <!--begin::Body-->
                            <div class="card-body">
                                <div class="mb-1">
                                    <label for="jns" class="form-label">Jenis</label>
                                    <br>
                                    <input type="radio" class="form-check-input" id="undangan" name="jns"
                                        value="1" required />
                                    <label class="form-check-label" for="undangan">Undangan</label>
                                    <input type="radio" class="form-check-input" id="nonundangan" name="jns"
                                        value="2" required />
                                    <label class="form-check-label" for="nonundangan">Non Undangan</label>
                                    <input type="radio" class="form-check-input" id="usulan_pembangunan" name="jns"
                                        value="3" required />
                                    <label class="form-check-label" for="usulan_pembangunan">Usulan Pembangunan</label>
                                </div>
                                <div class="mb-1">
                                    <label for="no_agenda" class="form-label">No Agenda</label>
                                    <input type="text" name="no_agenda" class="form-control" id="no_agenda" readonly
                                        value="{{ $no_agenda }}" />
                                </div>
                                <div class="mb-1">
                                    <label for="perihal" class="form-label">Perihal</label>
                                    <textarea name="perihal" class="form-control" style="width: 100%; height:150px;" id="perihal" required></textarea>
                                </div>
                                <div class="mb-1">
                                    <label for="tanggal" class="form-label">Tanggal Surat</label>
                                    <input type="date" name="tanggal" class="form-control" id="tanggal" required>
                                </div>
                                <div class="mb-1">
                                    <div class="row">
                                        <div class="col-6">
                                            <label for="tgl_agenda" class="form-label">Tanggal Agenda</label>
                                            <input type="date" name="tgl_agenda" class="form-control" id="tgl_agenda">
                                        </div>
                                        <div class="col-6">
                                            <label for="jam" class="form-label">Jam</label>
                                            <input type="time" name="jam" class="form-control" id="jam">
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-1">
                                    <label for="tmpt" class="form-label">Tempat Agenda / Undangan</label>
                                    <textarea name="tmpt" class="form-control" style="width: 100%; height:100px;" id="tmpt" required></textarea>
                                </div>
                                <div class="mb-1">
                                    <label for="acara" class="form-label">Acara</label>
                                    <textarea name="acara" class="form-control" style="width: 100%; height:100px;" id="acara" required></textarea>
                                </div>
                                <div class="mb-1">
                                    <label for="no_surat" class="form-label">No Surat</label>
                                    <input type="text" name="no_surat" class="form-control" id="no_surat"
                                        required />
                                </div>
                                <div class="mb-1">
                                    <label for="asal" class="form-label">Asal</label>
                                    <select name="asal" id="asal" class="form-select mb-2 select2" required>
                                        <option value="">-- Pilih Asal --</option>
                                        @foreach ($asal as $item)
                                            <option value="{{ $item }}">{{ $item }}</option>
                                        @endforeach
                                    </select>
                                    <!-- <input type="text" name="asal" class="form-control" id="asal" required/> -->
                                </div>
                                <div class="mb-1">
                                    <label for="note" class="form-label">Catatan</label>
                                    <textarea name="note" class="form-control" style="width: 100%; height:150px;" id="note" required></textarea>
                                </div>
                                <div class="card p-2 mb-1">
                                    <label for="disposisi" class="form-label"><b>Disposisi</b></label>
                                    <br>
                                    <select class="form-control" id="disposisi" name="disposisi[]" multiple>
                                        @foreach ($disposisi as $item)
                                            <option value="{{ $item->id }}" class="options">{{ $item->disposisi }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="m-1" id="disposisi_ket">

                                </div>
                            </div>
                            <!--end::Body-->
                            <!--begin::Footer-->
                            <div class="card-footer">
                                <button type="button" id="submitBtn" class="btn btn-primary">Submit</button>
                            </div>
                            <!--end::Footer-->
                        </form>
                        <!--end::Form-->
                    </div> <!-- /.card -->
                </div>
            </div>
        </div>
    </main> <!--end::App Main--> <!--begin::Footer-->

    <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"><i class="fas fa-cloud-upload-alt"></i> Upload File
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Dropzone Upload Form -->
                    <form action="{{ route('surat-masuk-import') }}" enctype="multipart/form-data" method="POST">
                        @csrf
                        <input type="file" name="file" class="form-control" accept=".xlsx, .xls" required>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success" data-bs-dismiss="modal">Import</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i> Tutup
                    </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function(e) {
            $('#disposisi').select2();
            $('#asal').select2({
                tags: true,
                width: 'resolve',
            });
        });

        $('#disposisi').on('change', function() {
            $('#disposisi_ket').empty();
            var html = '';
            $(this).find('option:selected').each(function() {
                var value = $(this).text();
                var id = $(this).val();
                html += value;
                html += '<div class="input-group">';
                html += '<select name="tindak[]" class="form-control selectdisposisi" data-id="' + id +
                    '" multiple>'
                html += '<option value="">Pilih Tindak Lanjut</option>'
                html += '</select>'
                html += '<input type="text" name="ket[]" class="form-control m-1" id="ket' + this.value +
                    '"/>'
                html += '</div>'
            });
            $('#disposisi_ket').html(html);

            // Initialize select2 on dynamically created .selectdisposisi elements
            $('#disposisi_ket').find('.selectdisposisi').each(function() {
                var $el = $(this);
                console.log($el.data('id'));
                // destroy previous select2 if present to avoid dup errors
                if ($el.hasClass('select2-hidden-accessible')) {
                    $el.select2('destroy');
                }
                $el.select2({
                    tags: true,
                    width: 'resolve',
                    theme: 'bootstrap-5',
                    containerCssClass: ':all:',
                    ajax: {
                        url: "{{ url('/pilih-user-data/') }}" + '/' + $el.data('id'),
                        dataType: 'json',
                        type: 'GET',
                        delay: 300,
                        processResults: function(params) {
                            return {
                                results: $.map(params.data, function(item) {
                                    return {
                                        text: item.nama,
                                        id: item.nama
                                    }
                                })
                            };
                        },
                        cache: true
                    }
                });
            });
        });

        // No-op: .selectdisposisi initialization happens after dynamic creation above

        // jQuery submit button handler
        $(document).ready(function() {
            $('#submitBtn').on('click', function(e) {
                e.preventDefault();
                const form = document.getElementById('suratForm');
                const formData = new FormData(form);
                formData.set('perihal', $('#perihal').val());
                formData.set('note', $('#note').val());

                $.ajax({
                    type: 'POST',
                    url: form.action,
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        if (response.status !== 'success') {
                            let message = response.message || 'Terjadi kesalahan';
                            Swal.fire('Error', message, 'error');
                            return;
                        }
                        Swal.fire('Sukses', 'Surat berhasil disimpan', 'success').then(() => {
                            window.location.href = "{{ route('surat-masuk') }}";
                        });
                    },
                    error: function(xhr) {
                        let message = 'Terjadi kesalahan';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        }
                        Swal.fire('Error', message, 'error');
                        console.error('Error:', xhr);
                    }
                });
            });
        });
    </script>
@endpush
