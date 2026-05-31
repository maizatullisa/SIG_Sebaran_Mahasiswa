<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AlamatMahasiswaController;
use App\Http\Controllers\WilayahController;
use App\Http\Controllers\SekolahController;
use App\Http\Controllers\MinatController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\ProfileMahasiswaController;


// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/', [LandingPageController::class, 'index']);

Route::get('/register', [Authcontroller::class, 'showRegister']);
Route::post('/register', [Authcontroller::class, 'register']);

Route::get('/login', [AuthController::class, 'showLogin']);
Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout']);

Route::middleware('auth')->group(function () {

    // dashboard
    Route::get('/dashboard', [DashboardController::class, 'index']);

    // alamat mahasiswa
    Route::get('/alamat', [AlamatMahasiswaController::class, 'index']);
    Route::post('/alamat', [AlamatMahasiswaController::class, 'store']);
    Route::delete('/alamat', [AlamatMahasiswaController::class, 'destroy']);

    // sekolah
    Route::get('/sekolah', [SekolahController::class, 'index']);
    Route::post('/sekolah', [SekolahController::class, 'store']);
    Route::delete('/sekolah/{id}', [SekolahController::class, 'destroy']);

    //mint
    Route::get('/minat', [MinatController::class, 'index']);
    Route::post('/minat', [MinatController::class, 'store']);
    Route::delete('/minat-bakat/delete', [MinatController::class, 'destroy']);
    Route::delete('/minat/reset', [MinatController::class, 'reset'])->name('minat.reset');

    //prf
    Route::get('/profile', [ProfileMahasiswaController::class, 'index']);
    Route::post('/profile/update', [ProfileMahasiswaController::class, 'update'])->name('profile.update');
    Route::post('/ganti-password', [ProfileMahasiswaController::class, 'changePassword'])
    ->middleware('auth')
    ->name('password.change');


});

Route::get(
    '/cities/{province}',
    [WilayahController::class, 'cities']
);

Route::get(
    '/districts/{city}',
    [WilayahController::class, 'districts']
);

Route::get(
    '/villages/{district}',
    [WilayahController::class, 'villages']
);

