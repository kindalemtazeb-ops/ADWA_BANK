<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UssdController;

/*
| API Routes for Adwa Bank USSD
*/

// ማንኛውንም የ USSD ጥያቄ በዚህ አድራሻ ያስተናግዳል
Route::any('/ussd', [UssdController::class, 'handleUssd']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
