@extends('templates.dashboard-layout')
@section('content')
<main class="app-main"> <!--begin::App Content Header-->
    <div class="app-content-header"> <!--begin::Container-->
        <div class="container-fluid"> <!--begin::Row-->
            <div class="row">
                @include('templates.alert')
                <div class="col-sm-6">
                    <h3 class="mb-0">Disposisi</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item" aria-current="page">
                            Disposisi
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Tabel Disposisi
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
                        <h3 class="card-title">Tabel Disposisi</h3>
                        <div class="card-tools">
                            <a href="{{ route('disposisi.create') }}" class="btn btn-sm btn-success">
                                <i class="fas fa-plus"></i> Tambah
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Devisi</th>
                                        <th>Role</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($disposisis as $index => $disposisi)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $disposisi->disposisi }}</td>
                                            <td>{{ $disposisi->role }}</td>
                                            <td>
                                                @if($disposisi->aktif == 1)
                                                    <span class="badge bg-success">Aktif</span>
                                                @else
                                                    <span class="badge bg-danger">Tidak Aktif</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('disposisi.edit', $disposisi->id) }}" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                                <a href="{{ route('disposisi.destroy', $disposisi->id) }}" class="btn btn-sm btn-danger" onclick="event.preventDefault(); if(confirm('Apakah Anda yakin ingin menghapus disposisi ini?')) { document.getElementById('delete-form-{{ $disposisi->id }}').submit(); }">
                                                    <i class="fas fa-trash"></i> Hapus
                                                </a>
                                                <form id="delete-form-{{ $disposisi->id }}" action="{{ route('disposisi.destroy', $disposisi->id) }}" method="POST" style="display: none;">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center">Tidak ada data disposisi.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div> <!--end::Row-->
    </div> <!--end::Container-->
</main> <!--end::App Content-->
@endsection
                    