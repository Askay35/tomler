<?php

use App\Http\Controllers\Auth\LoginRegisterController;
use App\Http\Controllers\SubdomainController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VerifyEmailController;
use App\Models\Subdomain;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::prefix('auth')->group(function () {
    Route::controller(LoginRegisterController::class)->group(function () {
        Route::post('/register', 'register')->middleware(['throttle:6,1']);
        Route::post('/login', 'login')->middleware(['throttle:10,1']);
    });

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [LoginRegisterController::class, 'logout']);
        
    });

    Route::get('/email/verify/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    // Resend link to verify email
    Route::post('/email/verify/resend', [VerifyEmailController::class, 'resend'])->middleware(['auth:api', 'throttle:6,1'])->name('verification.send');
});


Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('user')->group(function () {
        Route::get('/', [UserController::class, 'index']);
    })->middleware(['signed']);

    Route::put('/subdomain', [SubdomainController::class, 'store']);
    Route::get('/subdomain', [SubdomainController::class, 'show']);
    Route::put('/subdomain/update', [SubdomainController::class, 'update']);
    Route::delete('/subdomain', [SubdomainController::class, 'destroy']);
});