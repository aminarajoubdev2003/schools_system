<?php

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//...boss
Route::get('view/boss',[App\Http\Controllers\BossController::class,'index']);
Route::get('view/boss/{uuid}',[App\Http\Controllers\BossController::class,'show']);
Route::post('add/boss',[App\Http\Controllers\BossController::class,'store']);
Route::post('edit/boss/{uuid}',[App\Http\Controllers\BossController::class,'update']);
Route::get('delete/boss/{uuid}',[App\Http\Controllers\BossController::class,'destroy']);
