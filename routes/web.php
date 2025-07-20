<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Public routes
Route::get('/', App\Livewire\Public\Login\Index::class)->name('auth.login');
Route::get('/register', App\Livewire\Public\Register\Index::class)->name('auth.register');

// Admin routes - hanya bisa diakses role admin
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', App\Livewire\Admin\Dashboard\Index::class)
        ->name('admin.dashboard');
    Route::get('/alumni-magang', App\Livewire\Admin\AlumniMagang\Index::class)
        ->name('admin.alumni-magang');
    Route::get('/peserta-magang', App\Livewire\Admin\PesertaMagang\Index::class)
        ->name('admin.peserta-magang');
    Route::get('/sekolah', App\Livewire\Admin\Sekolah\Index::class)
        ->name('admin.sekolah');
    Route::get('/pengajuan', App\Livewire\Admin\Pengajuan\Index::class)
        ->name('admin.pengajuan');
    Route::get('/data-user', App\Livewire\Admin\User\Index::class)
        ->name('admin.user');
    Route::get('/laporan', App\Livewire\Admin\Laporan\Index::class)
        ->name('admin.laporan');
});

// User routes - hanya bisa diakses role user
Route::middleware(['auth', 'role:user'])->prefix('user')->group(function () {
    Route::get('/dashboard', App\Livewire\User\Dashboard\Index::class)
        ->name('user.dashboard');
    Route::get('/profile', App\Livewire\User\Profile\Index::class)
        ->name('user.profile');
    Route::get('/pengajuan', App\Livewire\User\Pengajuan\Index::class)
        ->name('user.pengajuan');
    Route::get('/alumni-magang', App\Livewire\User\AlumniMagang\Index::class)
        ->name('user.alumni-magang');
});

// Logout route - bisa diakses admin dan user
Route::middleware(['auth'])->group(function () {
    Route::get('/logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/');
    })->name('logout');
});
