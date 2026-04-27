<!DOCTYPE html>
<html lang="am">
<head>
    <meta charset="UTF-8">
    <title>ADWA BANK RECEIPT</title>
    <style>
        body { font-family: 'Courier New', monospace; width: 350px; padding: 20px; border: 1px solid #000; margin: 30px auto; color: #000; line-height: 1.4; }
        .text-center { text-align: center; }
        hr { border: 0; border-top: 1px dashed #000; margin: 15px 0; }
        .row { display: flex; justify-content: space-between; margin-bottom: 8px; }
        .amount-box { text-align: center; margin: 20px 0; padding: 15px; border: 1px solid #eee; background: #f9f9f9; }
        .no-print { margin-top: 20px; width: 100%; padding: 10px; cursor: pointer; background: #333; color: white; border: none; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body onload="window.print()">
    <div class="text-center">
        <h2 style="margin-bottom: 5px;">ADWA BANK 🏧</h2>
        <p style="margin-top: 0;">የገንዘብ ማውጫ ደረሰኝ</p>
    </div>

    <hr>

    <div class="row">
        <span><strong>ቀን:</strong></span>
        <span>{{ now()->format('Y-m-d h:i A') }}</span>
    </div>
    <div class="row">
        <span><strong>ባለቤት:</strong></span>
        <span>{{ $account->full_name }}</span>
    </div>
    <div class="row">
        <span><strong>አካውንት:</strong></span>
        <span>{{ $account->account_number }}</span>
    </div>

    <hr>

    <div class="amount-box">
        <p style="margin: 0; font-size: 14px;">የወጣው የብር መጠን</p>
        <h2 style="margin: 10px 0;">
            {{ request('amount') ? number_format(request('amount'), 2) : '0.00' }} ETB
        </h2>
    </div>

    <hr>

    <div class="row" style="font-weight: bold; font-size: 1.1em;">
        <span>ቀሪ ሂሳብ:</span>
        <span>{{ number_format($account->balance, 2) }} ETB</span>
    </div>

    <div class="text-center" style="margin-top: 30px; font-size: 0.9em;">
        <p>ስላገለገልንዎ እናመሰግናለን!</p>
        <p>*** ADWA BANK OF ETHIOPIA ***</p>
    </div>

    <button class="no-print" onclick="window.close()">ዝጋ (Close)</button>
</body>
</html>
