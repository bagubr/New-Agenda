@extends('templates.dashboard-layout')
@section('content')
<main class="app-main"> <!--begin::App Content Header-->
    <div class="app-content-header"> <!--begin::Container-->
        <div class="container-fluid"> <!--begin::Row-->
            <div class="row">
                @include('templates.alert')
                <div class="col-sm-6">
                    <h3 class="mb-0">Ruang Rapat</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item" aria-current="page">
                            Pengaturan
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Ruang Rapat
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
                        <h3 class="card-title">Ruang Rapat</h3>
                        <div class="card-tools">
                            <a href="{{route('ruang-rapat.create')}}" class="btn btn-primary btn-sm">Tambah</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered" id="dataTable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Ruang Rapat</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($ruang_rapat as $data)
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$data->ruangrapat}}</td>
                                    <td>
                                        <a href="{{route('ruang-rapat.edit', $data->id)}}" class="btn btn-warning btn-sm text-white">Edit</a>
                                        <form action="{{route('ruang-rapat.destroy', $data->id)}}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                            @method('DELETE')
                                            @csrf
                                            <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div> <!--end::Row-->
    </div> <!--end::Container-->
</main>
@endsection