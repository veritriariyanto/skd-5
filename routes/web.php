<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\OrderController;
use App\Http\Middleware\CashierMiddleware;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\BackupController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;



Route::get('/', function () {
    if (Auth::check()) {
        if (Auth::user()->role == 1) {
            return redirect()->route('dashboard.index');
        } elseif (Auth::user()->role == 2) {
            return redirect()->route('transaction.index');
        }
    }

    // Redirect langsung ke login jika guest
    return redirect()->route('login');
});


//Route Middleware ADMIN
Route::middleware(['auth', AdminMiddleware::class])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

    Route::get('/history', [OrderController::class, 'index'])->name('history.index');
    Route::get('/history/{id}', [OrderController::class, 'show'])->name('history.show');
    Route::get('/history/view/pdf', [OrderController::class, 'view_pdf']);
    Route::post('/history/export', [OrderController::class, 'export_transactions'])->name('history.export');

    Route::get('/products', [ProductController::class, 'index'])->name('product.index');
    Route::get('/products/create', [ProductController::class, 'create'])->name('product.create');
    Route::post('/products', [ProductController::class, 'store'])->name('product.store');
    Route::get('/products/{id}/edit', [ProductController::class, 'edit'])->name('product.edit');
    Route::put('/products/{id}', [ProductController::class, 'update'])->name('product.update');
    Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('product.destroy');

    Route::get('/categories', [CategoryController::class, 'index'])->name('category.index');
    Route::get('/categories/create', [CategoryController::class, 'create'])->name('category.create');
    Route::post('/categories', [CategoryController::class, 'store'])->name('category.store');
    Route::get('/categories/{id}/edit', [CategoryController::class, 'edit'])->name('category.edit');
    Route::put('/categories/{id}', [CategoryController::class, 'update'])->name('category.update');
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy'])->name('category.destroy');

    Route::get('/users', [UserController::class, 'index'])->name('user.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('user.create');
    Route::post('/users', [UserController::class, 'store'])->name('user.store');
    Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('user.edit');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('user.update');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('user.destroy');

    Route::get('/backup', [BackupController::class, 'index'])->name('backup.index');
    Route::post('/backup/create', [BackupController::class, 'create'])->name('backup.create');
    Route::get('/backup/download/{filename}', [BackupController::class, 'download'])->name('backup.download');
    Route::delete('/backup/delete/{filename}', [BackupController::class, 'delete'])->name('backup.delete');
    Route::post('/backup/restore', [BackupController::class, 'restore'])->name('backup.restore');
    Route::post('/backup/upload', [BackupController::class, 'upload'])->name('backup.upload');
});

//Route Middleware CASHIER
Route::middleware(['auth'])->group(function () {
    Route::get('/transaction', [TransactionController::class, 'index'])->name('transaction.index');
    Route::get('/transaction/pay', [TransactionController::class, 'pay'])->name('transaction.pay');
    Route::post('/transaction/pay', [TransactionController::class, 'pay'])->name('transaction.pay');
    Route::post('/transaction/process', [TransactionController::class, 'process'])->name('transaction.process');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/history', [OrderController::class, 'index'])->name('history.index');
    Route::get('/history/{id}', [OrderController::class, 'show'])->name('history.show');
});


Route::get('/images/{folder}/{filename}', function ($folder, $filename) {
    $path = resource_path('images/' . $folder . '/' . $filename);

    if (!file_exists($path)) {
        abort(404); // Mengembalikan 404 jika gambar tidak ditemukan
    }

    return Response::file($path); // Mengembalikan file gambar
});

Route::middleware('guest')->group(function () {
    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    // OTP routes
    Route::get('login/otp', [AuthenticatedSessionController::class, 'showOtpForm'])
        ->name('login.otp');
    Route::post('login/otp', [AuthenticatedSessionController::class, 'verifyOtp'])
        ->name('login.otp.verify');
});

// Remove or comment out these duplicate routes
// Route::get('/otp-verification', [AuthenticatedSessionController::class, 'otpVerificationView'])->name('otp-verification');
// Route::post('/otp-verification', [AuthenticatedSessionController::class, 'verifyOtp'])->name('otp.verify');

Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});

require __DIR__ . '/auth.php';
