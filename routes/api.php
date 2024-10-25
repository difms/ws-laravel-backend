<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MessageController;

Route::post('/auth/login', [MessageController::class, 'login']);
Route::post('/send-message', [MessageController::class, 'sendMessage']);

Route::middleware('auth:sanctum')->get('getUser', function () {
    return response()->json(Auth::user());
});
