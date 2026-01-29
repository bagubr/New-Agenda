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
                        var html = '';
                        const date = new Date(row.tgl_agenda);
                        if(date <= new Date()){
                            html += '<a href="#" class="badge bg-success" onclick="openNotulenModal(\''+data+'\')" data-bs-toggle="modal" data-bs-target="#notulenModal">Notulen</a>';
                        }else{
                            html += '<a href="#" class="badge bg-secondary">Notulen</a>';
                        }
                        html += `
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
    let currentEditId = null;
    
    // Dynamic file inputs
    function openNotulenModal(noAgenda){
        document.getElementById('notulenForm').action = `{{ url('surat-keluar-notulen') }}/${noAgenda}`;
        
        // Fetch existing notulen data
        fetch(`{{ url('surat-keluar-notulen-data') }}/${noAgenda}`)
            .then(response => response.json())
            .then(data => {
                console.log('Notulen Data:', data);
                if(data.status === 'success' && data.notulen){
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
                    if(data.notulen.filename){
                        fileDocDiv.innerHTML = `
                            <a href="{{ url('storage') }}/notulen_keluar/${data.notulen.filename}" target="_blank" class="badge bg-primary">
                                📄 ${data.notulen.original_name}
                            </a>
                        `;
                    }
                    
                    // Display notulen files
                    const filesDiv = document.getElementById('displayNotulenFiles');
                    if(data.notulen.files && data.notulen.files.length > 0){
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
                    }else{
                        filesDiv.innerHTML = '<p class="text-muted">Tidak ada foto kegiatan</p>';
                    }
                    
                    document.getElementById('displayUser').textContent = data.notulen.user;
                    document.getElementById('displayDate').textContent = new Date(data.notulen.tgin).toLocaleString('id-ID');
                }else{
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
    
    document.getElementById('editNotulenBtn').addEventListener('click', function(){
        // Switch to edit mode
        document.getElementById('notulenDisplaySection').style.display = 'none';
        document.getElementById('notulenDisplayFooter').style.display = 'none';
        document.getElementById('notulenFormSection').style.display = 'block';
        document.getElementById('notulenFormFooter').style.display = 'flex';
        document.getElementById('cancelEditBtn').style.display = 'inline-block';
        
        // Populate form with current data
        document.getElementById('notulenContent').value = document.getElementById('displayNotulenContent').textContent;
        document.getElementById('notulenForm').action = `{{ url('surat-keluar-notulen-update') }}/${currentEditId}`;
    });
    
    document.getElementById('cancelEditBtn').addEventListener('click', function(){
        // Switch back to display mode
        document.getElementById('notulenDisplaySection').style.display = 'block';
        document.getElementById('notulenDisplayFooter').style.display = 'flex';
        document.getElementById('notulenFormSection').style.display = 'none';
        document.getElementById('notulenFormFooter').style.display = 'none';
        document.getElementById('cancelEditBtn').style.display = 'none';
    });

    document.getElementById('addNotulenFileBtn').addEventListener('click', function(){
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
        btnGroup.onclick = function(){ container.removeChild(wrapper); };

        wrapper.appendChild(input);
        wrapper.appendChild(btnGroup);
        container.appendChild(wrapper);
    });

    function deleteNotulenFile(fileId, fileName){
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
                fetch(`{{ url('surat-keluar-notulen-file-delete') }}/${fileId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if(data.status === 'success'){
                        Swal.fire('Dihapus!', 'Foto berhasil dihapus', 'success');
                        // Refresh the modal display
                        const noAgenda = document.getElementById('notulenForm').action.split('/').pop();
                        if(currentEditId) {
                            fetch(`{{ url('surat-keluar-notulen-data') }}/${noAgenda}`)
                                .then(response => response.json())
                                .then(refreshData => {
                                    if(refreshData.status === 'success' && refreshData.notulen){
                                        const filesDiv = document.getElementById('displayNotulenFiles');
                                        if(refreshData.notulen.files && refreshData.notulen.files.length > 0){
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
                                        }else{
                                            filesDiv.innerHTML = '<p class="text-muted">Tidak ada foto kegiatan</p>';
                                        }
                                    }
                                });
                        }
                    }else{
                        Swal.fire('Gagal', data.message || 'Gagal menghapus foto', 'error');
                    }
                })
                .catch(error => {
                    Swal.fire('Error', 'Terjadi kesalahan saat menghapus foto', 'error');
                });
            }
        });
    }

    document.getElementById('notulenForm').addEventListener('submit', function(e){
        e.preventDefault();
        const form = this;
        const formData = new FormData();
        formData.append('_token', '{{ csrf_token() }}');
        formData.append('notulen', document.getElementById('notulenContent').value);
        
        // Append file_dokument if a file is selected
        const fileInput = document.getElementById('file_dokument');
        if(fileInput.files && fileInput.files.length){
            formData.append('file_dokument', fileInput.files[0]);
        }

        // collect files from inputs
        const container = document.getElementById('notulenFilesContainer');
        const inputs = container.querySelectorAll('input[type="file"]');
        inputs.forEach((inp) => {
            if(inp.files && inp.files.length){
                for(let i=0;i<inp.files.length;i++){
                    formData.append('files[]', inp.files[i]);
                }
            }
        });

        // Add _method for PUT request if editing
        if(currentEditId){
            formData.append('_method', 'PUT');
        }

        fetch(form.action, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            console.log('Success:', data);
            if(data.status === 'success'){
                const message = currentEditId ? 'Notulen berhasil diperbarui' : 'Notulen berhasil disimpan';
                Swal.fire('Sukses', message, 'success');
                const modalEl = document.getElementById('notulenModal');
                const modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
                modal.hide();
                $('#dataTable').DataTable().ajax.reload();
                currentEditId = null;
            }else{
                Swal.fire('Gagal', data.message || 'Gagal menyimpan', 'error');
            }
        })
        .catch(error => {
            Swal.fire('Error', 'Terjadi kesalahan', 'error');
        });
    });

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
</script>
@endpush
