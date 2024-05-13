<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/login', [AuthController::class, 'login']);


Route::post('/report', [ReportController::class, 'store']);
Route::post('/report-by-status', [ReportController::class, 'showByStatus']);
Route::get('/report/{id}', [ReportController::class, 'show']);
Route::get('/report', [ReportController::class, 'showAll']);
Route::put('/report/{id}', [ReportController::class, 'update']);
Route::put('/report-update-status/{id}', [ReportController::class, 'updateStatus']);
Route::delete('/report/{id}', [ReportController::class, 'destroy']);


Route::post('/news', [NewsController::class, 'store']);
Route::get('/news/{id}', [NewsController::class, 'show']);
Route::get('/news', [NewsController::class, 'showAll']);
Route::post('/news-update/{id}', [NewsController::class, 'update']);
Route::delete('/news/{id}', [NewsController::class, 'destroy']);


Route::post('/user-by-username', [UserController::class, 'getUserByUsername']);




