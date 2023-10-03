<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\PersonalAccessTokenController;
use App\Http\Controllers\TransactionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/employees/new', [EmployeeController::class, 'store']);

Route::post('/personal-access-tokens', [PersonalAccessTokenController::class, 'store']);

Route::middleware('auth:sanctum')->group(function() {
    Route::post('/transactions/new', [TransactionController::class, 'store'])->middleware('auth:sanctum');
});

Route::get('/transactions', [TransactionController::class, 'index']);

Route::put('/transactions', [TransactionController::class, 'update']);