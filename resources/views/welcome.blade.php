@extends('templates.dashboard-layout')
@section('content')
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

<main class="app-main"> <!--begin::App Content Header-->
    <div class="app-content-header"> <!--begin::Container-->
        <div class="container-fluid"> <!--begin::Row-->
            <div class="row">
                @include('templates.alert')
                <div class="col-sm-6">
                    <h3 class="mb-0">Dashboard</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item active" aria-current="page">
                            Dashboard
                        </li>
                    </ol>
                </div>
            </div> <!--end::Row-->
        </div> <!--end::Container-->
    </div> <!--end::App Content Header--> <!--begin::App Content-->
    <div class="app-content"> <!--begin::Container-->
        <div class="container-fluid"> <!--begin::Row-->
            <div class="row"> <!--begin::Col-->
                <div class="col-lg-3 col-6"> <!--begin::Small Box Widget 1-->
                    <div class="small-box text-bg-primary">
                        <div class="inner">
                            <h3>{{count($data['surat_masuk'])}}</h3>
                            <p>Total Surat Masuk Hari ini</p>
                        </div> <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path d="M5.5 5.1L2 12v6c0 1.1.9 2 2 2h16a2 2 0 002-2v-6l-3.4-6.9A2 2 0 0016.8 4H7.2a2 2 0 00-1.8 1.1z" />
                        </svg> <a href="{{route('surat-masuk')}}" class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
                            Daftar Surat Masuk <i class="bi bi-link-45deg"></i> </a>

                    </div> <!--end::Small Box Widget 1-->
                </div> <!--end::Col-->
                <div class="col-lg-3 col-6"> <!--begin::Small Box Widget 2-->
                    <div class="small-box text-bg-success">
                        <div class="inner">
                            <h3>{{count($data['surat_keluar'])}}
                                <h3>
                                    <p>Total Surat Keluar Hari ini</p>
                        </div>
                        <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z"></path>
                        </svg>
                        <a href="{{route('surat-keluar')}}" class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
                            Daftar Surat Keluar <i class="bi bi-link-45deg"></i> </a>
                    </div> <!--end::Small Box Widget 2-->
                </div> <!--end::Col-->
                <div class="col-lg-3 col-6"> <!--begin::Small Box Widget 3-->
                    <div class="small-box text-bg-warning">
                        <div class="inner">
                            <h3>{{count($data['surat_belum_disposisi'])}}</h3>
                            <p>Belum Disposisi</p>
                        </div> <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path>
                        </svg> <a href="{{route('belum-disposisi')}}" class="small-box-footer link-dark link-underline-opacity-0 link-underline-opacity-50-hover">
                            More info <i class="bi bi-link-45deg"></i> </a>
                    </div> <!--end::Small Box Widget 3-->
                </div> <!--end::Col-->
                <div class="col-lg-3 col-6"> <!--begin::Small Box Widget 4-->
                    <div class="small-box text-bg-danger">
                        <div class="inner">
                            <h3>{{count($data['surat_selesai'])}}</h3>
                            <p>Surat Terlewat</p>
                        </div> <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path d="M22 17H2a3 3 0 0 0 3-3V9a7 7 0 0 1 14 0v5a3 3 0 0 0 3 3zm-8.27 4a2 2 0 0 1-3.46"></path>
                            <circle cx="12" cy="19" r="2"></circle>
                        </svg> <a href="{{route('surat-terlewat')}}" class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
                            More info <i class="bi bi-link-45deg"></i> </a>
                    </div> <!--end::Small Box Widget 4-->
                </div> <!--end::Col-->
            </div> <!--end::Row-->
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-header">
                        <h3 class="card-title">Surat Masuk Hari ini</h3>
                    </div> <!-- /.card-header -->
                    <div class="card-body">
                        <table class="table table-bordered table-responsive" id="dataTable">
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
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-header">
                        <h3 class="card-title">Surat Keluar Hari ini</h3>
                    </div> <!-- /.card-header -->
                    <div class="card-body">
                        <table id="datatable" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Jenis</th>
                                    <th>Asal</th>
                                    <th>Tanggal</th>
                                    <th>Nomor</th>
                                    <th>Perihal</th>
                                    <th>Tanggal Agenda</th>
                                    <th>Disposisi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data['surat_keluar'] as $item)
                                <tr>
                                    <td style="width: 2%;">{{$loop->iteration}}</td>
                                    <td style="width: 5%;">{{$item->jenis}}</td>
                                    <td style="width: 10%;">{{$item->asal}}</td>
                                    <td style="width: 5%;">{{Carbon\Carbon::parse($item->tanggal)->translatedFormat('d F Y');}}</td>
                                    <td style="width: 10%;">{{$item->no_surat}}</td>
                                    <td>{!!$item->perihal!!}</td>
                                    <td style="width: 30%;">({{$item->jam}}) {{Carbon\Carbon::parse($item->tgl_agenda)->translatedFormat('l, d F Y')}}
                                        <hr>
                                        Tempat : {{$item->tmpt}}
                                        <hr>
                                        Acara : {{$item->acara}}
                                    </td>
                                    <td>{{@$item->disposisi_all}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div> <!-- /.card-body -->
                </div> <!-- /.card -->
            </div>
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-header">
                        <h3 class="card-title">Grafik Surat</h3>
                    </div> <!-- /.card-header -->
                    <canvas id="chartSurat" class="w-full h-48 mt-6"></canvas>
                </div>
            </div>
        </div> <!--end::Container-->
    </div> <!--end::App Content-->
</main> <!--end::App Main--> <!--begin::Footer-->

                                <!-- Modal (Files upload) - only for superadmin -->
                                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel"><i class="fas fa-cloud-upload-alt"></i> Upload File</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="{{ route('upload-file') }}" class="dropzone" id="myDropzone">
                                                    <input type="hidden" id="noAgendaInput" name="no_agenda">
                                                    @if(Auth::check() && Auth::user()->role === 'superadmin')
                                                    @csrf
                                                    <div class="dz-message needsclick">
                                                        <i class="fas fa-cloud-upload-alt" style="font-size: 48px; color: #007bff;"></i>
                                                        <h6 class="mt-3"><strong>Tarik file ke sini atau klik untuk memilih</strong></h6>
                                                        <span class="text-muted" style="font-size: 12px;">Tipe file: PDF, DOC, DOCX, XLS, XLSX, JPG, PNG, GIF<br>Ukuran max: 10MB per file</span>
                                                    </div>
                                                    @endif
                                                </form>

                                                <div id="uploadPreview" class="mt-3" style="display: none;">
                                                    <h6>File yang Terupload:</h6>
                                                    <div id="filesList" class="border rounded p-2 bg-light" style="max-height: 250px; overflow-y: auto;">
                                                    </div>
                                                </div>

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
<script>
    $(document).ready(function() {
        $('#dataTable').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            iDisplayLength: 10,
            ajax: {
                url: "{{route('data-dashboard')}}",
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
                        return formatted + ' <b>(' + row.jam + ')</b> <hr> Tempat : ' + row.tmpt + '<hr>Acara : ' + row.acara;
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
    const ctx = document.getElementById('chartSurat').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agt', 'Sep', 'Okt', 'Nov', 'Des'],
            datasets: [{
                    label: 'Surat Masuk',
                    data: @json($data['grafik_surat_masuk']),
                    backgroundColor: '#60A5FA'
                },
                {
                    label: 'Surat Keluar',
                    data: @json($data['grafik_surat_keluar']),
                    backgroundColor: '#34D399'
                }
            ]
        }
    });
</script>
    <script src="https://cdn.jsdelivr.net/npm/dropzone@6.0.0-beta.1/dist/dropzone-min.js"></script>
    <script>
        // Dropzone Configuration
        var isSuperAdmin = @json(Auth::check() && Auth::user()->role === 'superadmin');
        Dropzone.autoDiscover = false;

        var uploadedFiles = [];
        var totalFileSize = 0;

        var myDropzone = new Dropzone('#myDropzone', {
            maxFilesize: 10,
            acceptedFiles: '.pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png,.gif',
            addRemoveLinks: isSuperAdmin,
            clickable: isSuperAdmin,
            paramName: 'file',
            success: function(file, response) {
                var fileId = response.id;
                file.serverFileId = fileId;
                uploadedFiles.push({
                    name: file.name,
                    size: file.size,
                    path: 'storage/' + response.path || file.name,
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
                // if (!isSuperAdmin) {
                    // Prevent non-superadmin from removing files client-side
                //     Swal.fire('Tidak diizinkan', 'Anda tidak berhak menghapus file.', 'error');
                //     return;
                // }
                var fileId = file.serverFileId;

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
                            console.error('Delete error:', error);
                            Swal.fire('Error', 'Gagal menghapus file', 'error');
                        });
                }

                uploadedFiles = uploadedFiles.filter(f => f.id !== fileId);

                if (file.previewElement) {
                    file.previewElement.parentNode.removeChild(file.previewElement);
                }
                updateFilesList(uploadedFiles);
            }
        });

        // If user is not superadmin, fully disable dropzone uploads and show notice
        if (!isSuperAdmin) {
            try { myDropzone.disable(); } catch (e) {}
            var dzElem = document.getElementById('myDropzone');
            if (dzElem) {
                var notice = document.createElement('div');
                notice.className = 'alert alert-warning mt-2';
                notice.textContent = 'Hanya superadmin yang dapat mengunggah file.';
                dzElem.parentNode.insertBefore(notice, dzElem.nextSibling);
            }
        }

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

        var existingLoaded = false;
        var currentAgenda = null;
        var isClearing = false;

        $(document).on('click', '.add-file-btn', function() {
            currentAgenda = $(this).data('id');
            console.log(currentAgenda);
            existingLoaded = false;
            document.getElementById('noAgendaInput').value = currentAgenda;
        });

        document.getElementById('exampleModal').addEventListener('shown.bs.modal', function() {
            if (existingLoaded || !currentAgenda) return;
            isClearing = true;
            myDropzone.removeAllFiles(false);
            isClearing = false;
            uploadedFiles = [];
            totalFileSize = 0;

            fetch(`{{ url('surat-masuk-files') }}/${currentAgenda}`)
                .then(r => r.json())
                .then(data => {
                    console.log(data);
                    if (data.status === 'success' && data.files) {
                        data.files.forEach(function(f) {
                            var name = f.original_name || f.file;
                            var size = Number(f.file_size) || 0;
                            var mockFile = {
                                name: name,
                                size: size,
                                serverFileId: f.id,
                                path: '/storage/uploads/' + f.file
                            };

                            myDropzone.emit('addedfile', mockFile);
                            myDropzone.emit('complete', mockFile);
                            if (/(jpe?g|png|gif)$/i.test(name)) {
                                myDropzone.emit('thumbnail', mockFile, '/storage/uploads/' + f.file);
                            }
                            myDropzone.files.push(mockFile);
                            uploadedFiles.push({
                                name: name,
                                size: size,
                                path: '/storage/uploads/' + f.file,
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