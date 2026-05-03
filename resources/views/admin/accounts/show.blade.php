<!DOCTYPE html>
<html lang="am">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADWA BANK - Customer Details</title>
    <style>
        :root {
            --cbe-purple: #4B1A60;
            --cbe-yellow: #FDB913;
        }
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f0f2f5;
            padding: 20px;
        }
        .container {
            max-width: 900px;
            margin: auto;
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        .header {
            border-bottom: 3px solid var(--cbe-yellow);
            padding-bottom: 15px;
            margin-bottom: 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .profile-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            background: #f9f9f9;
            padding: 20px;
            border-radius: 10px;
            border: 1px solid #ddd;
        }
        .info-box h4 { margin: 0; color: #666; font-size: 13px; text-transform: uppercase; }
        .info-box p { margin: 5px 0 0; font-size: 18px; font-weight: bold; color: var(--cbe-purple); }

        .transaction-section { margin-top: 40px; }
        .section-title {
            background: var(--cbe-purple);
            color: white;
            padding: 10px 15px;
            border-radius: 5px;
            margin-bottom: 15px;
            font-weight: bold;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }
        th, td {
            padding: 12px;
            border-bottom: 1px solid #eee;
            text-align: left;
        }
        th { background: #f4f4f4; color: #333; }
        .badge {
            padding: 4px 8px;
            border-radius: 5px;
            font-size: 11px;
            font-weight: bold;
        }
        .deposit { background: #e8f5e9; color: #2e7d32; }
        .withdraw { background: #ffebee; color: #c62828; }
        .back-btn {
            display: inline-block;
            margin-bottom: 20px;
            text-decoration: none;
            color: var(--cbe-purple);
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="container">
    <a href="{{ route('admin.accounts.index') }}" class="back-btn">⬅️ ወደ ዝርዝር ተመለስ</a>

    <div class="header">
        <h2 style="color: var(--cbe-purple); margin: 0;">የደንበኛ መረጃ (Customer Details)</h2>
        <div style="background: var(--cbe-purple); color: var(--cbe-yellow); padding: 5px 15px; border-radius: 50px; font-weight: bold;">
            ADWA BANK
        </div>
    </div>

    <!-- የደንበኛ ዋና መረጃ -->
    <div class="profile-info">
        <div class="info-box">
            <h4>ሙሉ ስም</h4>
            <p>{{ $account->full_name }}</p>
        </div>
        <div class="info-box">
            <h4>የአካውንት ቁጥር</h4>
            <p style="color: #2e7d32;">{{ $account->account_number }}</p>
        </div>
        <div class="info-box">
            <h4>የስልክ ቁጥር</h4>
            <p>{{ $account->phone_number }}</p>
        </div>
        <div class="info-box">
            <h4>አሁን ያለው ቀሪ ሂሳብ</h4>
            <p style="font-size: 24px;">{{ number_format($account->balance, 2) }} ETB</p>
        </div>
    </div>

    <!-- የዚህ ደንበኛ ግብይት ታሪክ -->
    <div class="transaction-section">
        <div class="section-title">📜 የግብይት ታሪክ (Transaction History)</div>
        <table>
            <thead>
                <tr>
                    <th>ቀን</th>
                    <th>አይነት</th>
                    <th>መጠን</th>
                    <th>መግለጫ</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $transaction)
                <tr>
                    <td>{{ $transaction->created_at->format('M d, Y') }}</td>
                    <td>
                        <span class="badge {{ $transaction->type == 'Deposit' || $transaction->type == 'Transfer In' ? 'deposit' : 'withdraw' }}">
                            {{ $transaction->type }}
                        </span>
                    </td>
                    <td style="font-weight: bold;">
                        {{ number_format($transaction->amount, 2) }} ETB
                    </td>
                    <td style="color: #666; font-style: italic;">{{ $transaction->description }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="text-align: center; padding: 20px; color: #999;">እስካሁን የተመዘገበ እንቅስቃሴ የለም።</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div style="margin-top: 20px;">
            {{ $transactions->links() }}
        </div>
    </div>
</div>

</body>
</html>

