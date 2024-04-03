<?php

use App\Http\Controllers\SubdomainController;
use App\Models\Subdomain;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::domain('{account}.' . explode('://',config('app.url'))[1])->group(function(){
    Route::get('/', [SubdomainController::class,'index']);
})->where('account', '[0-9A-Za-z]+');
