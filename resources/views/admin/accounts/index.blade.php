<!DOCTYPE html>
<html lang="am">
<head>
    <meta charset="UTF-8">
    <title>አድዋ ባንክ - ዝርዝር</title>
    <style>
        body { font-family: sans-serif; background: #f8f9fa; padding: 30px; }
        .container { background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 15px rgba(0,0,0,0.1); }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 15px; text-align: left; border-bottom: 1px solid #eee; }
        th { background: #2c3e50; color: white; }
        .success { padding: 15px; background: #d4edda; color: #155724; border-radius: 5px; margin-bottom: 20px; font-weight: bold; }
        .btn { padding: 10px 20px; color: white; text-decoration: none; border-radius: 5px; font-weight: bold; }
    </style>
</head>
<body>

<div class="container">
    <h2>የአድዋ ባንክ ደንበኞች ዝርዝር</h2>

    @if(session('success'))
        <div class="success">✅ {{ session('success') }}</div>
    @endif

    <a href="{{ route('admin.accounts.create') }}" class="btn" style="background: #27ae60;">+ አዲስ ደንበኛ</a>
    <a href="{{ route('admin.accounts.transfer') }}" class="btn" style="background: #e67e22; margin-left: 10px;">💸 ብር ላክ (Transfer)</a>

    <table>
        <thead>
            <tr>
                <th>ሙሉ ስም</th>
                <th>አካውንት ቁጥር</th>
                <th>ባላንስ (ETB)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($accounts as $account)
            <tr>
                <td>{{ $account->full_name }}</td>
                <td><strong>{{ $account->account_number }}</strong></td>
                <td>{{ number_format($account->balance, 2) }} ETB</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

</body>
</html>
