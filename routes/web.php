<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AccountController;

Route::get('/', function () { return view('welcome'); });

// የአካውንት መቆጣጠሪያዎች
Route::get('/admin/accounts', [AccountController::class, 'index'])->name('admin.accounts.index');
Route::get('/admin/accounts/create', [AccountController::class, 'create'])->name('admin.accounts.create');
Route::post('/admin/accounts/store', [AccountController::class, 'store'])->name('admin.accounts.store');

// የገንዘብ ማስተላለፊያ (Transfer)
Route::get('/admin/transfer', [AccountController::class, 'transferForm'])->name('admin.accounts.transfer');
Route::post('/admin/transfer', [AccountController::class, 'doTransfer'])->name('admin.accounts.doTransfer');

// አዲሱ፦ የአካውንት ቁጥር ሲሰጠው ስሙን የሚፈልግ Route
Route::get('/admin/accounts/search/{account_number}', [AccountController::class, 'searchAccount']);
