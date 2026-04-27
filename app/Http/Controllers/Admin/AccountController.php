<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Account;
use Illuminate\Support\Facades\DB;

class AccountController extends Controller {

    public function index(Request $request) {
        $search = $request->input('search');

        // ደንበኞች ሲበዙ በየገጹ 10 እንዲታዩ paginate(10) ተጨምሯል
        $accounts = Account::when($search, function ($query, $search) {
            return $query->where('full_name', 'LIKE', "%{$search}%")
                         ->orWhere('account_number', 'LIKE', "%{$search}%")
                         ->orWhere('phone_number', 'LIKE', "%{$search}%");
        })->latest()->paginate(10);

        return view('admin.accounts.index', compact('accounts', 'search'));
    }

    public function create() { return view('admin.accounts.create'); }

    public function store(Request $request) {
        $request->validate([
            'full_name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\x{1200}-\x{137F}]++(?:\s++[a-zA-Z\x{1200}-\x{137F}]++){2,}$/u'],
            'phone_number' => 'required|unique:accounts,phone_number',
            'pin' => 'required|digits:4',
            'balance' => 'required|numeric|min:100', // ማስተካከያ፡ አዲስ ለመመዝገብ ቢያንስ 100 ብር ያስፈልጋል
        ], [
            'balance.min' => 'አዲስ ደንበኛ ለመመዝገብ ቢያንስ 100 ብር ማስገባት ግዴታ ነው።',
        ]);

        do {
            $generatedNumber = '1896' . mt_rand(100000000, 999999999);
        } while (Account::where('account_number', $generatedNumber)->exists());

        Account::create([
            'full_name' => $request->full_name,
            'account_number' => $generatedNumber,
            'phone_number' => $request->phone_number,
            'pin' => $request->pin,
            'balance' => $request->balance,
        ]);

        return redirect()->route('admin.accounts.index')->with('success', "አዲስ ደንበኛ በቁጥር $generatedNumber በትክክል ተመዝግቧል!");
    }

    public function depositForm() { return view('admin.accounts.deposit'); }

    public function doDeposit(Request $request) {
        $request->validate([
            'account_number' => 'required|digits:13',
            'amount' => 'required|numeric|min:1'
        ]);

        $account = Account::where('account_number', trim($request->account_number))->first();
        if (!$account) return back()->withErrors(['account_number' => 'አካውንቱ አልተገኘም!']);

        $account->increment('balance', $request->amount);

        return redirect()->route('admin.accounts.index')->with('success',
            number_format($request->amount, 2) . " ብር ለ " . $account->full_name . " ($account->account_number) ገቢ ሆኗል!");
    }

    public function withdrawForm() { return view('admin.accounts.withdraw'); }

    public function doWithdraw(Request $request) {
        $request->validate([
            'account_number' => 'required|digits:13',
            'amount' => 'required|numeric|min:1',
            'pin' => 'required|digits:4',
        ]);

        $account = Account::where('account_number', trim($request->account_number))->first();

        if (!$account) return back()->withErrors(['account_number' => 'አካውንቱ አልተገኘም!']);
        if ($account->pin !== $request->pin) return back()->withErrors(['pin' => 'የገቡት ሚስጥር ቁጥር ስህተት ነው!']);

        // 1. መጀመሪያ የሂሳቡ መጠን ከ 100 ብር በታች መሆኑን ማረጋገጥ
        if ($account->balance < 100) return back()->withErrors(['amount' => 'የአካውንትዎ መጠን ከ 100 ብር በታች ስለሆነ ብር ማውጣት አይችሉም!']);

        // 2. ተቀንሶ የሚቀረው ሂሳብ ከ 100 ብር በታች እንዳይሆን መከላከል
        if (($account->balance - $request->amount) < 100) return back()->withErrors(['amount' => 'ዝቅተኛ የሂሳብ መጠን 100 ብር መቅረት አለበት!']);

        $account->decrement('balance', $request->amount);

        return redirect()->route('admin.accounts.receipt', ['id' => $account->id, 'amount' => $request->amount])
            ->with('success', number_format($request->amount, 2) . " ብር ከ " . $account->full_name . " ወጪ ሆኗል!");
    }

    public function transferForm() { return view('admin.accounts.transfer'); }

    public function doTransfer(Request $request) {
        $request->validate([
            'from_account' => 'required|digits:13',
            'to_account' => 'required|digits:13',
            'amount' => 'required|numeric|min:1',
            'pin' => 'required|digits:4',
        ]);

        $from_acc = trim($request->from_account);
        $to_acc = trim($request->to_account);

        $from = Account::where('account_number', $from_acc)->first();
        $to = Account::where('account_number', $to_acc)->first();

        if (!$from) return back()->withErrors(['from_account' => 'መነሻ አካውንቱ አልተገኘም!']);
        if (!$to) return back()->withErrors(['to_account' => 'መድረሻ አካውንቱ አልተገኘም!']);
        if ($from->pin !== $request->pin) return back()->withErrors(['pin' => 'ሚስጥር ቁጥር ስህተት ነው!']);
        if ($from->account_number === $to->account_number) return back()->withErrors(['to_account' => 'ወደ ራስዎ አካውንት መላክ አይችሉም!']);

        if ($from->balance < 100) return back()->withErrors(['amount' => 'የአካውንትዎ መጠን ከ 100 ብር በታች ስለሆነ ብር መላክ አይችሉም!']);

        if (($from->balance - $request->amount) < 100) return back()->withErrors(['amount' => 'ዝውውሩ ከተፈጸመ በኋላ ሂሳብዎ ከ 100 ብር በታች ስለሚሆን አይፈቀድም!']);

        DB::transaction(function () use ($from, $to, $request) {
            $from->decrement('balance', $request->amount);
            $to->increment('balance', $request->amount);
        });

        return redirect()->route('admin.accounts.index')->with('success',
            number_format($request->amount, 2) . " ብር ከ " . $from->full_name . " ወደ " . $to->full_name . " በትክክል ተላልፏል!");
    }

    public function searchAccount($account_number) {
        $account = Account::where('account_number', trim($account_number))->first();
        return response()->json(['success' => !!$account, 'name' => $account ? $account->full_name : '']);
    }

    public function receipt($id, Request $request) {
        $account = Account::findOrFail($id);
        $amount = $request->query('amount');

        return view('admin.accounts.receipt', compact('account', 'amount'));
    }
}
