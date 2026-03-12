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
                    <form action="{{route('ruang-rapat.post')}}" method="POST">
                        @csrf
                        <!--begin::Body-->
                        <div class="card-body">
                            <div class="mb-1">
                                <label for="ruangrapat" class="form-label">Nama Ruang Rapat</label>
                                <input type="text" name="ruangrapat" class="form-control" id="ruangrapat" required/>
                            </div>
                        </div> <!--end::Body--> <!--begin::Footer-->
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <a href="{{route('ruang-rapat')}}" class="btn btn-secondary">Kembali</a>
                        </div> <!--end::Footer-->
                    </form> <!--end::Form-->
                </div>
            </div>
        </div> <!--end::Row-->
    </div> <!--end::Container-->
</main>
@endsection