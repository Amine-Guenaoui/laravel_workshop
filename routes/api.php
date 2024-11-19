<?php

use App\Http\Controllers\TaskApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::controller(TaskApiController::class)->group(function () {
    Route::get('/tasks', 'index');
    Route::get('/tasks/{task}', 'show');
    Route::post('/tasks', 'store');
    Route::put('/tasks/{task}', 'update');
    Route::delete('/tasks/{task}', 'destroy');
});
