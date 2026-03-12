@extends('templates.dashboard-layout')
@section('content')
<main class="app-main"> <!--begin::App Content Header-->
    <div class="app-content-header"> <!--begin::Container-->
        <div class="container-fluid"> <!--begin::Row-->
            <div class="row">
                @include('templates.alert')
                <div class="col-sm-6">
                    <h3 class="mb-0">Tambah Asal Surat</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item" aria-current="page">
                            Asal Surat
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
                    </div>
                    <!--begin::Form-->
                    <form action="{{route('asal.post')}}" method="POST">
                        @csrf
                        <!--begin::Body-->
                        <div class="card-body">
                            <div class="mb-1">
                                <label for="name" class="form-label">Nama Asal</label>
                                <input type="text" name="name" class="form-control" id="name" required/>
                            </div>
                            <div class="mb-1">
                                <label for="kode" class="form-label">Kode</label>
                                <input type="text" name="kode" class="form-control" id="kode" required/>
                            </div>
                        </div> <!--end::Body--> <!--begin::Footer-->
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <a href="{{route('asal')}}" class="btn btn-secondary">Kembali</a>
                        </div> <!--end::Footer-->
                    </form> <!--end::Form-->
                </div>
            </div>
        </div> <!--end::Row-->
    </div> <!--end::Container-->
</main>
@endsection