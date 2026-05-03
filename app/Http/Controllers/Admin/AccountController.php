<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AccountController extends Controller {

    // --- Admin Dashboard ---
    public function dashboard() {
        $totalUsers = Account::count();
        $totalBalance = Account::sum('balance');
        $todayTransactionsCount = Transaction::whereDate('created_at', today())->count();
        $recentTransactions = Transaction::latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalUsers', 'totalBalance', 'todayTransactionsCount', 'recentTransactions'
        ));
    }

    // --- ደንበኞችን በዝርዝር ማሳያ (Index) ---
    public function index(Request $request) {
        $search = $request->input('search');

        $accounts = Account::when($search, function ($query, $search) {
            return $query->where('full_name', 'LIKE', "%{$search}%")
                         ->orWhere('account_number', 'LIKE', "%{$search}%")
                         ->orWhere('phone_number', 'LIKE', "%{$search}%");
        })->latest()->paginate(10);

        $transactions = Transaction::latest()->take(10)->get();

        return view('admin.accounts.index', compact('accounts', 'transactions', 'search'));
    }

    // --- የደንበኛ ዝርዝር መረጃ ማሳያ (Show) ---
    public function show($id) {
        $account = Account::findOrFail($id);
        $recentTransactions = Transaction::where('account_number', $account->account_number)
                                        ->latest()
                                        ->take(10)
                                        ->get();

        return view('admin.accounts.show', compact('account', 'recentTransactions'));
    }

    public function create() { return view('admin.accounts.create'); }
    public function depositForm() { return view('admin.accounts.deposit'); }
    public function withdrawForm() { return view('admin.accounts.withdraw'); }
    public function transferForm() { return view('admin.accounts.transfer'); }

    // --- አካውንት ቁጥር ሲገባ ስም እና ፎቶ ፍለጋ (AJAX) ---
    public function searchAccount($query) {
        $account = Account::where('account_number', $query)->first();

        if ($account) {
            return response()->json([
                'success' => true,
                'name' => $account->full_name,
                'photo' => $account->photo ? asset('storage/' . $account->photo) : null,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'አካውንቱ አልተገኘም!'
        ]);
    }

    // --- አዲስ ደንበኛ መመዝገቢያ ---
    public function store(Request $request) {
        $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'phone_number' => 'required|unique:accounts,phone_number',
            'pin' => 'required|digits:4',
            'balance' => 'required|numeric|min:100',
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        do {
            $generatedNumber = '1896' . mt_rand(100000000, 999999999);
        } while (Account::where('account_number', $generatedNumber)->exists());

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $photoPath = $file->storeAs('photos', $fileName, 'public');
        }

        DB::transaction(function () use ($request, $generatedNumber, $photoPath) {
            Account::create([
                'full_name' => $request->full_name,
                'account_number' => $generatedNumber,
                'phone_number' => $request->phone_number,
                'pin' => Hash::make($request->pin),
                'balance' => $request->balance,
                'photo' => $photoPath,
            ]);

            Transaction::create([
                'account_number' => $generatedNumber,
                'type' => 'Deposit',
                'amount' => $request->balance,
                'description' => 'Initial Deposit',
                'reference_number' => 'OPN' . strtoupper(Str::random(10)),
            ]);
        });

        return redirect()->route('admin.accounts.index')->with('success', "አዲስ ደንበኛ ተመዝግቧል!");
    }

    // --- የደንበኛ መረጃ ማስተካከያ (Edit) ---
    public function edit($id) {
        $account = Account::findOrFail($id);
        return view('admin.accounts.edit', compact('account'));
    }

    // --- መረጃ ማዘመኛ (Update) ---
    public function update(Request $request, $id) {
        $account = Account::findOrFail($id);
        $request->validate([
            'full_name' => 'required|string|max:255',
            'phone_number' => 'required|unique:accounts,phone_number,' . $id,
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $account->full_name = $request->full_name;
        $account->phone_number = $request->phone_number;

        if ($request->hasFile('photo')) {
            if ($account->photo) { Storage::disk('public')->delete($account->photo); }
            $file = $request->file('photo');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $account->photo = $file->storeAs('photos', $fileName, 'public');
        }

        $account->save();
        return redirect()->route('admin.accounts.index')->with('success', "መረጃው ተዘምኗል!");
    }

    // --- የዝውውር ታሪክ (History) ---
    public function history($id) {
        $account = Account::findOrFail($id);
        $transactions = Transaction::where('account_number', $account->account_number)
                                    ->orWhere('receiver_account', $account->account_number)
                                    ->latest()
                                    ->paginate(15);
        return view('admin.accounts.history', compact('account', 'transactions'));
    }

    // --- ገንዘብ ማስተላለፊያ (doTransfer) ---
    public function doTransfer(Request $request) {
        $request->validate([
            'from_account' => 'required',
            'to_account' => 'required',
            'amount' => 'required|numeric|min:1',
            'pin' => 'required|digits:4',
        ]);

        $fromAccNo = str_replace(' ', '', $request->from_account);
        $toAccNo = str_replace(' ', '', $request->to_account);
        $from = Account::where('account_number', $fromAccNo)->first();
        $to = Account::where('account_number', $toAccNo)->first();

        if (!$from || !Hash::check($request->pin, $from->pin)) {
            return back()->withErrors(['pin' => 'የሚስጥር ቁጥር ስህተት ነው!']);
        }

        if (($from->balance - $request->amount) < 100) {
            return back()->withErrors(['amount' => 'ቀሪ ሂሳብ ከ 100 ብር በታች መሆን አይችልም!']);
        }

        DB::transaction(function () use ($from, $request, $toAccNo, $to) {
            $from->decrement('balance', $request->amount);

            if ($to) {
                $to->increment('balance', $request->amount);
                Transaction::create([
                    'account_number' => $to->account_number,
                    'type' => 'Transfer In',
                    'amount' => $request->amount,
                    'description' => "ከ {$from->full_name} የገባ",
                    'reference_number' => 'TRI' . strtoupper(Str::random(10)),
                ]);
            }

            Transaction::create([
                'account_number' => $from->account_number,
                'type' => 'Transfer Out',
                'amount' => $request->amount,
                'receiver_account' => $toAccNo,
                'description' => "ወደ አካውንት {$toAccNo} የተላከ",
                'reference_number' => 'TRO' . strtoupper(Str::random(10)),
            ]);
        });

        // እዚህ ጋር የተቀባዩን ፎቶ እና አካውንት ቁጥር ወደ index page በ session ልከናል
        return redirect()->route('admin.accounts.index')
            ->with('success', "ዝውውሩ ተሳክቷል!")
            ->with('name', $from->full_name)
            ->with('photo', $from->photo)
            ->with('phone', $from->phone_number)
            ->with('amount', $request->amount)
            ->with('type', 'Transfer')
            ->with('receiver', $to ? $to->full_name : $toAccNo)
            ->with('receiver_acc', $toAccNo)
            ->with('receiver_photo', $to ? $to->photo : null);
    }

    // --- ገቢ ማድረጊያ (doDeposit) ---
    public function doDeposit(Request $request) {
        $request->validate(['account_number' => 'required', 'amount' => 'required|numeric|min:1']);
        $accNo = str_replace(' ', '', $request->account_number);
        $account = Account::where('account_number', $accNo)->first();

        if (!$account) return back()->withErrors(['account_number' => 'አካውንቱ አልተገኘም!']);

        DB::transaction(function () use ($account, $request) {
            $account->increment('balance', $request->amount);
            Transaction::create([
                'account_number' => $account->account_number,
                'type' => 'Deposit',
                'amount' => $request->amount,
                'description' => 'ገንዘብ ገቢ ተደርጓል',
                'reference_number' => 'DEP' . strtoupper(Str::random(10)),
            ]);
        });

        return redirect()->route('admin.accounts.index')
            ->with('success', "ብር {$request->amount} ገቢ ተደርጓል!")
            ->with('name', $account->full_name)
            ->with('photo', $account->photo)
            ->with('phone', $account->phone_number)
            ->with('amount', $request->amount)
            ->with('type', 'Deposit');
    }

    // --- ወጪ ማድረጊያ (doWithdraw) ---
    public function doWithdraw(Request $request) {
        $request->validate(['account_number' => 'required', 'amount' => 'required|numeric|min:1', 'pin' => 'required|digits:4']);
        $accNo = str_replace(' ', '', $request->account_number);
        $account = Account::where('account_number', $accNo)->first();

        if (!$account || !Hash::check($request->pin, $account->pin)) {
            return back()->withErrors(['pin' => 'የሚስጥር ቁጥር ስህተት ነው!']);
        }

        DB::transaction(function () use ($account, $request) {
            $account->decrement('balance', $request->amount);
            Transaction::create([
                'account_number' => $account->account_number,
                'type' => 'Withdraw',
                'amount' => $request->amount,
                'description' => 'ገንዘብ ወጪ ተደርጓል',
                'reference_number' => 'WTH' . strtoupper(Str::random(10)),
            ]);
        });

        return redirect()->route('admin.accounts.index')
            ->with('success', "ብር {$request->amount} ወጪ ተደርጓል!")
            ->with('name', $account->full_name)
            ->with('photo', $account->photo)
            ->with('phone', $account->phone_number)
            ->with('amount', $request->amount)
            ->with('type', 'Withdraw');
    }

    // --- አካውንት ማጥፊያ (Delete) ---
    public function destroy($id) {
        $account = Account::findOrFail($id);
        if ($account->photo) { Storage::disk('public')->delete($account->photo); }
        Transaction::where('account_number', $account->account_number)->delete();
        $account->delete();

        return redirect()->route('admin.accounts.index')->with('success', 'አካውንቱ ተሰርዟል!');
    }
}
