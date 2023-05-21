<?php

use Illuminate\Support\Facades\Route;


//Request Controller Routes
Route::get('/requests', [App\Http\Controllers\ConnectionRequestController::class, 'index'])->name('requests');
Route::post('/requests', [App\Http\Controllers\ConnectionRequestController::class, 'store']);
Route::delete('/requests/{id}', [App\Http\Controllers\ConnectionRequestController::class, 'destroy']);
Route::patch('/requests/{id}', [App\Http\Controllers\ConnectionRequestController::class, 'update']);
