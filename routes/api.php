<?php 

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PaymentsController;
use App\Http\Controllers\RecurringPaymentController;
use App\Http\Controllers\DashboardController;

// Routes publiques
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);  

// Routes protégées par authentification
Route::middleware('auth:sanctum')->group(function () {
    // Authentification
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index']);
    
    // Paiements
    Route::apiResource('/payments', PaymentsController::class);
    Route::get('/payments/filter/past', [PaymentsController::class, 'filterByPastPayments']);
    Route::get('/payments/filter/date-range', [PaymentsController::class, 'filterByDateRange']);
    
    // Paiements récurrents
    Route::apiResource('/recurring-payments', RecurringPaymentController::class);
});

// Routes administrateur (exemple pour futures fonctionnalités)
Route::middleware(['auth:sanctum', 'role:admin'])->prefix('admin')->group(function () {
    // Routes réservées aux administrateurs
    // Route::get('/users', [UserController::class, 'index']);
    // Route::get('/all-payments', [PaymentsController::class, 'allPayments']);
});