<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\ProjectApiController;
use App\Http\Controllers\Api\StaffApiController;
use App\Http\Controllers\Api\StaffTaskApiController;
use App\Http\Controllers\Api\TaskApiController;


Route::post('admin/login', [AuthApiController::class, 'login']);
Route::post('staff/login', [AuthApiController::class, 'login']);

/* Admin API */
Route::prefix('admin')->middleware(['auth:sanctum', 'user.type:admin'])->group(function () {
    Route::post('logout', [AuthApiController::class, 'logout']);
    Route::apiResource('projects', ProjectApiController::class);
    Route::apiResource('staff', StaffApiController::class)->except('show');
    Route::apiResource('tasks', TaskApiController::class);
});

/* Staff API */
Route::prefix('staff')->middleware(['auth:sanctum', 'user.type:staff', 'staff.active'])->group(function () {
    Route::post('logout', [AuthApiController::class, 'logout']);
    Route::get('tasks', [StaffTaskApiController::class, 'index']);
    Route::get('tasks/{task}', [StaffTaskApiController::class, 'show']);
    Route::patch('tasks/{task}/status', [StaffTaskApiController::class, 'updateStatus']);
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
