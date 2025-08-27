<?php 

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;


//Auth
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);  

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/dashboard', [AuthController::class, 'index']);
    Route::apiResource('/payments', PaymentController::class);
    Route::post('/logout', [AuthController::class, 'logout']);
});
