<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Modules\Auth\Controllers\AdminDashboardController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Route untuk admin (memerlukan login + role admin)
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])
        ->name('admin.dashboard');

    // FITUR TAMBAHAN POIN A
    Route::get('/admin/users', [AdminDashboardController::class, 'userList'])
        ->name('admin.users');

    // FITUR TAMBAHAN POIN B (Untuk update role user)
    Route::patch('/admin/users/{id}/role', [AdminDashboardController::class, 'updateRole'])
        ->name('admin.users.updateRole');

    // FITUR TAMBAHAN POIN C (Untuk hapus user)
    Route::delete('/admin/users/{id}', [AdminDashboardController::class, 'destroyUser'])
        ->name('admin.users.destroy');
});


// Route untuk user biasa (memerlukan login)
Route::middleware(['auth'])->group(function () {
    Route::get('/user/dashboard', function () {
        return view('dashboard');
    })->name('user.dashboard');
});

require __DIR__ . '/auth.php';
