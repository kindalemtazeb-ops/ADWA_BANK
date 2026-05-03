<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\AccountController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Admin Dashboard
Route::get('/dashboard', [AccountController::class, 'dashboard'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {

    // 1. Dashboard
    Route::get('/admin/dashboard', [AccountController::class, 'dashboard'])->name('admin.dashboard');

    // --- ወሳኝ ማስተካከያ፦ ፍለጋ (Search) ከ Resource በላይ መሆን አለበት ---
    Route::get('/admin/accounts/search/{query}', [AccountController::class, 'searchAccount'])->name('admin.accounts.search');

    // 2. የባንክ ስራዎች (Deposit, Withdraw, Transfer)
    Route::get('/admin/accounts/deposit', [AccountController::class, 'depositForm'])->name('admin.accounts.depositForm');
    Route::post('/admin/accounts/deposit', [AccountController::class, 'doDeposit'])->name('admin.accounts.doDeposit');

    Route::get('/admin/accounts/withdraw', [AccountController::class, 'withdrawForm'])->name('admin.accounts.withdrawForm');
    Route::post('/admin/accounts/withdraw', [AccountController::class, 'doWithdraw'])->name('admin.accounts.doWithdraw');

    Route::get('/admin/accounts/transfer', [AccountController::class, 'transferForm'])->name('admin.accounts.transferForm');
    Route::post('/admin/accounts/transfer', [AccountController::class, 'doTransfer'])->name('admin.accounts.doTransfer');

    // 3. CRUD ስራዎች በ Resource
    Route::resource('admin/accounts', AccountController::class)->names([
        'index'   => 'admin.accounts.index',
        'create'  => 'admin.accounts.create',
        'store'   => 'admin.accounts.store',
        'show'    => 'admin.accounts.show',
        'edit'    => 'admin.accounts.edit',
        'update'  => 'admin.accounts.update',
        'destroy' => 'admin.accounts.destroy',
    ]);

    // 4. ተጨማሪ ተግባራት
    Route::get('/admin/accounts/{id}/history', [AccountController::class, 'history'])->name('admin.accounts.history');
    Route::post('/ussd-simulate', [AccountController::class, 'handleUSSD'])->name('admin.ussd.handle');

    // 5. የፕሮፋይል ስራዎች
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
