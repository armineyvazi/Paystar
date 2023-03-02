<?php

use App\Http\Controllers\Api\Auth\AuthController;
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

/**
 * @see AuthController::class
 * @methodName register
 */

//public
Route::prefix('v1')->group(function () {
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/login',[AuthController::class,'logIn'])->name('logIn');


});
//private
Route::middleware(['auth:sanctum'])->prefix('v1')->group( function( ) {
    Route::post('logout',[AuthController::class,'logout'])->name('logOut');//Route For Logout.
});
