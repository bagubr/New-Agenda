@extends('templates.dashboard-layout')
@section('content')
<main class="app-main"> <!--begin::App Content Header-->
    <div class="app-content-header"> <!--begin::Container-->
        <div class="container-fluid"> <!--begin::Row-->
            <div class="row">
                @include('templates.alert')
                <div class="col-sm-6">
                    <h3 class="mb-0">Asal Surat</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item" aria-current="page">
                            Asal Surat
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
                        <h3 class="card-title">Data Asal Surat</h3>
                        <div class="card-tools">
                            <a href="{{route('asal.create')}}" class="btn btn-primary mb-3">Tambah</a>
                        </div>
                    </div>
                    <!--begin::Form-->
                    <div class="card-body">
                        <table class="table table-bordered" id="asal-table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Asal</th>
                                    <th>Kode</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($asal as $item)
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$item->name}}</td>
                                    <td>{{$item->kode}}</td>
                                    <td>
                                        <a href="{{route('asal.edit', $item->id)}}" class="btn btn-info text-white">Edit</a>
                                        <form action="{{route('asal.destroy', $item->id)}}" method="POST" style="display: inline-block;">
                                            @method('DELETE')
                                            @csrf
                                            <button type="submit" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </div> <!--end::Row-->
    </div> <!--end::Container-->
</main> <!--end::App Content-->
@endsection