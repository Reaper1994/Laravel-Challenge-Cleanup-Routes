<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
// Public Routes
Route::get('/', \App\Http\Controllers\HomeController::class)->name('home');
Route::get('book/{book:slug}', [\App\Http\Controllers\BookController::class, 'show'])->name('books.show');

// Authenticated User Routes
Route::middleware('auth')->group(function () {
    // Book Routes
    Route::get('book/create', [\App\Http\Controllers\BookController::class, 'create'])->name('books.create');
    Route::post('book/store', [\App\Http\Controllers\BookController::class, 'store'])->name('books.store');
    Route::get('book/{book:slug}/report/create', [\App\Http\Controllers\BookReportController::class, 'create'])->name('books.report.create');
    Route::post('book/{book}/report', [\App\Http\Controllers\BookReportController::class, 'store'])->name('books.report.store');

    // User Book Management
    Route::prefix('user/books')->group(function () {
        Route::get('/', [\App\Http\Controllers\BookController::class, 'index'])->name('user.books.list');
        Route::get('{book:slug}/edit', [\App\Http\Controllers\BookController::class, 'edit'])->name('user.books.edit');
        Route::put('{book:slug}', [\App\Http\Controllers\BookController::class, 'update'])->name('user.books.update');
        Route::delete('{book}', [\App\Http\Controllers\BookController::class, 'destroy'])->name('user.books.destroy');
    });

    // User Orders
    Route::get('user/orders', [\App\Http\Controllers\OrderController::class, 'index'])->name('user.orders.index');

    // User Settings
    Route::prefix('user/settings')->group(function () {
        Route::get('/', [\App\Http\Controllers\UserSettingsController::class, 'index'])->name('user.settings');
        Route::post('{user}', [\App\Http\Controllers\UserSettingsController::class, 'update'])->name('user.settings.update');
        Route::post('password/change/{user}', [\App\Http\Controllers\UserChangePassword::class, 'update'])->name('user.password.update');
    });
});

// Admin Routes
Route::middleware('isAdmin')->group(function () {
    Route::get('admin', \App\Http\Controllers\Admin\AdminDashboardController::class)->name('admin.index');

    // Admin Book Management
    Route::prefix('admin/books')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\AdminBookController::class, 'index'])->name('admin.books.index');
        Route::get('create', [\App\Http\Controllers\Admin\AdminBookController::class, 'create'])->name('admin.books.create');
        Route::post('/', [\App\Http\Controllers\Admin\AdminBookController::class, 'store'])->name('admin.books.store');
        Route::get('{book}/edit', [\App\Http\Controllers\Admin\AdminBookController::class, 'edit'])->name('admin.books.edit');
        Route::put('{book}', [\App\Http\Controllers\Admin\AdminBookController::class, 'update'])->name('admin.books.update');
        Route::delete('{book}', [\App\Http\Controllers\Admin\AdminBookController::class, 'destroy'])->name('admin.books.destroy');
        Route::put('approve/{book}', [\App\Http\Controllers\Admin\AdminBookController::class, 'approveBook'])->name('admin.books.approve');
    });

    // Admin User Management
    Route::prefix('admin/users')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\AdminUsersController::class, 'index'])->name('admin.users.index');
        Route::get('{user}/edit', [\App\Http\Controllers\Admin\AdminUsersController::class, 'edit'])->name('admin.users.edit');
        Route::put('{user}', [\App\Http\Controllers\Admin\AdminUsersController::class, 'update'])->name('admin.users.update');
        Route::delete('{user}', [\App\Http\Controllers\Admin\AdminUsersController::class, 'destroy'])->name('admin.users.destroy');
    });
});

require __DIR__ . '/auth.php';
