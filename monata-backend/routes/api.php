<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\Admin\AdminManagementController;
use App\Http\Controllers\Api\Admin\CategoryController;
use App\Http\Controllers\Api\Admin\ProductController;
use App\Http\Controllers\Api\Admin\TransactionController;

// --- public ---
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// --- authenticated ---
Route::middleware(['auth:sanctum'])->group(function () {

    // auth & profile
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::get('/profile', [AuthController::class, 'profile']);

    // --- siswa ---
    // Katalog & Produk (Read Only)
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/categories/{id}', [CategoryController::class, 'show']);
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/{id}', [ProductController::class, 'show']);

    // Transaksi Siswa (Checkout & Riwayat)
    Route::get('/transactions', [TransactionController::class, 'index']);
    Route::get('/transactions/{id}', [TransactionController::class, 'show']);
    Route::post('/transactions', [TransactionController::class, 'store']); // Ini udah sekalian simpen detail


    // --- admin & super admin ---
    Route::middleware('role:admin,super_admin')->prefix('dashboard')->group(function () {
    
        Route::apiResource('categories', CategoryController::class);
        Route::apiResource('products', ProductController::class);
        
        Route::get('/transactions', [TransactionController::class, 'index']);
        Route::get('/transactions/{id}', [TransactionController::class, 'show']);
        Route::delete('/transactions/{id}', [TransactionController::class, 'destroy']);
        Route::patch('/transactions/{id}/status', [TransactionController::class, 'updateStatus']);
        
        // --- super admin ---
        Route::middleware('role:super_admin')->group(function () {
            Route::get('/users', [AuthController::class, 'getAllUsers']);
            Route::apiResource('admins', AdminManagementController::class);
        });
    });
});