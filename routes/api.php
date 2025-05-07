<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/device/register', function (\Illuminate\Http\Request $request) {
    Log::info('Device info received:', $request->all());
    return response()->json(['message' => 'Received successfully']);
});
