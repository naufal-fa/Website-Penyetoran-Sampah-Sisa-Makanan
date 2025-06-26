<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Import semua Controller yang kita butuhkan
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Nasabah\LaporSetoranController;
use App\Http\Controllers\Admin\DepositController as AdminDepositController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\WithdrawalController as AdminWithdrawalController;
use App\Http\Controllers\Nasabah\DashboardController as NasabahDashboardController;
use App\Http\Controllers\Nasabah\WithdrawalController as NasabahWithdrawalController;


/*
|--------------------------------------------------------------------------
| Rute Publik
|--------------------------------------------------------------------------
| Rute yang bisa diakses oleh siapa saja, bahkan tamu (guest).
*/

Route::get('/', function () {
    // Jika sudah login, arahkan ke dashboard yang sesuai. Jika belum, tampilkan halaman welcome.
    if (Auth::check()) {
        if (Auth::user()->role == 'admin') {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('nasabah.dashboard');
    }
    return view('welcome'); // Halaman landing page default Laravel
});


/*
|--------------------------------------------------------------------------
| Rute Bawaan Breeze (Setelah Login)
|--------------------------------------------------------------------------
| Rute-rute ini ditambahkan oleh Breeze dan hanya bisa diakses
| oleh pengguna yang sudah login (middleware 'auth').
*/

// Rute '/dashboard' bawaan Breeze.
// Kita modifikasi agar menjadi 'pintu gerbang' yang mengarahkan user
// ke dashboard yang sesuai dengan rolenya.
Route::get('/dashboard', function () {
    if (Auth::user()->role == 'admin') {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('nasabah.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


// Rute untuk halaman profil pengguna (edit nama, email, password)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


/*
|--------------------------------------------------------------------------
| Rute Kustom untuk Nasabah
|--------------------------------------------------------------------------
| Grup rute ini hanya bisa diakses oleh pengguna yang sudah login.
*/

Route::middleware(['auth'])->name('nasabah.')->prefix('nasabah')->group(function () {
    Route::get('/dashboard', [NasabahDashboardController::class, 'index'])->name('dashboard');
    Route::get('/withdrawals', [NasabahWithdrawalController::class, 'create'])->name('withdrawals.create');
    Route::post('/withdrawals', [NasabahWithdrawalController::class, 'store'])->name('withdrawals.store');
    Route::get('/lapor-setoran', [LaporSetoranController::class, 'create'])->name('lapor.create');
    Route::post('/lapor-setoran', [LaporSetoranController::class, 'store'])->name('lapor.store');
});


/*
|--------------------------------------------------------------------------
| Rute Kustom untuk Admin
|--------------------------------------------------------------------------
| Grup rute ini HANYA bisa diakses oleh pengguna yang sudah login
| DAN memiliki role 'admin' (middleware 'admin').
*/

Route::middleware(['auth', 'admin'])->name('admin.')->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/deposits/create', [AdminDepositController::class, 'create'])->name('deposits.create');
    Route::post('/deposits', [AdminDepositController::class, 'store'])->name('deposits.store');
    Route::get('/deposits', [AdminDepositController::class, 'index'])->name('deposits.index');
    Route::post('/deposits/{deposit}/approve', [AdminDepositController::class, 'approve'])->name('deposits.approve');
    Route::get('/withdrawals', [AdminWithdrawalController::class, 'index'])->name('withdrawals.index');
    Route::post('/withdrawals/{withdrawal}/process', [AdminWithdrawalController::class, 'process'])->name('withdrawals.process');
});


/*
|--------------------------------------------------------------------------
| Impor Rute Autentikasi Breeze
|--------------------------------------------------------------------------
| Baris ini adalah 'kunci' dari Breeze. Ia memuat semua rute
| yang diperlukan untuk proses login, register, logout, dll.
| dari file routes/auth.php.
*/
require __DIR__.'/auth.php';