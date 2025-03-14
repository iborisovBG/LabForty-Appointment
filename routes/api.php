<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AppointmentApiController;

Route::prefix('appointments')->group(function() {
    Route::get('/', [AppointmentApiController::class, 'index']);
    Route::post('/', [AppointmentApiController::class, 'store']);
    Route::get('{id}', [AppointmentApiController::class, 'show']);
    Route::put('{id}', [AppointmentApiController::class, 'update']);
    Route::delete('{id}', [AppointmentApiController::class, 'destroy']);
});
