@extends('templates.dashboard-layout')

@section('content')

<main class="app-main"> <!--begin::App Content Header-->
    <div class="app-content-header"> <!--begin::Container-->
        <div class="container-fluid"> <!--begin::Row-->
            <div class="row">
                @include('templates.alert')
                <div class="col-sm-6">
                    <h3 class="mb-0">Laporan</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item active" aria-current="page">
                            Laporan
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
                        <h3 class="card-title">Laporan</h3>
                        <div class="card-tools">
                            <a href="{{route('laporan.export')}}" class="btn btn-success d-none" id="exportBtn">Export</a>
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
                                <label for="bulan" class="form-label mb-0">Bulan</label>
                                <select name="month" id="bulan" class="form-select">
                                    <option value="">PILIH</option>
                                    <option value="1">Januari</option>
                                    <option value="2">Februari</option>
                                    <option value="3">Maret</option>
                                    <option value="4">April</option>
                                    <option value="5">Mei</option>
                                    <option value="6">Juni</option>
                                    <option value="7">Juli</option>
                                    <option value="8">Agustus</option>
                                    <option value="9">September</option>
                                    <option value="10">Oktober</option>
                                    <option value="11">November</option>
                                    <option value="12">Desember</option> 
                                </select>
                            </div>
                            <div class="col-auto">
                                <label for="tahun" class="form-label mb-0">Tahun</label>
                                <select name="year" id="tahun" class="form-select">
                                    <option value="">PILIH</option>
                                    <option value="2022">2022</option>
                                    <option value="2023">2023</option>
                                    <option value="2024">2024</option>
                                    <optgroup label="Tahun Lainnya">
                                        @for ($year = 2025; $year <= date('Y') + 5; $year++)
                                            <option value="{{ $year }}">{{ $year }}</option>
                                        @endfor
                                    </optgroup>
                                </select>
                            </div>
                            <div class="col-auto">
                                <label for="filterBtn" class="form-label mb-0">Jenis</label>
                                <select name="jenis" id="jenisFilter" class="form-select">
                                    <option value="">PILIH</option>
                                    <option value="1">Undangan</option>
                                    <option value="2">Non Undangan</option>
                                    <option value="3">Usulan Pembangunan</option>
                                </select>
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
                url: "{{route('surat-masuk-data')}}",
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
                    data: 'time',
                    title: 'Tanggal Input',
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
                }
            ]
        });
    });
</script>
<script>
    // Filter functionality
    document.getElementById('filterBtn').addEventListener('click', function() {
        const startDate = document.getElementById('startDate').value;
        const endDate = document.getElementById('endDate').value;
        const month = document.getElementById('bulan').value;
        const year = document.getElementById('tahun').value;
        const jenis = document.getElementById('jenisFilter').value;
        document.getElementById('exportBtn').classList.remove('d-none');
        document.getElementById('exportBtn').href = `{{route('laporan.export')}}?startDate=${startDate}&endDate=${endDate}&month=${month}&year=${year}&jenis=${jenis}`;
        $('#dataTable').DataTable().ajax.url(`{{route('surat-masuk-data')}}?startDate=${startDate}&endDate=${endDate}&month=${month}&year=${year}&jenis=${jenis}`).load();
    });

    document.getElementById('resetBtn').addEventListener('click', function() {
        document.getElementById('exportBtn').classList.add('d-none');
        document.getElementById('filterForm').reset();
        $('#dataTable').DataTable().ajax.url(`{{route('surat-masuk-data')}}`).load();
    });
</script>
@endpush