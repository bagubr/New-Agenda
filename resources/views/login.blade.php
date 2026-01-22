<!DOCTYPE html>
<html lang="en">

<head>
    <title>Agenda | Distaru Kota Semarang</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="{{asset('/dist')}}/css/adminlte.css">
</head>

<body class="login-page bg-body-secondary">
    <div class="login-box">
        <div class="card">
            @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
            @endif
            @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
            @endif
            @if ($errors->any())
            @foreach ($errors->all() as $error)
            <div class="alert alert-danger">{{ $error }}</div>
            @endforeach
            @endif



            <div class="card-body login-card-body">
                <p class="login-box-msg">E-AGENDA DISTARU KOTA SEMARANG</p>
                <img src="{{asset('/dist')}}/assets/img/logo.png" alt="AdminLTE Logo" class="opacity-75 w-50 rounded m-auto mb-2 d-block">
                <form action="{{route('post-login')}}" method="post" autocomplete="off" autocomplete="do-not-autofill">
                    @csrf
                    <div class="input-group mb-3">
                        <input type="text" name="akun" class="form-control" placeholder="Username">
                        <div class="input-group-text"> <span class="bi bi-person"></span> </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" name="password" class="form-control" placeholder="Password">
                        <div class="input-group-text"> <span class="bi bi-lock-fill"></span> </div>
                    </div>
                    <div class="row">
                        <div class="col-12 d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">Login</button>
                        </div>
                    </div>
                </form>
                <div class="social-auth-links text-center mb-3 d-grid gap-2">
                </div>
            </div>
        </div>
    </div>
    <script src="{{asset('/dist')}}/js/adminlte.js"></script>
</body>

</html>