<!DOCTYPE html>
<html lang="am">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADWA BANK - CUSTOMER LIST</title>
    <style>
        /* የንግድ ባንክ (CBE) የቀለም ስታይል */
        :root {
            --cbe-purple: #4B1A60;
            --cbe-purple-dark: #311b92;
            --cbe-yellow: #FDB913;
            --cbe-yellow-dark: #fca311;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f4f7f6;
            padding: 20px;
            color: #333;
        }

        .container {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 25px rgba(0,0,0,0.1);
            max-width: 1150px;
            margin: auto;
            position: relative;
        }

        .header-section {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid var(--cbe-yellow);
            padding-bottom: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 20px;
            position: relative;
        }

        .logout-container {
            position: absolute;
            right: 0;
            top: 0;
        }

        .logout-btn {
            background: #e74c3c;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            transition: 0.3s;
        }

        .logout-btn:hover {
            background: #c0392b;
        }

        .adwa {
            font-size: 70px;
            color: var(--cbe-purple);
            margin: 0;
            font-weight: 800;
            text-transform: uppercase;
        }

        /* የፍለጋ ሳጥን ስታይል */
        .search-container {
            margin-bottom: 25px;
            display: flex;
            justify-content: center;
        }

        .search-form {
            display: flex;
            width: 100%;
            max-width: 600px;
            gap: 10px;
        }

        .search-input {
            flex-grow: 1;
            padding: 12px 15px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            outline: none;
            transition: 0.3s;
        }

        .search-input:focus {
            border-color: var(--cbe-purple);
        }

        .search-btn {
            background: var(--cbe-purple);
            color: white;
            border: none;
            padding: 0 25px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
        }

        .clear-btn {
            background: #95a5a6;
            color: white;
            text-decoration: none;
            padding: 12px 15px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            font-weight: bold;
        }

        .success-box {
            padding: 20px;
            background: #d4edda;
            color: #155724;
            border-radius: 10px;
            margin-bottom: 25px;
            border-left: 8px solid #28a745;
            font-weight: bold;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }

        .print-btn {
            background: var(--cbe-purple);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            transition: 0.3s;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            border: 1px solid #eee;
        }
        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        th { background: var(--cbe-purple); color: white; }

        .customer-photo { width: 45px; height: 45px; border-radius: 50%; object-fit: cover; border: 2px solid var(--cbe-yellow); }

        .action-buttons { margin-bottom: 30px; display: flex; gap: 10px; justify-content: center; flex-wrap: wrap; }
        .btn { padding: 12px 20px; color: white; text-decoration: none; border-radius: 8px; font-weight: bold; }

        .btn-sm {
            padding: 6px 12px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 13px;
            font-weight: bold;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        .edit-btn { background: #27ae60; color: white; }
        .history-btn { background: #2980b9; color: white; }
        .delete-btn { background: #e74c3c; color: white; border: none; cursor: pointer; }

        .btn-group {
            display: flex;
            gap: 8px;
            justify-content: center;
        }

        /* ደረሰኝ ስታይል (ለፕሪንት ብቻ) */
        .print-only-receipt { display: none; }

        @media print {
            body * { visibility: hidden; }
            .print-only-receipt, .print-only-receipt * { visibility: visible; }
            .print-only-receipt {
                display: block !important;
                position: absolute;
                left: 50%;
                top: 20px;
                transform: translateX(-50%);
                width: 350px;
                border: 1px solid #333;
                padding: 20px;
                background: white !important;
                color: black !important;
                font-family: 'Courier New', Courier, monospace;
            }
            .receipt-header { text-align: center; border-bottom: 1px dashed #333; padding-bottom: 10px; margin-bottom: 15px; }
            .receipt-row { display: flex; justify-content: space-between; margin: 8px 0; font-size: 14px; }
            .receipt-footer { text-align: center; border-top: 1px dashed #333; margin-top: 15px; padding-top: 10px; font-size: 12px; }
            @page { size: auto; margin: 5mm; }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header-section">
        <div class="logout-container">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-btn">
                    ውጣ (Logout)
                </button>
            </form>
        </div>

        <div>
            <h1 class="adwa">ADWA BANK</h1>
            <h2 style="color: var(--cbe-purple);">የአድዋ ባንክ የደንበኞች መቆጣጠሪያ</h2>
        </div>
    </div>

    @if(session('success'))
        <div class="success-box no-print">
            <div>
                <span style="font-size: 22px;">✅</span>
                {{ session('success') }}
            </div>
            <button onclick="window.print()" class="print-btn">
                📄 ደረሰኝ አትም (Print Receipt)
            </button>
        </div>

        <div class="print-only-receipt">
            <div class="receipt-header">
                <h2 style="margin:0;">ADWA BANK 🏦</h2>
                <small>የገንዘብ ዝውውር ማረጋገጫ</small>
            </div>

            <div style="text-align: center; margin-bottom: 10px;">
                @if(session('photo'))
                    <img src="{{ asset('storage/' . session('photo')) }}"
                         style="width: 70px; height: 70px; border-radius: 8px; border: 1px solid #000; object-fit: cover;">
                @endif
                <p style="margin: 5px 0; font-size: 12px;">{{ session('type') == 'Transfer' ? 'ላኪ' : 'ደንበኛ' }}</p>
            </div>

            <div class="receipt-body">
                <div class="receipt-row">
                    <span>ቀን:</span>
                    <span>{{ date('Y-m-d H:i A') }}</span>
                </div>
                <div class="receipt-row">
                    <span>{{ session('type') == 'Transfer' ? 'የላኪ ስም:' : 'የደንበኛ ስም:' }}</span>
                    <span style="font-weight: bold; text-transform: uppercase;">{{ session('name') }}</span>
                </div>
                <div class="receipt-row">
                    <span>ስልክ ቁጥር:</span>
                    <span>{{ session('phone') }}</span>
                </div>
                <div class="receipt-row">
                    <span>የአገልግሎት አይነት:</span>
                    <span style="font-weight: bold;">{{ session('type') }}</span>
                </div>
                <div class="receipt-row" style="border-top: 1px dashed #ccc; padding-top: 5px; margin-top: 10px;">
                    <span style="font-weight: bold;">መጠን (Amount):</span>
                    <span style="font-weight: bold;">{{ number_format(session('amount'), 2) }} ETB</span>
                </div>

                @if(session('type') == 'Transfer')
                <div style="border-top: 1px solid #000; margin-top: 15px; padding-top: 10px;">
                    <p style="text-align: center; font-weight: bold; margin-bottom: 10px;">የተቀባይ መረጃ</p>
                    <div style="text-align: center; margin-bottom: 10px;">
                        @if(session('receiver_photo'))
                            <img src="{{ asset('storage/' . session('receiver_photo')) }}"
                                 style="width: 60px; height: 60px; border-radius: 50%; border: 1px solid #000; object-fit: cover;">
                        @endif
                    </div>
                    <div class="receipt-row">
                        <span>ተቀባይ ስም:</span>
                        <span style="font-weight: bold;">{{ session('receiver') }}</span>
                    </div>
                    <div class="receipt-row">
                        <span>አካውንት ቁጥር:</span>
                        <span>{{ session('receiver_acc') }}</span>
                    </div>
                </div>
                @endif

                <div style="text-align: center; margin: 15px 0; border: 1px solid #000; padding: 5px;">
                    <strong>ADWA-OFFICIAL-RECEIPT</strong>
                </div>
            </div>
            <div class="receipt-footer">
                <p>ስላገለገሉን እናመሰግናለን!</p>
                <p>*** ADWA BANK OF ETHIOPIA ***</p>
            </div>
        </div>
    @endif

    <div class="search-container">
        <form action="{{ route('admin.accounts.index') }}" method="GET" class="search-form">
            <!-- በፍለጋ ሳጥኑ ላይ ፊደላት ብቻ እንዲገቡ pattern ተጨምሯል -->
            <input type="text"
                   name="search"
                   value="{{ request('search') }}"
                   placeholder="በስም፣ በአካውንት ወይም በስልክ ይፈልጉ..."
                   class="search-input">
            <button type="submit" class="search-btn">🔍 ፈልግ</button>

            @if(request('search'))
                <a href="{{ route('admin.accounts.index') }}" class="clear-btn">🔄 አጽዳ</a>
            @endif
        </form>
    </div>

    <div class="action-buttons">
        <a href="{{ route('admin.accounts.create') }}" class="btn" style="background: #27ae60;">+ አዲስ ደንበኛ</a>
        <a href="{{ route('admin.accounts.withdrawForm') }}" class="btn" style="background: #e74c3c;">🏧 ብር አውጣ</a>
        <a href="{{ route('admin.accounts.depositForm') }}" class="btn" style="background: #2980b9;">💰 ብር አክል</a>
        <a href="{{ route('admin.accounts.transferForm') }}" class="btn" style="background: #8e44ad;">🔄 ብር አስተላልፍ</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>ፎቶ</th>
                <th>ሙሉ ስም</th>
                <th>የሂሳብ ቁጥር</th>
                <th>ቀሪ ሂሳብ</th>
                <th style="text-align: center;">ድርጊቶች (Actions)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($accounts as $account)
            <tr>
                <td>
                    @if($account->photo)
                        <img src="{{ asset('storage/' . $account->photo) }}" class="customer-photo">
                    @else
                        <div class="customer-photo" style="background: #ccc; display: flex; align-items: center; justify-content: center; font-size: 10px;">No Photo</div>
                    @endif
                </td>
                <td><strong>{{ $account->full_name }}</strong></td>
                <td><code>{{ $account->account_number }}</code></td>
                <td style="color: #27ae60; font-weight: bold;">{{ number_format($account->balance, 2) }} ETB</td>
                <td>
                    <div class="btn-group">
                        <a href="{{ route('admin.accounts.history', $account->id) }}" class="btn-sm history-btn">
                            📜 ታሪክ
                        </a>
                        <a href="{{ route('admin.accounts.edit', $account->id) }}" class="btn-sm edit-btn">
                            ✏️ አስተካክል
                        </a>
                        <form action="{{ route('admin.accounts.destroy', $account->id) }}" method="POST" onsubmit="return confirm('ይህ አካውንት እስከነ ታሪኩ ይጠፋል። እርግጠኛ ነህ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-sm delete-btn">
                                🗑️ አጥፋ
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align: center; padding: 20px; color: #e74c3c;">
                    ምንም አይነት ደንበኛ አልተገኘም!
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 20px;">
        {{ $accounts->appends(['search' => request('search')])->links() }}
    </div>
</div>
</body>
</html>
