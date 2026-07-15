<?php

use App\Http\Controllers\ArsipSuratController;
use App\Http\Controllers\AsalController;
use App\Http\Controllers\RuangRapatController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DispoMasukController;
use App\Http\Controllers\DisposisiController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SuratMasukController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {return view('login');})->name('login');
Route::post('login', [AuthController::class, 'index'])->name('post-login');
Route::middleware(['auth', 'auth.session', 'role.access'])->group(function () {
    Route::put('/profile/{user}', [AuthController::class, 'profile_update'])->name('profile-update');
    Route::get('/profile', function () {return view('profile');})->name('profile');
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');
    Route::get('/data-dashboard', [HomeController::class, 'data_dashboard'])->name('data-dashboard');
    
    Route::post('/check-url', [HomeController::class, 'checkUrl'])->name('check-url');
    Route::get('/surat-masuk-edit/{surat_masuk}', [SuratMasukController::class, 'edit'])->name('surat-masuk.edit');
    Route::get('/belum-disposisi', [SuratMasukController::class, 'disposisi'])->name('belum-disposisi');
    Route::get('/data-disposisi', [SuratMasukController::class, 'data_disposisi'])->name('data-disposisi');
    Route::get('/surat-terlewat', [SuratMasukController::class, 'terlewat'])->name('surat-terlewat');
    Route::get('/data-terlewat', [SuratMasukController::class, 'data_terlewat'])->name('data-terlewat');
    Route::get('/surat-masuk', [SuratMasukController::class, 'index'])->name('surat-masuk');
    Route::get('/surat-masuk-all', [SuratMasukController::class, 'surat_masuk_all'])->name('surat-masuk-all');
    Route::get('/surat-masuk/create', [SuratMasukController::class, 'create'])->name('surat-masuk.create');
    Route::get('/surat-masuk/data', [SuratMasukController::class, 'data'])->name('surat-masuk-data');
    Route::get('/surat-masuk/data-all', [SuratMasukController::class, 'all'])->name('surat-masuk-data-all');
    Route::post('/surat-masuk', [SuratMasukController::class, 'post'])->name('surat-masuk-post');
    Route::post('/surat-masuk-import', [SuratMasukController::class, 'import'])->name('surat-masuk-import');
    Route::post('/surat-masuk-notulen/{no_agenda}', [SuratMasukController::class, 'notulen'])->name('surat-masuk-notulen');
    Route::put('/surat-masuk-notulen-update/{id}', [SuratMasukController::class, 'notulenUpdate'])->name('surat-masuk-notulen-update');
    Route::get('/surat-masuk-notulen-data/{no_agenda}', [SuratMasukController::class, 'notulenData'])->name('surat-masuk-notulen-data');
    Route::delete('/surat-masuk-notulen-file-delete/{id}', [SuratMasukController::class, 'notulenFileDelete'])->name('surat-masuk-notulen-file-delete');
    Route::put('/surat-masuk-update/{surat_masuk}', [SuratMasukController::class, 'update'])->name('surat-masuk.update');
    Route::delete('/surat-masuk-delete/{surat_masuk}', [SuratMasukController::class, 'delete'])->name('surat-masuk-delete');

    Route::delete('/dispo_masuk/{dispo_masuk}', [DispoMasukController::class, 'delete'])->name('dispo-masuk-delete');
    Route::get('/cetak-dispo/{dispo_masuk}', [DispoMasukController::class, 'cetak'])->name('cetak-dispo');
    Route::get('/pilih-user-data/{dispo_masuk}', [DispoMasukController::class, 'pilihUserData'])->name('pilih-user-data');
    Route::post('/user-dispo/{dispo_masuk}', [DispoMasukController::class, 'pilihUser'])->name('user-dispo');


    Route::delete('/delete-file/{id}', [SuratMasukController::class, 'deleteFile'])->name('delete-file');
    Route::post('/upload-file', [SuratMasukController::class, 'uploadFile'])->name('upload-file');
    // endpoint to fetch uploaded files for a specific surat masuk (used by index view)
    Route::get('/surat-masuk-files/{id}', [SuratMasukController::class, 'files'])->name('surat-masuk-files');

    Route::get('arsip-surat', [ArsipSuratController::class, 'index'])->name('arsip-surat');
    Route::get('/arsip-surat/data', [ArsipSuratController::class, 'data'])->name('arsip-surat-data');
    
    Route::get('/asal', [AsalController::class, 'index'])->name('asal');
    Route::get('/asal-create', [AsalController::class, 'create'])->name('asal.create');
    Route::post('/asal', [AsalController::class, 'store'])->name('asal.post');
    Route::get('/asal/edit/{id}', [AsalController::class, 'edit'])->name('asal.edit');
    Route::put('/asal/update/{id}', [AsalController::class, 'update'])->name('asal.update');
    Route::delete('/asal/delete/{id}', [AsalController::class, 'destroy'])->name('asal.destroy');

    Route::get('/ruang-rapat', [RuangRapatController::class, 'index'])->name('ruang-rapat');
    Route::get('/ruang-rapat/create', [RuangRapatController::class, 'create'])->name('ruang-rapat.create');
    Route::post('/ruang-rapat', [RuangRapatController::class, 'store'])->name('ruang-rapat.post');
    Route::get('/ruang-rapat/edit/{id}', [RuangRapatController::class, 'edit'])->name('ruang-rapat.edit');
    Route::put('/ruang-rapat/update/{id}', [RuangRapatController::class, 'update'])->name('ruang-rapat.update');
    Route::delete('/ruang-rapat/delete/{id}', [RuangRapatController::class, 'destroy'])->name('ruang-rapat.destroy');

    Route::resource('users', UserController::class);
    Route::resource('disposisi', DisposisiController::class);

    Route::get('/laporan/export/', [LaporanController::class, 'export'])->name('laporan.export');
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan');
});
