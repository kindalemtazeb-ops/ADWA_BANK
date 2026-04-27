<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\AccountController; // የአካውንት ኮንትሮለርን እዚህ ጋር እናስገባለን
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ማንኛውም ሰው ሲስተሙን ሲከፍት መጀመሪያ የሚመጣው ገጽ
Route::get('/', function () {
    return view('welcome');
});

// የሰራተኛው ዳሽቦርድ (ሎጊን ካደረገ በኋላ የሚመጣ)
Route::get('/dashboard', function(){
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

/*
|--------------------------------------------------------------------------
| የባንክ ስራዎች (በፓስወርድ የተጠበቁ)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // 1. የደንበኞች ዝርዝር እና መፈለጊያ
    Route::get('/admin/accounts', [AccountController::class, 'index'])->name('admin.accounts.index');

    // 2. አዲስ ደንበኛ መመዝገቢያ
    Route::get('/admin/accounts/create', [AccountController::class, 'create'])->name('admin.accounts.create');
    Route::post('/admin/accounts/store', [AccountController::class, 'store'])->name('admin.accounts.store');

    // 3. ብር ማስገቢያ (Deposit)
    Route::get('/admin/accounts/deposit', [AccountController::class, 'depositForm'])->name('admin.accounts.depositForm');
    Route::post('/admin/accounts/deposit', [AccountController::class, 'doDeposit'])->name('admin.accounts.doDeposit');

    // 4. ብር ማውጫ (Withdraw)
    Route::get('/admin/accounts/withdraw', [AccountController::class, 'withdrawForm'])->name('admin.accounts.withdrawForm');
    Route::post('/admin/accounts/withdraw', [AccountController::class, 'doWithdraw'])->name('admin.accounts.doWithdraw');

    // 5. ብር መላኪያ (Transfer)
    Route::get('/admin/accounts/transfer', [AccountController::class, 'transferForm'])->name('admin.accounts.transferForm');
    Route::post('/admin/accounts/transfer', [AccountController::class, 'doTransfer'])->name('admin.accounts.doTransfer');

    // 6. የደረሰኝ ማተሚያ
    Route::get('/admin/accounts/receipt/{id}', [AccountController::class, 'receipt'])->name('admin.accounts.receipt');

    /*--- የፕሮፋይል ማስተካከያ (Breeze የጨመረው) ---*/
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
// 7. አካውንት በቁጥር መፈለጊያ (ለ JavaScript/AJAX የሚጠቅም)
Route::get('/admin/accounts/search/{account_number}', [AccountController::class, 'searchAccount'])->name('admin.accounts.search');
