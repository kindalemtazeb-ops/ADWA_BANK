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
            max-width: 1000px;
            margin: auto;
            position: relative;
        }

        /* ርዕስ እና አርማ ክፍል */
        .header-section {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid var(--cbe-yellow);
            padding-bottom: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 20px;
        }

        .adwa {
            font-size: 70px;
            color: var(--cbe-purple);
            margin: 0;
            font-weight: 800;
            text-shadow: 10px 10px 15px rgba(2, 255, 100, 0.2);
            text-transform: uppercase;
            padding-top: -10px;
            padding-left: 2px;
           padding-bottom: 5px;
           padding-right: 5px;
           margin-right: 170px;
           margin-left: 20px;
           margin-top: -70px;
           margin-bottom: 5px;
        }

        .list {
            color: var(--cbe-purple);
            font-size: 30px;
            margin: 5px 0 20px 0;
            font-weight: 700;
        }

        .control {
            color: var(--cbe-yellow-dark);
            font-size: 18px;
            margin: 0;
            font-weight: bold;
        }

        /* ቶር እና ጋሻ አርማ */
        .adwa-logo {
            width: 100px;
            height: 100px;
            background-color: var(--cbe-purple);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 50px;
            color: var(--cbe-yellow);
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
            padding-top: -5px;
            padding-left: 2px;
           padding-bottom: 5px;
           padding-right: 5px;
           margin-right: 20px;
           margin-left: 20px;
           margin-top: -110px;
           margin-bottom: 5px;
        }

        /* Logout Button Style */
        .logout-container {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 10px;

        }
        .logout-btn {
            background: #e74c3c;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            transition: 0.3s;

        }
        .logout-btn:hover { background: #c0392b; }

        /* Search Bar Style */
        .search-container {
            margin-bottom: 25px;
            display: flex;
            justify-content: center;
        }
        .search-form {
            display: flex;
            width: 100%;
            max-width: 600px;
            gap: 5px;
        }
        .search-input {
            flex-grow: 1;
            padding: 12px 20px;
            border: 2px solid var(--cbe-purple);
            border-radius: 50px;
            outline: none;
        }
        .search-btn {
            padding: 10px 25px;
            background: var(--cbe-purple);
            color: white;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            font-weight: bold;
        }
        .search-btn:hover { background: var(--cbe-purple-dark); }

        /* Message Style */
        .success {
            padding: 15px;
            background: #d4edda;
            color: #155724;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 5px solid #28a745;
            font-weight: bold;
            line-height: 1.5;
        }

        /* Action Buttons Style */
        .action-buttons {
            margin-bottom: 30px;
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            justify-content: center;
        }
        .btn {
            padding: 12px 20px;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            font-size: 14px;
            transition: 0.3s;
        }
        .btn:hover { opacity: 0.8; }

        /* Table Style */
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            margin-bottom: 20px;
            border: 1px solid #eee;
        }
        th, td {
            padding: 18px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        th {
            background: var(--cbe-purple);
            color: white;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 1px;
        }
        .acc-code {
            background: #e8f5e9;
            padding: 4px 8px;
            border-radius: 4px;
            color: #2e7d32;
            font-family: monospace;
            font-weight: bold;
        }

        /* Pagination Styling */
        .pagination-wrapper {
            margin-top: 20px;
            padding: 10px;
        }
        .pagination-wrapper nav svg { width: 20px; }
    </style>
</head>
<body>

<div class="container">
    <div class="logout-container">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="logout-btn">
                🚪 Logout (ውጣ)
            </button>
        </form>
    </div>

    <div class="header-section">
        <div class="adwa-logo">🛡️⚔️</div>
        <div>
            <h1 class="adwa">ADWA BANK</h1>
            <h2 class="list">🏦 WELLCOME TO ADWA BANK OF ETHIOPIA</h2>
            <h2 class="control">የደንበኞች ዝርዝር መቆጣጠሪያ</h2>
        </div>
    </div>

    @if(session('success'))
        <div class="success">✅ {{ session('success') }}</div>
    @endif

    <div class="search-container">
        <form action="{{ route('admin.accounts.index') }}" method="GET" class="search-form">
            <input type="text" name="search" class="search-input" placeholder="ይፈልጉ..." value="{{ request('search') }}">
            <button type="submit" class="search-btn">🔍 ፈልግ</button>
        </form>
    </div>

    <div class="action-buttons">
        <a href="{{ route('admin.accounts.create') }}" class="btn" style="background: #27ae60;">+ አዲስ ደንበኛ</a>
        <a href="{{ route('admin.accounts.depositForm') }}" class="btn" style="background: var(--cbe-yellow); color: var(--cbe-purple);">💰 ብር አስገባ</a>
        <a href="{{ route('admin.accounts.withdrawForm') }}" class="btn" style="background: #e74c3c;">🏧 ብር አውጣ</a>
        <a href="{{ route('admin.accounts.transferForm') }}" class="btn" style="background: #e67e22;">💸 ብር ላክ</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>ሙሉ ስም</th>
                <th>የሂሳብ ቁጥር</th>
                <th>የተቀማጭ መጠን</th>
            </tr>
        </thead>
        <tbody>
            @forelse($accounts as $account)
            <tr>
                <td><strong>{{ $account->full_name }}</strong></td>
                <td><span class="acc-code">{{ $account->account_number }}</span></td>
                <td style="font-weight: 800; color: #27ae60;">{{ number_format($account->balance, 2) }} ETB</td>
            </tr>
            @empty
            <tr>
                <td colspan="3" style="text-align: center; color: #888; padding: 30px;">ምንም የተመዘገበ ደንበኛ አልተገኘም።</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="pagination-wrapper">
        {{ $accounts->appends(['search' => $search])->links() }}
    </div>
</div>

@if(session('print_receipt'))
<script>
    let amount = "{{ session('amount_withdrawn') }}";
    let receiptUrl = "{{ route('admin.accounts.receipt', session('print_receipt')) }}?amount=" + amount;
    window.open(receiptUrl, '_blank');
</script>
@endif

</body>
</html>

