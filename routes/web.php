<?php

use App\Http\Controllers\ArsipSuratController;
use App\Http\Controllers\AsalController;
use App\Http\Controllers\RuangRapatController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DispoKeluarController;
use App\Http\Controllers\DispoMasukController;
use App\Http\Controllers\DisposisiController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SuratMasukController;
use App\Http\Controllers\SuratKeluarController;
use App\Models\Disposisi;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {return view('login');})->name('login');
Route::post('login', [AuthController::class, 'index'])->name('post-login');
Route::middleware(['auth', 'auth.session'])->group(function () {
    Route::put('/profile/{user}', [AuthController::class, 'profile_update'])->name('profile-update');
    Route::get('/profile', function () {return view('profile');})->name('profile');
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');
    Route::post('/check-url', [HomeController::class, 'checkUrl'])->name('check-url');
    Route::get('/surat-masuk-edit/{surat_masuk}', [SuratMasukController::class, 'edit'])->name('surat-masuk.edit');
    Route::get('/surat-masuk', [SuratMasukController::class, 'index'])->name('surat-masuk');
    Route::get('/surat-masuk/create', [SuratMasukController::class, 'create'])->name('surat-masuk.create');
    Route::get('/surat-masuk/data', [SuratMasukController::class, 'data'])->name('surat-masuk-data');
    Route::post('/surat-masuk', [SuratMasukController::class, 'post'])->name('surat-masuk-post');
    Route::post('/surat-masuk-notulen/{no_agenda}', [SuratMasukController::class, 'notulen'])->name('surat-masuk-notulen');
    Route::put('/surat-masuk-notulen-update/{id}', [SuratMasukController::class, 'notulenUpdate'])->name('surat-masuk-notulen-update');
    Route::get('/surat-masuk-notulen-data/{no_agenda}', [SuratMasukController::class, 'notulenData'])->name('surat-masuk-notulen-data');
    Route::delete('/surat-masuk-notulen-file-delete/{id}', [SuratMasukController::class, 'notulenFileDelete'])->name('surat-masuk-notulen-file-delete');
    Route::put('/surat-masuk-update/{surat_masuk}', [SuratMasukController::class, 'update'])->name('surat-masuk.update');
    Route::delete('/surat-masuk-delete/{surat_masuk}', [SuratMasukController::class, 'delete'])->name('surat-masuk-delete');

    Route::delete('/dispo_masuk/{dispo_masuk}', [DispoMasukController::class, 'delete'])->name('dispo-masuk-delete');
    // Surat Keluar Routes
    Route::get('/surat-keluar', [SuratKeluarController::class, 'index'])->name('surat-keluar');
    Route::get('/surat-keluar/create', [SuratKeluarController::class, 'create'])->name('surat-keluar.create');
    Route::get('/surat-keluar/data', [SuratKeluarController::class, 'data'])->name('surat-keluar-data');
    Route::get('/surat-keluar-edit/{surat_keluar}', [SuratKeluarController::class, 'edit'])->name('surat-keluar.edit');
    Route::post('/surat-keluar', [SuratKeluarController::class, 'post'])->name('surat-keluar-post');
    Route::put('/surat-keluar-update/{surat_keluar}', [SuratKeluarController::class, 'update'])->name('surat-keluar.update');
    Route::delete('/surat-keluar-delete/{surat_keluar}', [SuratKeluarController::class, 'delete'])->name('surat-keluar-delete');
    Route::post('/surat-keluar-notulen/{no_agenda}', [SuratKeluarController::class, 'notulen'])->name('surat-keluar-notulen');
    Route::put('/surat-keluar-notulen-update/{id}', [SuratKeluarController::class, 'notulenUpdate'])->name('surat-keluar-notulen-update');
    Route::get('/surat-keluar-notulen-data/{no_agenda}', [SuratKeluarController::class, 'notulenData'])->name('surat-keluar-notulen-data');
    Route::delete('/surat-keluar-notulen-file-delete/{id}', [SuratKeluarController::class, 'notulenFileDelete'])->name('surat-keluar-notulen-file-delete');
    
    Route::delete('/dispo_keluar/{dispo_keluar}', [DispoKeluarController::class, 'delete'])->name('dispo-keluar-delete');
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
});
