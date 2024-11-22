<?php

use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json(['message' => 'OK'], 200);
});

Route::post('/reserveAndConfirm', [TicketController::class, 'reserveAndConfirm']);
