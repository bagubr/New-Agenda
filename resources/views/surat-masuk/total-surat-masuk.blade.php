@extends('templates.dashboard-layout')
@push('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/dropzone@6.0.0-beta.1/dist/dropzone.min.css" />
<style>
    .dropzone {
        border: 2px dashed #007bff;
        border-radius: 5px;
        background: #f8f9fa;
        padding: 40px;
        min-height: 250px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .dropzone.dz-drag-hover {
        background: #e7f1ff;
        border-color: #0056b3;
    }

    .dz-message {
        text-align: center;
        font-size: 16px;
        color: #666;
    }

    .dz-message i {
        font-size: 48px;
        color: #007bff;
        margin-bottom: 15px;
        display: block;
    }

    /* Dropzone preview grid */
    #myDropzone {
        display: grid !important;
        grid-template-columns: repeat(auto-fill, minmax(120px, 1fr)) !important;
        gap: 10px !important;
        padding: 20px !important;
        min-height: auto !important;
        max-height: 400px !important;
        overflow-y: auto !important;
    }

    #myDropzone.dz-drag-hover {
        grid-template-columns: repeat(auto-fill, minmax(120px, 1fr)) !important;
    }

    .dz-message {
        grid-column: 1 / -1 !important;
        display: flex !important;
        flex-direction: column !important;
        align-items: center !important;
        justify-content: center !important;
        min-height: 200px !important;
    }

    /* allow JS to hide message even with !important rules */
    .dz-message.hidden {
        display: none !important;
    }

    .dz-preview {
        margin: 0 !important;
        display: flex !important;
        flex-direction: column !important;
        align-items: center !important;
        justify-content: center !important;
        padding: 10px !important;
        border: 1px solid #ddd !important;
        border-radius: 4px !important;
        background: #f9f9f9 !important;
        min-height: 120px !important;
        position: relative !important;
    }

    .dz-preview .dz-image {
        width: 100% !important;
        height: auto !important;
        border-radius: 4px !important;
    }

    .dz-preview .dz-image img {
        width: 100% !important;
        height: auto !important;
    }

    .dz-preview .dz-details {
        width: 100% !important;
        padding: 5px !important;
        text-align: center !important;
    }

    .dz-preview .dz-details .dz-filename {
        font-size: 12px !important;
        word-break: break-word !important;
    }

    .dz-preview .dz-details .dz-size {
        font-size: 11px !important;
        color: #999 !important;
    }

    .dz-success-mark,
    .dz-error-mark {
        display: none;
    }

    /* Responsive breakpoints for dropzone */
    @media (max-width: 768px) {
        #myDropzone {
            grid-template-columns: repeat(auto-fill, minmax(100px, 1fr)) !important;
            padding: 15px !important;
        }

        .dz-preview {
            min-height: 100px !important;
        }
    }

    @media (max-width: 576px) {
        #myDropzone {
            grid-template-columns: repeat(auto-fill, minmax(80px, 1fr)) !important;
            padding: 10px !important;
        }

        .dz-preview {
            min-height: 80px !important;
            padding: 8px !important;
        }

        .dz-preview .dz-details .dz-filename {
            font-size: 10px !important;
        }
    }

    #uploadedFilesList {
        max-height: 300px;
        overflow-y: auto;
    }

    /* Responsive file list grid */
    #filesList {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 12px;
        max-height: 500px !important;
        overflow-y: auto;
    }

    #filesList>div {
        min-width: 0;
    }

    /* Ensure file items are responsive */
    @media (max-width: 768px) {
        #filesList {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 576px) {
        #filesList {
            grid-template-columns: 1fr;
        }
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
                        <li class="breadcrumb-item active" aria-current="page">
                            Surat Masuk
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
                        <h3 class="card-title">Surat Masuk</h3>
                        @if(Auth::user()->role == 'superadmin')
                        <div class="card-tools">
                            <a href="{{route('surat-masuk.create')}}" class="btn btn-success">Tambah</a>
                        </div>
                        @endif
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
                                    <th>Tempat dan Waktu</th>
                                    <th>Nomor Surat</th>
                                    <th>Tanggal Surat</th>
                                    <th>Asal</th>
                                    <th>Disposisi</th>
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
                        <label for="file_dokument" class="form-label">File Dokumen </label>
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
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><i class="fas fa-cloud-upload-alt"></i> Upload File</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Dropzone Upload Form -->
                <form action="{{ route('upload-file') }}" class="dropzone" id="myDropzone">
                    @csrf
                    <input type="hidden" id="noAgendaInput" name="no_agenda">
                    <div class="dz-message needsclick">
                        <i class="fas fa-cloud-upload-alt" style="font-size: 48px; color: #007bff;"></i>
                        <h6 class="mt-3"><strong>Tarik file ke sini atau klik untuk memilih</strong></h6>
                        <span class="text-muted" style="font-size: 12px;">Tipe file: PDF, DOC, DOCX, XLS, XLSX, JPG, PNG, GIF<br>Ukuran max: 10MB per file</span>
                    </div>
                </form>

                <!-- Uploaded Files Preview -->
                <div id="uploadPreview" class="mt-3" style="display: none;">
                    <h6>File yang Terupload:</h6>
                    <div id="filesList" class="border rounded p-2 bg-light" style="max-height: 250px; overflow-y: auto;">
                    </div>
                </div>

                <!-- Upload Statistics -->
                <div id="uploadStats" class="mt-3" style="display: none;">
                    <div class="row g-2">
                        <div class="col-6">
                            <div class="p-2 border rounded bg-light text-center">
                                <small class="text-muted">Total File</small>
                                <p class="mb-0"><strong id="totalFilesCount">0</strong></p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-2 border rounded bg-light text-center">
                                <small class="text-muted">Total Ukuran</small>
                                <p class="mb-0"><strong id="totalSizeCount">0 MB</strong></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i> Tutup
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/dropzone@6.0.0-beta.1/dist/dropzone-min.js"></script>
<script>
    // Call the dataTables jQuery plugin
    $(document).ready(function() {
        $('#dataTable').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            iDisplayLength: 10,
            ajax: {
                url: "{{route('surat-masuk-data-all')}}",
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
                    data: 'tgl_agenda',
                    render: function(data, type, row) {
                        const date = new Date(row.tgl_agenda);
                        const formatted = date.toLocaleDateString("id-ID", {
                            weekday: "long",
                            day: "2-digit",
                            month: "long",
                            year: "numeric"
                        });
                        const time = new Date(row.time);
                        const timeFormatted = time.toLocaleTimeString("id-ID", {
                            hour: "2-digit",
                            minute: "2-digit",
                        });
                        if(row.jns == 3) {
                            return formatted + ' <b>(' + timeFormatted + ')</b> <hr> Tempat : ' + row.asal + '<hr>Acara : ' + row.acara;
                        }else{
                            return formatted + ' <b>(' + row.jam + ')</b> <hr> Tempat : ' + row.tmpt + '<hr>Acara : ' + row.acara;
                        }
                        
                    }
                },
                {
                    data: 'no_surat'
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
                    data: 'asal'
                },
                {
                    data: 'disposisi_all'
                },
                {
                    data: 'id',
                    title: 'Aksi',
                    render: function(data, type, row) {
                        var html = '';
                        const date = new Date(row.tgl_agenda);
                        if (row.jns == 1) {
                            if (date <= new Date()) {
                                html += '<a href="#" class="badge bg-success" onclick="openNotulenModal(\'' + data + '\')" data-bs-toggle="modal" data-bs-target="#notulenModal">Notulen</a>';
                            } else {
                                html += '<a href="#" class="badge bg-secondary">Notulen</a>';
                            }
                        } 
                        html += `
                        <a href="{{url('surat-masuk-edit')}}/` + data + `" class="badge bg-info">Update</a>
                        <a class="badge bg-primary ms-auto add-file-btn" data-id="` + data + `" data-bs-toggle="modal" data-bs-target="#exampleModal">
                            <i class="fas fa-plus"></i> Files
                        </a>`;
                        if ('{{Auth::user()->role}}' === 'superadmin') {
                            html += `<a href="#" class="badge bg-danger" onclick="deleteSurat('` + data + `')">Delete</a>`;
                        }
                        return html;
                    }
                }
            ]
        });
    });
</script>
<script>
    let currentEditId = null;

    // Dynamic file inputs
    function openNotulenModal(noAgenda) {
        document.getElementById('notulenForm').action = `{{ url('surat-masuk-notulen') }}/${noAgenda}`;

        // Fetch existing notulen data
        fetch(`{{ url('surat-masuk-notulen-data') }}/${noAgenda}`)
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success' && data.notulen) {
                    currentEditId = data.notulen.id;
                    document.getElementById('displayNotulenId').textContent = data.notulen.id;

                    // Show display section
                    document.getElementById('notulenDisplaySection').style.display = 'block';
                    document.getElementById('notulenDisplayFooter').style.display = 'flex';
                    document.getElementById('notulenFormSection').style.display = 'none';
                    document.getElementById('notulenFormFooter').style.display = 'none';

                    // Populate display data
                    document.getElementById('displayNotulenContent').textContent = data.notulen.note;

                    // Display file document
                    const fileDocDiv = document.getElementById('displayFileDocument');
                    if (data.notulen.filename) {
                        fileDocDiv.innerHTML = `
                            <a href="{{ url('storage') }}/notulen_masuk/${data.notulen.filename}" target="_blank" class="badge bg-primary">
                                📄 ${data.notulen.original_name}
                            </a>
                        `;
                    } else {
                        fileDocDiv.innerHTML = '<p class="text-muted">Tidak ada file dokumen</p>';
                    }

                    // Display notulen files
                    const filesDiv = document.getElementById('displayNotulenFiles');
                    if (data.notulen.files && data.notulen.files.length > 0) {
                        filesDiv.innerHTML = '<div class="row">';
                        data.notulen.files.forEach(file => {
                            filesDiv.innerHTML += `
                                <div class="col-md-3 mb-2">
                                    <div style="position: relative; display: inline-block; width: 100%;">
                                        <a href="{{ url('storage') }}/notulen_files/${file.file}" target="_blank">
                                            <img src="{{ url('storage') }}/notulen_files/${file.file}" style="width: 100%; height: auto; border-radius: 4px;" class="img-thumbnail">
                                        </a>
                                        <button type="button" class="btn btn-sm btn-danger" style="position: absolute; top: 5px; right: 5px;" onclick="deleteNotulenFile(${file.id}, '${file.file}')">×</button>
                                    </div>
                                </div>
                            `;
                        });
                        filesDiv.innerHTML += '</div>';
                    } else {
                        filesDiv.innerHTML = '<p class="text-muted">Tidak ada foto kegiatan</p>';
                    }

                    document.getElementById('displayUser').textContent = data.notulen.user;
                    document.getElementById('displayDate').textContent = new Date(data.notulen.tgin).toLocaleString('id-ID');
                } else {
                    // Show form section for new entry
                    currentEditId = null;
                    document.getElementById('notulenDisplaySection').style.display = 'none';
                    document.getElementById('notulenDisplayFooter').style.display = 'none';
                    document.getElementById('notulenFormSection').style.display = 'block';
                    document.getElementById('notulenFormFooter').style.display = 'flex';
                    document.getElementById('cancelEditBtn').style.display = 'none';

                    document.getElementById('notulenContent').value = '';
                    const container = document.getElementById('notulenFilesContainer');
                    container.innerHTML = '';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                // Show form on error
                currentEditId = null;
                document.getElementById('notulenDisplaySection').style.display = 'none';
                document.getElementById('notulenDisplayFooter').style.display = 'none';
                document.getElementById('notulenFormSection').style.display = 'block';
                document.getElementById('notulenFormFooter').style.display = 'flex';
                document.getElementById('cancelEditBtn').style.display = 'none';
            });
    }

    document.getElementById('editNotulenBtn').addEventListener('click', function() {
        // Switch to edit mode
        document.getElementById('notulenDisplaySection').style.display = 'none';
        document.getElementById('notulenDisplayFooter').style.display = 'none';
        document.getElementById('notulenFormSection').style.display = 'block';
        document.getElementById('notulenFormFooter').style.display = 'flex';
        document.getElementById('cancelEditBtn').style.display = 'inline-block';

        // Populate form with current data
        document.getElementById('notulenContent').value = document.getElementById('displayNotulenContent').textContent;
        document.getElementById('notulenForm').action = `{{ url('surat-masuk-notulen-update') }}/${currentEditId}`;
    });

    document.getElementById('cancelEditBtn').addEventListener('click', function() {
        // Switch back to display mode
        document.getElementById('notulenDisplaySection').style.display = 'block';
        document.getElementById('notulenDisplayFooter').style.display = 'flex';
        document.getElementById('notulenFormSection').style.display = 'none';
        document.getElementById('notulenFormFooter').style.display = 'none';
        document.getElementById('cancelEditBtn').style.display = 'none';
    });

    document.getElementById('addNotulenFileBtn').addEventListener('click', function() {
        const container = document.getElementById('notulenFilesContainer');
        const idx = container.children.length;
        const wrapper = document.createElement('div');
        wrapper.className = 'input-group mb-2';
        wrapper.id = 'notulen-file-row-' + idx;

        const input = document.createElement('input');
        input.type = 'file';
        input.name = 'files[]';
        input.className = 'form-control';

        const btnGroup = document.createElement('button');
        btnGroup.type = 'button';
        btnGroup.className = 'btn btn-outline-danger';
        btnGroup.textContent = 'Hapus';
        btnGroup.onclick = function() {
            container.removeChild(wrapper);
        };

        wrapper.appendChild(input);
        wrapper.appendChild(btnGroup);
        container.appendChild(wrapper);
    });

    function deleteNotulenFile(fileId, fileName) {
        Swal.fire({
            title: 'Hapus Foto?',
            text: 'Foto akan dihapus secara permanen.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`{{ url('surat-masuk-notulen-file-delete') }}/${fileId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            Swal.fire('Dihapus!', 'Foto berhasil dihapus', 'success');
                            // Refresh the modal display
                            const noAgenda = document.getElementById('notulenForm').action.split('/').pop();
                            if (currentEditId) {
                                fetch(`{{ url('surat-masuk-notulen-data') }}/${noAgenda}`)
                                    .then(response => response.json())
                                    .then(refreshData => {
                                        if (refreshData.status === 'success' && refreshData.notulen) {
                                            const filesDiv = document.getElementById('displayNotulenFiles');
                                            if (refreshData.notulen.files && refreshData.notulen.files.length > 0) {
                                                filesDiv.innerHTML = '<div class="row">';
                                                refreshData.notulen.files.forEach(file => {
                                                    filesDiv.innerHTML += `
                                                    <div class="col-md-3 mb-2">
                                                        <div style="position: relative; display: inline-block; width: 100%;">
                                                            <a href="{{ url('storage') }}/notulen_files/${file.file}" target="_blank">
                                                                <img src="{{ url('storage') }}/notulen_files/${file.file}" style="width: 100%; height: auto; border-radius: 4px;" class="img-thumbnail">
                                                            </a>
                                                            <button type="button" class="btn btn-sm btn-danger" style="position: absolute; top: 5px; right: 5px;" onclick="deleteNotulenFile(${file.id}, '${file.file}')">×</button>
                                                        </div>
                                                    </div>
                                                `;
                                                });
                                                filesDiv.innerHTML += '</div>';
                                            } else {
                                                filesDiv.innerHTML = '<p class="text-muted">Tidak ada foto kegiatan</p>';
                                            }
                                        }
                                    });
                            }
                        } else {
                            Swal.fire('Gagal', data.message || 'Gagal menghapus foto', 'error');
                        }
                    })
                    .catch(error => {
                        Swal.fire('Error', 'Terjadi kesalahan saat menghapus foto', 'error');
                    });
            }
        });
    }

    document.getElementById('notulenForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const form = this;
        const formData = new FormData();
        formData.append('_token', '{{ csrf_token() }}');
        formData.append('notulen', document.getElementById('notulenContent').value);

        // Append file_dokument if a file is selected
        const fileInput = document.getElementById('file_dokument');
        if (fileInput.files && fileInput.files.length) {
            formData.append('file_dokument', fileInput.files[0]);
        }

        // collect files from inputs
        const container = document.getElementById('notulenFilesContainer');
        const inputs = container.querySelectorAll('input[type="file"]');
        inputs.forEach((inp) => {
            if (inp.files && inp.files.length) {
                for (let i = 0; i < inp.files.length; i++) {
                    formData.append('files[]', inp.files[i]);
                }
            }
        });

        // Add _method for PUT request if editing
        if (currentEditId) {
            formData.append('_method', 'PUT');
        }

        fetch(form.action, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                console.log('Success:', data);
                if (data.status === 'success') {
                    const message = currentEditId ? 'Notulen berhasil diperbarui' : 'Notulen berhasil disimpan';
                    Swal.fire('Sukses', message, 'success');
                    const modalEl = document.getElementById('notulenModal');
                    const modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
                    modal.hide();
                    $('#dataTable').DataTable().ajax.reload();
                    currentEditId = null;
                } else {
                    Swal.fire('Gagal', data.message || 'Gagal menyimpan', 'error');
                }
            })
            .catch(error => {
                Swal.fire('Error', 'Terjadi kesalahan', 'error');
            });
    });

    function deleteSurat(noAgenda) {
        const url = `{{ url('surat-masuk-delete') }}/${noAgenda}`;
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
    document.getElementById('filterBtn').addEventListener('click', function() {
        const startDate = document.getElementById('startDate').value;
        const endDate = document.getElementById('endDate').value;
        $('#dataTable').DataTable().ajax.url(`{{route('surat-masuk-data')}}?startDate=${startDate}&endDate=${endDate}`).load();
    });

    document.getElementById('resetBtn').addEventListener('click', function() {
        document.getElementById('filterForm').reset();
        $('#dataTable').DataTable().ajax.url(`{{route('surat-masuk-data')}}`).load();
    });
</script>
<script>
    // Dropzone Configuration
    Dropzone.autoDiscover = false;

    var uploadedFiles = [];
    var totalFileSize = 0;

    var myDropzone = new Dropzone('#myDropzone', {
        maxFilesize: 2,
        acceptedFiles: '.pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png,.gif',
        addRemoveLinks: true,
        paramName: 'file',
        success: function(file, response) {
            // the controller returns JSON with `id` at top level
            var fileId = response.id;
            file.serverFileId = fileId;
            uploadedFiles.push({
                name: file.name,
                size: file.size,
                path: '{{config("app.url")}}' +  '/storage/' + response.path || file.name,
                id: fileId
            });
            updateFilesList(uploadedFiles);
        },
        error: function(file, errorMessage) {
            file.previewElement.parentNode.removeChild(file.previewElement);
            Swal.fire('Error', 'Gagal upload file: ' + errorMessage, 'error');
            console.error('Upload error:', errorMessage);
        },
        removedfile: function(file) {
            var fileId = file.serverFileId;

            // Only send delete request if not clearing for fresh load
            if (fileId && !isClearing) {
                fetch("{{ url('delete-file') }}/" + fileId, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire('Sukses', 'File berhasil dihapus', 'success');
                        }
                    })
                    .catch(error => {
                        Swal.fire('Error', 'Gagal menghapus file', 'error');
                    });
            }

            // Remove from uploaded files array
            uploadedFiles = uploadedFiles.filter(f => f.id !== fileId);

            if (file.previewElement) {
                file.previewElement.parentNode.removeChild(file.previewElement);
            }
            updateFilesList(uploadedFiles);
        }
    });

    // hide show dz-message based on number of files
    myDropzone.on('addedfile', function() {
        var msg = document.querySelector('#myDropzone .dz-message');
        if (msg) msg.classList.add('hidden');
    });
    myDropzone.on('removedfile', function() {
        var msg = document.querySelector('#myDropzone .dz-message');
        if (msg && myDropzone.files.length === 0) msg.classList.remove('hidden');
    });

    function updateFilesList(uploadedFiles) {
        const uploadPreview = document.getElementById('uploadPreview');
        const uploadStats = document.getElementById('uploadStats');
        const filesList = document.getElementById('filesList');

        // hide dropzone message when files exist
        var msg = document.querySelector('#myDropzone .dz-message');
        if (msg) {
            if (myDropzone.files.length > 0) {
                msg.classList.add('hidden');
            } else {
                msg.classList.remove('hidden');
            }
        }

        if (myDropzone.files.length === 0) {
            uploadPreview.style.display = 'none';
            uploadStats.style.display = 'none';
            return;
        }

        uploadPreview.style.display = 'block';
        uploadStats.style.display = 'block';

        let html = '';
        totalFileSize = 0;
        uploadedFiles.forEach((file, index) => {
            totalFileSize += file.size;
            html += `
                <div class="d-flex justify-content-between align-items-center p-2 mb-2 bg-white border rounded">
                    <div class="d-flex align-items-center gap-2">
                        <i class="${getFileIcon(file.name)}"></i>
                        <div>
                            <small><strong>${file.name}</strong></small><br>
                            <small class="text-muted">${formatFileSize(file.size)}</small>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <a href="${file.path}" target="_blank" class="btn btn-sm btn-outline-primary">View</a>
                        <i class="fas fa-check-circle text-success"></i>
                    </div>
                </div>
            `;
        });

        filesList.innerHTML = html;
        totalFilesCount.textContent = myDropzone.files.length;
        totalSizeCount.textContent = formatFileSize(totalFileSize);
    }

    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
    }

    function getFileIcon(filename) {
        const ext = filename.split('.').pop().toLowerCase();
        const iconMap = {
            'pdf': 'fas fa-file-pdf',
            'doc': 'fas fa-file-word',
            'docx': 'fas fa-file-word',
            'xls': 'fas fa-file-excel',
            'xlsx': 'fas fa-file-excel',
            'jpg': 'fas fa-file-image',
            'jpeg': 'fas fa-file-image',
            'png': 'fas fa-file-image',
            'gif': 'fas fa-file-image'
        };
        return iconMap[ext] || 'fas fa-file';
    }

    // Populate modal with existing uploaded files when opened (index page)
    var existingLoaded = false;
    var currentAgenda = null;
    var isClearing = false;

    $(document).on('click', '.add-file-btn', function() {
        currentAgenda = $(this).data('id');
        existingLoaded = false;
        document.getElementById('noAgendaInput').value = currentAgenda;
    });

    document.getElementById('exampleModal').addEventListener('shown.bs.modal', function() {
        if (existingLoaded || !currentAgenda) return;

        // Set flag to skip server delete on removedfile callback
        isClearing = true;
        myDropzone.removeAllFiles(false);
        isClearing = false;
        uploadedFiles = [];
        totalFileSize = 0;

        fetch(`{{ url('surat-masuk-files') }}/${currentAgenda}`)
            .then(r => r.json())
            .then(data => {

                if (data.status === 'success' && data.files) {
                    data.files.forEach(function(f) {
                        var name = f.original_name || f.file;
                        var size = Number(f.file_size) || 0;
                        var mockFile = {
                            name: name,
                            size: size,
                            serverFileId: f.id,
                            path: '{{config("app.url")}}' + '/storage/uploads/' + f.file
                        };

                        myDropzone.emit('addedfile', mockFile);
                        myDropzone.emit('complete', mockFile);
                        if (/(jpe?g|png|gif)$/i.test(name)) {
                            myDropzone.emit('thumbnail', mockFile, '{{config("app.url")}}' + '/storage/uploads/' + f.file);
                        }
                        myDropzone.files.push(mockFile);
                        uploadedFiles.push({
                            name: name,
                            size: size,
                            path: '{{config("app.url")}}' + '/storage/uploads/' + f.file,
                            id: f.id
                        });
                        totalFileSize += size;
                    });
                    updateFilesList(uploadedFiles);
                    existingLoaded = true;
                }
            })
            .catch(err => console.error('fetch files error', err));
    });
</script>
@endpush
