<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UssdController; // አንድ ጊዜ ብቻ መጠቀም ይበቃል

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| እዚህ ጋር የ USSD ሲስተምህን አድራሻ እናስቀምጣለን።
|
*/

// ለ USSD ጥያቄዎች (POST እና GET ሁለቱንም እንዲቀበል 'any' ተጠቅሜያለሁ)
// ይህ ሲስተሙ ከ Africa's Talking ወይም ከ Simulator ጋር እንዲገናኝ ይረዳል።
Route::any('/ussd', [UssdController::class, 'handleUssd']);

// Default route (ከፈለግከው መተው ትችላለህ)
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
