<!DOCTYPE html>
<html lang="am">
<head>
    <meta charset="UTF-8">
    <title>ADWA BANK RECEIPT</title>
    <style>
        body {
            font-family: 'Courier New', monospace;
            width: 380px;
            padding: 20px;
            border: 1px solid #000;
            margin: 20px auto;
            color: #000;
            line-height: 1.4;
            background-color: #fff;
        }
        .text-center { text-align: center; }
        hr { border: 0; border-top: 1px dashed #000; margin: 10px 0; }
        .row { display: flex; justify-content: space-between; margin-bottom: 5px; font-size: 13px; color: #000; }

        .profile-section {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 15px;
            border: 1px dashed #000;
            padding: 10px;
            background: #fff;
        }
        .profile-img {
            width: 70px;
            height: 70px;
            border: 1px solid #000;
            object-fit: cover;
            background: #eee;
        }
        .user-meta { font-size: 12px; text-align: left; color: #000; }
        .user-meta p { margin: 2px 0; }

        .amount-box {
            text-align: center;
            margin: 15px 0;
            padding: 10px;
            border: 1px solid #000;
            background: #fff;
            color: #000;
        }

        .receiver-box {
            background-color: #fff;
            padding: 10px;
            margin: 10px 0;
            border: 1px dashed #000;
            font-size: 12px;
            display: flex;
            align-items: center;
            gap: 15px;
            color: #000;
        }
        .receiver-img {
            width: 60px;
            height: 60px;
            border: 1px solid #000;
            border-radius: 50%;
            object-fit: cover;
            background: #eee;
        }
        .receiver-meta { text-align: left; }
        .receiver-meta p { margin: 2px 0; }

        .no-print {
            margin-top: 20px;
            width: 100%;
            display: flex;
            gap: 5px;
        }
        .btn {
            flex: 1;
            padding: 10px;
            font-weight: bold;
            cursor: pointer;
            border: 1px solid #000;
            text-align: center;
            text-decoration: none;
            font-size: 14px;
        }
        .btn-dark { background: #000; color: #fff; }
        .btn-light { background: #fff; color: #000; }

        @media print {
            .no-print { display: none !important; }
            body { border: none; margin: 0; width: 100%; background: #fff !important; color: #000 !important; }
        }
    </style>
</head>
<body>

    @php
        if (isset($sender) && $sender instanceof \Illuminate\Database\Eloquent\Collection) {
            $actualSender = $sender->first();
        } else {
            $actualSender = $sender;
        }
    @endphp

    <div class="text-center">
        <h2 style="margin: 0 0 5px 0; color: #000;">ADWA BANK 🏧</h2>
        <p style="margin: 0; font-weight: bold; color: #000;">የገንዘብ ዝውውር ማረጋገጫ</p>
    </div>

    <hr>

    <div class="profile-section">
        <img src="{{ isset($actualSender) && $actualSender->photo ? asset('storage/' . $actualSender->photo) : 'https://ui-avatars.com/api/?name=User&background=888&color=fff' }}" class="profile-img">
        <div class="user-meta">
            <p><strong>ባለቤት:</strong> {{ $actualSender->full_name ?? 'N/A' }}</p>
            <p><strong>አካውንት:</strong> {{ $actualSender->account_number ?? 'N/A' }}</p>
            <p><strong>ስልክ:</strong> {{ $actualSender->phone_number ?? 'N/A' }}</p>
        </div>
    </div>

    <div class="row">
        <span><strong>ቀን:</strong></span>
        <span>
            @php
                $date = isset($transaction->created_at) && $transaction->created_at instanceof \Carbon\Carbon
                        ? $transaction->created_at
                        : \Carbon\Carbon::parse($transaction->created_at ?? now());
            @endphp
            {{ $date->format('Y-m-d h:i A') }}
        </span>
    </div>

    <div class="row">
        <span><strong>የግብይት አይነት:</strong></span>
        <span style="text-transform: uppercase; font-weight: bold;">{{ $transaction->type ?? 'N/A' }}</span>
    </div>

    @if(!empty($receiver_acc) || isset($receiver_name))
    <div class="receiver-box">
        @php
            $display_name = isset($receiver_name) ? $receiver_name : (request()->input('receiver_name') ? request()->input('receiver_name') : 'CBE User');
            $display_phone = isset($receiver_phone) ? $receiver_phone : (request()->input('receiver_phone') ? request()->input('receiver_phone') : 'N/A');
            $display_acc = isset($receiver_acc) ? $receiver_acc : 'N/A';
        @endphp

        <img src="{{ isset($receiver) && $receiver->photo ? asset('storage/' . $receiver->photo) : 'https://ui-avatars.com/api/?name=' . urlencode($display_name) . '&background=888&color=fff' }}" class="receiver-img">
        <div class="receiver-meta">
            <p style="color: #333; font-weight: bold;">ተቀባይ (Receiver)</p>
            <p><strong>ስም:</strong> {{ $display_name }}</p>
            <p><strong>አካውንት:</strong> {{ $display_acc }}</p>
            <p><strong>ስልክ:</strong> {{ $display_phone }}</p>
        </div>
    </div>
    @endif

    <hr>

    <div class="amount-box">
        <p style="margin: 0; font-size: 12px; font-weight: bold;">የብር መጠን (Amount)</p>
        <h2 style="margin: 5px 0; font-size: 24px;">
            {{ number_format($transaction->amount ?? 0, 2) }} ETB
        </h2>
    </div>

    <hr>

    <div class="row" style="font-weight: bold; font-size: 14px;">
        <span>አሁናዊ ቀሪ ሂሳብ:</span>
        <span>{{ number_format($actualSender->balance ?? 0, 2) }} ETB</span>
    </div>

    <div class="text-center" style="margin-top: 25px; font-size: 11px; color: #000;">
        <p style="border: 1px solid #000; display: inline-block; padding: 3px 10px; margin: 0 0 10px 0; font-weight: bold;">ADWA-OFFICIAL-RECEIPT</p>
        <p style="margin: 5px 0;">ስላገለገልንዎ እናመሰግናለን!</p>
        <p style="margin: 5px 0; font-weight: bold;">*** ADWA BANK OF ETHIOPIA ***</p>
    </div>

    <div class="no-print">
        <button onclick="window.print()" class="btn btn-dark">Print Receipt</button>
        <button onclick="window.location.href='{{ route('admin.accounts.index') }}'" class="btn btn-light">Back to Index</button>
    </div>

</body>
</html>
