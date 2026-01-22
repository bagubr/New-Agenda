@extends('templates.dashboard-layout')

@section('content')
<main class="app-main"> <!--begin::App Content Header-->
    <div class="app-content-header"> <!--begin::Container-->
        <div class="container-fluid"> <!--begin::Row-->
            <div class="row">
                @include('templates.alert')
                <div class="col-sm-6">
                    <h3 class="mb-0">Profile</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Profile
                        </li>
                    </ol>
                </div>
            </div> <!--end::Row-->
        </div> <!--end::Container-->
    </div> <!--end::App Content Header--> <!--begin::App Content-->
    <div class="app-content"> <!--begin::Container-->
        <div class="container-fluid"> <!--begin::Row-->
            <div class="row g-4"> <!--begin::Col-->
                <div class="col-md-6"> <!--begin::Quick Example-->
                    <div class="card card-primary card-outline mb-4"> <!--begin::Header-->
                        <div class="card-header">
                            <div class="card-title">Akun Anda</div>
                        </div> <!--end::Header--> <!--begin::Form-->
                        <form action="{{route('profile-update', \Auth::user()->id)}}" method="POST" enctype="multipart/form-data"> <!--begin::Body-->
                            @csrf
                            @method('PUT')
                            <div class="card-body">
                                <div class="mb-3"> <label class="form-label">Username</label> <input
                                        type="text" name="username" class="form-control" value="{{\Auth::user()->username}}">
                                </div>
                                <div class="mb-3"> <label class="form-label">NIP</label> <input
                                        type="text" name="nip" class="form-control" value="{{\Auth::user()->nip}}">
                                </div>
                                <label for="exampleInputPassword1" class="form-label">Password</label> 
                                <div class="input-group mb-3">
                                    <input type="password" name="password" class="form-control" id="exampleInputPassword1">
                                    <label for="" id="togglePassword" class="input-group-text">Show</label>
                                </div>
                                <label for="exampleInputPassword2" class="form-label">Konfirmasi Password</label> 
                                <div class="input-group mb-3">
                                    <input type="password" name="password_confirmation" class="form-control" id="exampleInputPassword2">
                                    <label for="" id="togglePassword2" class="input-group-text">Show</label>
                                </div>
                                <div class="input-group mb-3"> <input type="file" name="image" class="form-control"> <label
                                        class="input-group-text">Avatar</label> </div>
                                        <img src="{{url(\Auth::user()->image)}}" alt="" width="200px" height="200px">
                            </div> <!--end::Body--> <!--begin::Footer-->
                            <div class="card-footer"> <button type="submit" class="btn btn-primary">Submit</button> </div>
                            <!--end::Footer-->
                        </form> <!--end::Form-->
                    </div> <!--end::Quick Example--> <!--begin::Input Group-->
                </div> <!--end::Col--> <!--begin::Col-->
            </div> <!--end::Row-->
        </div> <!--end::Container-->
    </div> <!--end::App Content-->
</main> <!--end::App Main--> <!--begin::Footer-->
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {
    const passwordInput = document.getElementById('exampleInputPassword1');
    const toggleButton = document.getElementById('togglePassword');
    const passwordInput2 = document.getElementById('exampleInputPassword2');
    const toggleButton2 = document.getElementById('togglePassword2');

    toggleButton.addEventListener('click', function() {
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleButton.textContent = 'Hide';
        } else {
            passwordInput.type = 'password';
            toggleButton.textContent = 'Show';
        }
    });

    toggleButton2.addEventListener('click', function() {
        if (passwordInput2.type === 'password') {
            passwordInput2.type = 'text';
            toggleButton2.textContent = 'Hide';
        } else {
            passwordInput2.type = 'password';
            toggleButton2.textContent = 'Show';
        }
    });
});
</script>