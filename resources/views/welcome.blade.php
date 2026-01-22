@extends('templates.dashboard-layout')
@section('content')

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
                            <p>Surat Masuk</p>
                        </div> <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path d="M5.5 5.1L2 12v6c0 1.1.9 2 2 2h16a2 2 0 002-2v-6l-3.4-6.9A2 2 0 0016.8 4H7.2a2 2 0 00-1.8 1.1z" />
                        </svg> <a href="{{route('surat-masuk')}}" class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
                            More info <i class="bi bi-link-45deg"></i> </a>

                    </div> <!--end::Small Box Widget 1-->
                </div> <!--end::Col-->
                <div class="col-lg-3 col-6"> <!--begin::Small Box Widget 2-->
                    <div class="small-box text-bg-success">
                        <div class="inner">
                            <h3>{{count($data['surat_keluar'])}}<h3>
                                    <p>Surat Keluar</p>
                        </div>
                        <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z"></path>
                        </svg>
                        <a href="#" class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
                            More info <i class="bi bi-link-45deg"></i> </a>
                    </div> <!--end::Small Box Widget 2-->
                </div> <!--end::Col-->
                <div class="col-lg-3 col-6"> <!--begin::Small Box Widget 3-->
                    <div class="small-box text-bg-warning">
                        <div class="inner">
                            <h3>{{count($data['surat_belum_disposisi'])}}</h3>
                            <p>Belum Disposisi</p>
                        </div> <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path>
                        </svg> <a href="#" class="small-box-footer link-dark link-underline-opacity-0 link-underline-opacity-50-hover">
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
                        </svg> <a href="#" class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
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
                        <table class="table table-bordered">
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
                                @foreach ($data['surat_masuk'] as $item)
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
                                    <td>{{$item->disposisi_all}}</td>
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
                        <h3 class="card-title">Surat Keluar Terbaru</h3>
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
                                    <td>{{$item->disposisi_all}}</td>
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
@endsection
@push('js')
<script>
    const ctx = document.getElementById('chartSurat').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agt', 'Sep', 'Okt', 'Nov', 'Des'],
            datasets: [{
                    label: 'Surat Masuk',
                    data: {{$data['grafik_surat_masuk']}},
                    backgroundColor: '#60A5FA'
                },
                {
                    label: 'Surat Keluar',
                    data: {{$data['grafik_surat_keluar']}},
                    backgroundColor: '#34D399'
                }
            ]
        }
    });
</script>
@endpush