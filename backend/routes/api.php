<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController as AuthController;
use App\Http\Controllers\UserController as UserController;
use App\Http\Controllers\AccountController as AccountController;
use App\Http\Controllers\TransactionController as TransactionController;
use App\Http\Controllers\AuditLogController as AuditLogController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application.
| These routes are loaded by the RouteServiceProvider within a group
| which is assigned the "api" middleware group.
|

*/



Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('reset-password', [AuthController::class, 'resetPassword']);
});

// Protected routes (auth:sanctum)
Route::middleware('auth:sanctum')->group(function () {

    // Current authenticated user
    Route::get('me', [UserController::class, 'me']);
    Route::post('auth/logout', [AuthController::class, 'logout']);

    // Update own account (cannot close or change role)
    Route::put('users/self', [UserController::class, 'updateSelf']);

    // Accounts
    Route::get('/accounts', [AccountController::class, 'index']);
    Route::get('/accounts/{account}', [AccountController::class, 'show']);
    Route::post('/accounts', [AccountController::class, 'store']);
    Route::put('/accounts/{account}', [AccountController::class, 'update']);

    // List transactions
    Route::get('/transactions', [TransactionController::class, 'index']);

    // View single transaction
    Route::get('/transactions/{transaction}', [TransactionController::class, 'show']);

    // Create deposit / withdrawal / transfer
    Route::post('/transactions', [TransactionController::class, 'store']);

    // Transfer between accounts
    Route::post('transfer', [TransactionController::class, 'transfer']);

    // User management routes
    // These routes call UserController methods which internally enforce role-based rules
    Route::get('users', [UserController::class, 'index']);        // list users
    Route::post('users', [UserController::class, 'store']);       // create user
    Route::get('users/{user}', [UserController::class, 'show']);  // show single user
    Route::put('users/{user}', [UserController::class, 'update']); // update user
    Route::delete('users/{user}', [UserController::class, 'destroy']); // close/delete user
    Route::patch('users/{user}/suspend', [UserController::class, 'suspend']);
    Route::patch('users/{user}/activate', [UserController::class, 'activate']);


    Route::get('audit-logs', [AuditLogController::class, 'index']); // optional ?user_id filter
    Route::get('audit-logs/{auditLog}', [AuditLogController::class, 'show']);
});
