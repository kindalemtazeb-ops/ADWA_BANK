<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Account;
use Illuminate\Support\Facades\DB;

class AccountController extends Controller
{
    public function index() {
        $accounts = Account::latest()->get();
        return view('admin.accounts.index', compact('accounts'));
    }

    public function create() {
        return view('admin.accounts.create');
    }

    public function store(Request $request) {
        $request->validate([
            'full_name' => 'required',
            'phone_number' => 'required|unique:accounts',
            'balance' => 'required|numeric|min:100',
            'pin' => 'required|digits:4',
        ]);

        $lastAccount = Account::latest()->first();
        $nextId = $lastAccount ? $lastAccount->id + 1 : 1;
        $accountNumber = "25000" . str_pad($nextId, 4, "0", STR_PAD_LEFT);

        Account::create([
            'full_name' => $request->full_name,
            'phone_number' => $request->phone_number,
            'account_number' => $accountNumber,
            'balance' => $request->balance,
            'pin' => $request->pin,
        ]);

        return redirect()->route('admin.accounts.index')->with('success', "አዲስ አካውንት ተከፍቷል!");
    }

    // አዲሱ፦ ስም ፈልጎ የሚያመጣ ፋንክሽን
    public function searchAccount($account_number) {
        $account = Account::where('account_number', $account_number)->first();
        if ($account) {
            return response()->json(['success' => true, 'name' => $account->full_name]);
        }
        return response()->json(['success' => false]);
    }

    public function transferForm() {
        return view('admin.accounts.transfer');
    }

    public function doTransfer(Request $request) {
        $request->validate([
            'sender_account' => 'required|exists:accounts,account_number',
            'receiver_account' => 'required|exists:accounts,account_number',
            'amount' => 'required|numeric|min:1',
            'pin' => 'required|digits:4',
        ]);

        $sender = Account::where('account_number', $request->sender_account)->first();
        $receiver = Account::where('account_number', $request->receiver_account)->first();

        if ($sender->pin !== $request->pin) {
            return back()->withErrors(['pin' => 'የተሳሳተ ሚስጥር ቁጥር (PIN) አስገብተዋል!']);
        }

        if ($sender->balance < $request->amount) {
            return back()->withErrors(['amount' => 'በቂ ባላንስ የሎትም!']);
        }

        DB::transaction(function () use ($sender, $receiver, $request) {
            $sender->decrement('balance', $request->amount);
            $receiver->increment('balance', $request->amount);
        });

        return redirect()->route('admin.accounts.index')
            ->with('success', "{$request->amount} ብር ለ {$receiver->full_name} በስኬት ተላልፏል!");
    }
}

