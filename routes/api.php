<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
// Before login
Route::post('/create-users',[AuthController::class,'register']);
Route::post('/login-user',[AuthController::class,'login']);

// After login
Route::post('/user-logout',[AuthController::class,'logout'])->middleware('auth:sanctum');