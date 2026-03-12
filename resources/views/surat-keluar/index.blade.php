@extends('templates.dashboard-layout')
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
                        <li class="breadcrumb-item active" aria-current="page">
                            Surat Keluar
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
                        <h3 class="card-title">Surat Keluar</h3>
                        <div class="card-tools">
                            <a href="{{route('surat-keluar.create')}}" class="btn btn-success">Tambah</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form id="filterForm" class="row g-2 mb-3 align-items-end">
                            <div class="col-auto">
                                <label for="startDate" class="form-label mb-0">Tanggal Agenda Dari</label>
                                <input type="date" class="form-control" id="startDate" name="startDate">
                            </div>
                            <div class="col-auto">
                                <label for="endDate" class="form-label mb-0">Sampai</label>
                                <input type="date" class="form-control" id="endDate" name="endDate">
                            </div>
                            <div class="col-auto">
                                <button type="button" id="filterBtn" class="btn btn-primary">Cari</button>
                                <button type="button" id="resetBtn" class="btn btn-secondary">Reset</button>
                            </div>
                        </form>
                        <table id="dataTable" class="table table-bordered table-responsive">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Jenis</th>
                                    <th>Agenda</th>
                                    <th>Tanggal</th>
                                    <th>Agenda</th>
                                    <th>Nomor</th>
                                    <th>Asal</th>
                                </tr>
                            </thead>
                        </table>
                    </div> <!-- /.card-body -->
                </div> <!-- /.card -->
            </div>
        </div>
    </div>
</main> <!--end::App Main--> <!--begin::Footer-->

<!-- Notulen Modal -->
<div class="modal fade" id="notulenModal" tabindex="-1" role="dialog" aria-labelledby="notulenModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="notulenModalLabel">Notulen Rapat</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <!-- Display Section -->
            <div id="notulenDisplaySection" class="modal-body" style="display:none;">
                <div class="alert alert-info">
                    <h6>Data Notulen yang Sudah Disimpan</h6>
                </div>
                <div class="mb-3">
                    <label class="form-label"><strong>Isi Notulen:</strong></label>
                    <div class="border p-3 rounded bg-light">
                        <p id="displayNotulenContent"></p>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label"><strong>File Dokumen:</strong></label>
                    <div id="displayFileDocument"></div>
                </div>
                <div class="mb-3">
                    <label class="form-label"><strong>Foto Kegiatan:</strong></label>
                    <div id="displayNotulenFiles"></div>
                </div>
                <div class="mb-3">
                    <label class="form-label"><strong>User:</strong></label>
                    <p id="displayUser"></p>
                </div>
                <div class="mb-3">
                    <label class="form-label"><strong>Tanggal Disimpan:</strong></label>
                    <p id="displayDate"></p>
                </div>
                <div id="displayNotulenId" style="display:none;"></div>
            </div>
            <div class="modal-footer" id="notulenDisplayFooter" style="display:none;">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-warning" id="editNotulenBtn">Edit</button>
            </div>

            <!-- Form Section -->
            <form id="notulenForm" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body" id="notulenFormSection">
                    <div class="mb-3">
                        <label for="notulenContent" class="form-label">Isi Notulen</label>
                        <textarea class="form-control" id="notulenContent" name="notulen" rows="5" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="file_dokument" class="form-label">File Dokumen</label>
                        <input class="form-control" type="file" id="file_dokument" name="file_dokument" rows="5"></input>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Foto Kegiatan</label>
                        <div class="d-flex gap-2 mb-2">
                            <button type="button" id="addNotulenFileBtn" class="btn btn-sm btn-outline-primary">Tambah Foto</button>
                            <small class="text-muted align-self-center">Tambahkan beberapa foto bila perlu</small>
                        </div>
                        <div id="notulenFilesContainer"></div>
                    </div>
                </div>
                <div class="modal-footer" id="notulenFormFooter">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-secondary" id="cancelEditBtn" style="display:none;">Batal</button>
                    <button type="submit" class="btn btn-primary" id="submitNotulenBtn">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('js')

<script>
    // Call the dataTables jQuery plugin
    $(document).ready(function() {
        $('#dataTable').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            iDisplayLength: 10,
            ajax: {
                url: "{{route('surat-keluar-data')}}",
                complete: function(data) {
                    console.log('Succesfully get data');
                }
            },
            columns: [{
                    data: 'row_id'
                },
                {
                    data: 'jenis'
                },
                {
                    data: 'no_agenda'
                },
                {
                    data: 'tanggal',
                    render: function(data, type, row) {
                        const date = new Date(data);
                        const formatted = date.toLocaleDateString("id-ID", {
                            weekday: "long",
                            day: "2-digit",
                            month: "long",
                            year: "numeric"
                        });
                        return formatted;
                    }
                },
                {
                    data: 'tgl_agenda',
                    render: function(data, type, row) {
                        const date = new Date(row.tgl_agenda);
                        const formatted = date.toLocaleDateString("id-ID", {
                            weekday: "long",
                            day: "2-digit",
                            month: "long",
                            year: "numeric"
                        });
                        return formatted + ' <b>(' + row.jam + ')</b> <hr> Tempat : '+row.tmpt+'<hr>Acara : '+row.acara;
                    }
                },
                {
                    data: 'no_surat'
                },
                {
                    data: 'asal'
                },
                {
                    data: 'no_agenda',
                    title: 'Aksi',
                    render: function(data, type, row){
                        html = `
                        <a href="{{url('surat-keluar-edit')}}/`+data+`" class="badge bg-info">Edit</a>
                        <a href="#" class="badge bg-danger" onclick="deleteSurat('`+data+`')">Delete</a>
                        `;
                        return html;
                    }
                }
            ]
        });
    });
</script>
<script>

    function deleteSurat(noAgenda){
        const url = `{{ url('surat-keluar-delete') }}/${noAgenda}`;
        Swal.fire({
            title: 'Yakin?',
            text: 'Surat akan dihapus permanen.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = url;
                const token = document.createElement('input');
                token.type = 'hidden';
                token.name = '_token';
                token.value = '{{ csrf_token() }}';
                form.appendChild(token);
                const method = document.createElement('input');
                method.type = 'hidden';
                method.name = '_method';
                method.value = 'DELETE';
                form.appendChild(method);
                document.body.appendChild(form);
                form.submit();
            }
        });
    }
    // Filter functionality
    document.getElementById('filterBtn').addEventListener('click', function(){
        const startDate = document.getElementById('startDate').value;
        const endDate = document.getElementById('endDate').value;
        $('#dataTable').DataTable().ajax.url(`{{route('surat-masuk-data')}}?startDate=${startDate}&endDate=${endDate}`).load();
    });

    document.getElementById('resetBtn').addEventListener('click', function(){
        document.getElementById('filterForm').reset();
        $('#dataTable').DataTable().ajax.url(`{{route('surat-masuk-data')}}`).load();
    });
</script>

@endpush
