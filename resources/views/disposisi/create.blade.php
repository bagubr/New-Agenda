@extends('templates.dashboard-layout')
@section('content')
<main class="app-main"> <!--begin::App Content Header-->
    <div class="app-content-header"> <!--begin::Container-->
        <div class="container-fluid"> <!--begin::Row-->
            <div class="row">
                @include('templates.alert')
                <div class="col-sm-6">
                    <h3 class="mb-0">Tambah Disposisi</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item" aria-current="page">
                            Disposisi
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Tambah Disposisi
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
                        <h3 class="card-title">Form Tambah Disposisi</h3>
                    </div>
                    <!--begin::Form-->
                    <form action="{{route('disposisi.store')}}" method="POST">
                        @csrf
                        <!--begin::Body-->
                        <div class="card-body">
                            <div class="mb-1">
                                <label for="disposisi" class="form-label">Disposisi</label>
                                <input type="text" name="disposisi" class="form-control" id="disposisi" required/>
                            </div>
                            <div class="mb-1">
                                <label for="role" class="form-label">Role</label>
                                <select name="role" id="role" class="form-control" required>
                                    <option value="">Pilih Role</option>
                                    <option value="admin">Admin</option>
                                    <option value="kepala_dinas">Kepala Dinas</option>
                                    <option value="kepala_bidang">Kepala Bidang</option>
                                    <option value="ketua_tim">Ketua Tim</option>
                                    <option value="user">User</option>
                                </select>
                            </div>
                            <div class="mb-1">
                                <label for="aktif" class="form-label">Status</label>
                                <select name="aktif" id="aktif" class="form-control" required>
                                    <option value="">Pilih Status</option>
                                    <option value="1">Aktif</option>
                                    <option value="0">Tidak Aktif</option>
                                </select>
                            </div>
                        </div>
                        <!--end::Body-->
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <a href="{{route('disposisi.index')}}" class="btn btn-secondary">Batal</a>
                        </div>
                    </form>
                    <!--end::Form-->
                </div>
            </div>
        </div> <!--end::Row-->
    </div> <!--end::Container-->
</main>
@endsection