<?php

// use App\Http\Middleware\AdminMiddleware;
// use Illuminate\Support\Facades\Route;
// use Illuminate\Foundation\Application;
// use Illuminate\Foundation\Configuration\Exceptions;
// use Illuminate\Foundation\Configuration\Middleware;
// use App\Http\Controllers\Admin\AuthController;
// use App\Http\Controllers\Admin\MahasiswaController;
// use App\Http\Controllers\Admin\DashboardController;
// use App\Http\Controllers\Admin\ProfileController;
// use App\Http\Controllers\Admin\KaprodiController;
// use App\Http\Controllers\Admin\PetaMahasiswaController;
// use App\Http\Controllers\Admin\AIController;
// use Illuminate\Support\Facades\Http;


// /////ADMIN +
// Route::get('/login-admin', [AuthController::class, 'showLogin'])
//     ->name('admin.login');

// Route::post('/login-admin', [AuthController::class, 'login'])
//     ->name('admin.login.submit');

// Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

// Route::middleware(['auth'])->group(function () {

// /////KAPRODI\
// Route::get('/admin/mahasiswa/export', [MahasiswaController::class, 'export'])
//     ->middleware(['auth', 'kaprodi'])
//     ->name('admin.mahasiswa.export');

//        // Daftar Mahasiswa
//     Route::get('/admin/mahasiswa', [MahasiswaController::class, 'index'])
//         ->name('admin.mahasiswa.index');

// /////ADMIN
//     Route::get('/admin/mahasiswa/create', [MahasiswaController::class, 'create'])
//         ->name('admin.mahasiswa.create');

//     // Simpan Mahasiswa
//     Route::post('/admin/mahasiswa', [MahasiswaController::class, 'store'])
//         ->name('admin.mahasiswa.store');

//     // Detail Mahasiswa
//     Route::get('/admin/mahasiswa/{mahasiswa}', [MahasiswaController::class, 'show'])
//         ->name('admin.mahasiswa.show');

//     // Form Edit Mahasiswa
//     Route::get('/admin/mahasiswa/{mahasiswa}/edit', [MahasiswaController::class, 'edit'])
//         ->name('admin.mahasiswa.edit');

//     // Update Mahasiswa
//     Route::put('/admin/mahasiswa/{mahasiswa}', [MahasiswaController::class, 'update'])
//         ->name('admin.mahasiswa.update');

//     // Hapus Mahasiswa
//     Route::delete('/admin/mahasiswa/{mahasiswa}', [MahasiswaController::class, 'destroy'])
//         ->name('admin.mahasiswa.destroy');

//     // // Import Excel
//     // Route::post('/admin/mahasiswa/import', [MahasiswaController::class, 'import'])
//     //     ->name('admin.mahasiswa.import');

//     // // Export Excel (opsional)
//     // Route::get('/admin/mahasiswa/export', [MahasiswaController::class, 'export'])
//     //     ->name('admin.mahasiswa.export');

// ////ADMIN
// Route::get('/admin/profile', [ProfileController::class, 'index'])->name('admin.profile');
// Route::put('/admin/profile', [ProfileController::class, 'update'])->name('admin.profile.update');
// Route::put('/admin/profile/password', [ProfileController::class, 'password'])->name('admin.profile.password');


// ////////////////////////////////////////////////////////////////////////////////////////////////////////////
// /////ADMIN
// Route::get(
//     '/admin/kaprodi',
//     [KaprodiController::class, 'index']
// )->name('admin.kaprodi.index');
// });

// ///////KAPRODI
// // Route::get(
// //     '/admin/peta/export/{provinsi}',
// //     [PetaMahasiswaController::class, 'exportProvinsi']
// // )
// // ->middleware(['auth', 'kaprodi'])
// // ->name('admin.peta.export');

// ////////////////////////////////////////////////ADMIN + KAPRODI
// Route::get(
//     '/admin/peta-mahasiswa',
//     [PetaMahasiswaController::class, 'index']
// )->name('admin.peta.index');

// Route::get(
//     '/api/map/provinsi',
//     [PetaMahasiswaController::class, 'provinsi']
// );

// Route::get(
//     '/api/map/kecamatan/{provinsi}',
//     [PetaMahasiswaController::class, 'kecamatan']
// );

// Route::get(
//     '/api/map/kabupaten/{provinsi}',
//     [PetaMahasiswaController::class, 'kabupaten']
// );

// Route::get(
//     '/api/map/mahasiswa/{provinsi}',
//     [PetaMahasiswaController::class, 'mahasiswaByProvinsi']

// );

//  Route::get('/admin/statistik', [App\Http\Controllers\Admin\StatistikController::class, 'index'])
//         ->name('admin.statistik');

// Route::get('/admin/bantuan', [App\Http\Controllers\Admin\BantuanController::class, 'index'])
//         ->name('admin.bantuan');

// Route::get('/admin/tentang', [App\Http\Controllers\Admin\TentangController::class, 'index'])
//         ->name('admin.tentang');

// ////AI
// Route::get('/admin/ai', [App\Http\Controllers\Admin\AIController::class, 'index'])
//     ->name('admin.ai.index');
// Route::post('/admin/ai/chat', [App\Http\Controllers\Admin\AIController::class, 'sendMessage'])
//     ->name('admin.ai.chat');


// // Route::get(
// //     '/admin/ai/briefing',
// //     [AIController::class, 'briefing']
// // )->name('admin.ai.briefing');

// Route::get(
//     '/admin/ai/insights',
//     [AIController::class, 'insight']
// )->name('admin.ai.insight');

// // Route::get(
// //     '/admin/ai/strategi',
// //     [AIController::class, 'strategic']
// // )->name('admin.ai.strategic');

// Route::prefix('admin/ai')->group(function () {

//     Route::get('/trend', [AIController::class, 'trend']);
//     Route::get('/anomaly', [AIController::class, 'anomaly']);
//     Route::get('/distribution', [AIController::class, 'distribution']);

// });


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MahasiswaController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\KaprodiController;
use App\Http\Controllers\Admin\PetaMahasiswaController;
use App\Http\Controllers\Admin\AIController;
use App\Http\Controllers\Admin\StatistikController;
use App\Http\Controllers\Admin\BantuanController;
use App\Http\Controllers\Admin\TentangController;

/*
|--------------------------------------------------------------------------
| LOGIN
|--------------------------------------------------------------------------
*/

Route::get('/login-admin', [AuthController::class, 'showLogin'])
    ->name('admin.login');

Route::post('/login-admin', [AuthController::class, 'login'])
    ->name('admin.login.submit');


/*
|--------------------------------------------------------------------------
| ADMIN + KAPRODI
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    ///DASHBOARD
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])
        ->name('admin.dashboard');

    //////PROFILE
    Route::get('/admin/profile', [ProfileController::class, 'index'])
        ->name('admin.profile');

    Route::put('/admin/profile/update', [ProfileController::class, 'updateProfile'])
        ->name('admin.profile.update');

    Route::put('/admin/profile/email', [ProfileController::class, 'updateEmail'])
        ->name('admin.profile.email');

    Route::put('/admin/profile/password', [ProfileController::class, 'updatePassword'])
        ->name('admin.profile.password');

    Route::put('/admin/profile/avatar', [ProfileController::class, 'updateAvatar'])
    ->name('admin.profile.avatar');

    Route::delete('/admin/profile/avatar', [ProfileController::class, 'removeAvatar'])
        ->name('admin.profile.avatar.delete');

    Route::post('/admin/logout-admin', [ProfileController::class, 'logout'])
        ->name('admin.profile.logout');

    ///PETA MAHASISWA
    Route::get('/admin/peta-mahasiswa', [PetaMahasiswaController::class, 'index'])
        ->name('admin.peta.index');

    Route::get('/api/map/provinsi', [PetaMahasiswaController::class, 'provinsi']);

    Route::get('/api/map/kabupaten/{provinsi}', [PetaMahasiswaController::class, 'kabupaten']);

    Route::get('/api/map/mahasiswa/{provinsi}', [PetaMahasiswaController::class, 'mahasiswaByProvinsi']);

    ///////STATISTIK
    Route::get('/admin/statistik', [StatistikController::class, 'index'])
        ->name('admin.statistik');
    
    Route::get('/statistik/detail-sekolah', [StatistikController::class, 'detailSekolah'])->name('statistik.detail-sekolah');
    Route::get('/statistik/detail-provinsi', [StatistikController::class, 'detailProvinsi'])->name('statistik.detail-provinsi');
    Route::get('/statistik/detail-kabupaten', [StatistikController::class, 'detailKabupaten'])->name('statistik.detail-kabupaten');

    Route::get('/admin/bantuan', [BantuanController::class, 'index'])
        ->name('admin.bantuan');

    Route::get('/admin/tentang', [TentangController::class, 'index'])
        ->name('admin.tentang');

    /*
    |--------------------------------------------------------------------------
    | AI
    |--------------------------------------------------------------------------
    */

    Route::get('/admin/ai', [AIController::class, 'index'])
        ->name('admin.ai.index');

    Route::post('/admin/ai/chat', [AIController::class, 'sendMessage'])
        ->name('admin.ai.chat');

    Route::get('/admin/ai/insights', [AIController::class, 'insight'])
        ->name('admin.ai.insight');

    Route::prefix('admin/ai')->group(function () {
        Route::get('/trend', [AIController::class, 'trend']);
        Route::get('/anomaly', [AIController::class, 'anomaly']);
        Route::get('/distribution', [AIController::class, 'distribution']);
    });
});


/*
|--------------------------------------------------------------------------
| ADMIN ONLY
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])->group(function () {
    /*
    |--------------------------------------------------------------------------
    | KAPRODI ONLY
    |--------------------------------------------------------------------------
    */

    Route::middleware(['auth', 'kaprodi'])->group(function () {

        Route::get('/admin/mahasiswa/export', [MahasiswaController::class, 'export'])
            ->name('admin.mahasiswa.export');
    });

    Route::get('/admin/kaprodi', [KaprodiController::class, 'index'])
            ->name('admin.kaprodi.index');

        
    Route::post('/admin/kaprodi', [KaprodiController::class, 'store'])
    ->name('admin.kaprodi.store');

    Route::put('/admin/kaprodi/{id}', [KaprodiController::class, 'update'])
        ->name('admin.kaprodi.update');

    Route::delete('/admin/kaprodi/{id}', [KaprodiController::class, 'destroy'])
        ->name('admin.kaprodi.destroy');


    Route::get('/mahasiswa/create', [MahasiswaController::class, 'create'])
        ->name('admin.mahasiswa.create');

    Route::post('/mahasiswa', [MahasiswaController::class, 'store'])
        ->name('admin.mahasiswa.store');

    Route::delete('/mahasiswa/{id}', [MahasiswaController::class, 'destroy'])
        ->name('admin.mahasiswa.destroy');

    Route::get('/mahasiswa/{id}/edit', [MahasiswaController::class, 'edit'])
    ->name('admin.mahasiswa.edit');

    Route::put('/mahasiswa/{id}', [MahasiswaController::class, 'update'])
        ->name('admin.mahasiswa.update');
});


/*
|--------------------------------------------------------------------------
| ADMIN + KAPRODI (LIST MAHASISWA)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get('/admin/mahasiswa', [MahasiswaController::class, 'index'])
        ->name('admin.mahasiswa.index');

});