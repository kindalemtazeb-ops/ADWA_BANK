<!DOCTYPE html>
<html lang="am">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADWA BANK - TRANSACTION HISTORY</title>
    <style>
        body { font-family: sans-serif; background: #f4f7f6; padding: 20px; color: #333; }
        .container {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            max-width: 1000px;
            margin: auto;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #eee;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        h2 { color: #2c3e50; margin: 0; }
        .account-info { background: #ecf0f1; padding: 10px 20px; border-radius: 8px; font-weight: bold; }

        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 15px; text-align: left; border-bottom: 1px solid #eee; }
        th { background: #34495e; color: white; text-transform: uppercase; font-size: 13px; }
        tr:hover { background: #f9f9f9; }

        .badge {
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .bg-deposit { background: #d4edda; color: #155724; }
        .bg-withdraw { background: #f8d7da; color: #721c24; }
        .bg-transfer { background: #fff3cd; color: #856404; }

        .amount { font-weight: bold; font-family: monospace; font-size: 16px; }
        .text-success { color: #27ae60; }
        .text-danger { color: #e74c3c; }

        .btn-print {
            padding: 6px 12px;
            background: #2980b9;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
        }
        .btn-print:hover { background: #21618c; }

        .btn-back {
            display: inline-block;
            padding: 10px 20px;
            background: #7f8c8d;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: 0.3s;
        }

        /* ለማተም ብቻ የሚጠቅም ስታይል */
        @media print {
            body * { visibility: hidden; }
            #printable-area, #printable-area * { visibility: visible; }
            #printable-area { position: absolute; left: 0; top: 0; width: 100%; }
            .btn-print, .btn-back { display: none; }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h2>📜 የግብይት ታሪክ</h2>
        <div class="account-info">
            {{ $account->account_number }} | {{ $account->full_name }}
        </div>
    </div>

    <div id="printable-area">
        <table>
            <thead>
                <tr>
                    <th>ቀን (Date)</th>
                    <th>ዓይነት (Type)</th>
                    <th>መጠን (Amount)</th>
                    <th>መግለጫ (Description)</th>
                    <th class="btn-print-column">ተግባር</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $trx)
                <tr>
                    <td>{{ $trx->created_at->format('M d, Y | H:i') }}</td>
                    <td>
                        <span class="badge {{ $trx->type == 'Deposit' || $trx->type == 'Transfer In' || $trx->type == 'Initial Deposit' ? 'bg-deposit' : ($trx->type == 'Withdraw' ? 'bg-withdraw' : 'bg-transfer') }}">
                            {{ $trx->type }}
                        </span>
                    </td>
                    <td class="amount {{ $trx->type == 'Withdraw' || $trx->type == 'Transfer Out' ? 'text-danger' : 'text-success' }}">
                        {{ $trx->type == 'Withdraw' || $trx->type == 'Transfer Out' ? '-' : '+' }}
                        {{ number_format($trx->amount, 2) }} ETB
                    </td>
                    <td>{{ $trx->description }}</td>
                    <td>
                        <button onclick="printReceipt('{{ $trx->type }}', '{{ number_format($trx->amount, 2) }}', '{{ $trx->created_at }}')" class="btn-print">🖨️ ደረሰኝ አትም</button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align: center; padding: 30px; color: #999;">ምንም አይነት የግብይት ታሪክ አልተገኘም!</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 30px; text-align: center;">
        <a href="{{ route('admin.accounts.index') }}" class="btn-back">← ወደ ዋናው ገጽ ተመለስ</a>
    </div>
</div>

<script>
    function printReceipt(type, amount, date) {
        const bankName = "ADWA BANK";
        const customer = "{{ $account->full_name }}";
        const accNo = "{{ $account->account_number }}";

        const receiptWindow = window.open('', '_blank', 'width=400,height=600');
        receiptWindow.document.write(`
            <html>
            <head>
                <title>Receipt - ${bankName}</title>
                <style>
                    body { font-family: sans-serif; padding: 20px; text-align: center; border: 2px solid #333; }
                    .header { font-weight: bold; font-size: 20px; border-bottom: 2px solid #333; padding-bottom: 10px; }
                    .details { text-align: left; margin-top: 20px; line-height: 1.6; }
                    .footer { margin-top: 30px; border-top: 1px dashed #333; padding-top: 10px; font-size: 12px; }
                    .amount { font-size: 18px; font-weight: bold; margin-top: 10px; }
                </style>
            </head>
            <body>
                <div class="header">${bankName}</div>
                <div class="details">
                    <strong>አካውንት ባለቤት:</strong> ${customer}<br>
                    <strong>አካውንት ቁጥር:</strong> ${accNo}<br>
                    <strong>የግብይት አይነት:</strong> ${type}<br>
                    <strong>ቀን:</strong> ${date}<br>
                </div>
                <div class="amount">መጠን: ${amount} ETB</div>
                <div class="footer">ስለተጠቀሙ እናመሰግናለን!<br>Adwa Bank - ዘመናዊ የባንክ አገልግሎት</div>
                <script>window.print(); window.close();<\/script>
            </body>
            </html>
        `);
        receiptWindow.document.close();
    }
</script>

</body>
</html>
