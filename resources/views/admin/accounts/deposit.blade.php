<!DOCTYPE html>
<html lang="am">
<head>
    <meta charset="UTF-8">
    <title>ADWA BANK - DEPOSIT</title>
    <style>
        body { font-family: sans-serif; background: #f8f9fa; padding: 30px; }
        .container { background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 15px rgba(0,0,0,0.1); max-width: 500px; margin: auto; }
        .error-msg { color: red; font-size: 14px; margin-top: 5px; }
        input { width: 100%; padding: 12px; margin: 10px 0; border: 1px solid #ccc; border-radius: 5px; box-sizing: border-box; }
        .btn { width: 100%; padding: 12px; background: #27ae60; color: white; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; }
    </style>
</head>
<body>
<div class="container">
    <h2>💰 ብር ማስቀመጫ (DEPOSIT)</h2>
    <form action="{{ route('admin.accounts.doDeposit') }}" method="POST">
        @csrf
        <label>የአካውንት ቁጥር:</label>
        <input type="text" name="account_number" value="{{ old('account_number') }}" required>
        @error('account_number') <p class="error-msg">{{ $message }}</p> @enderror

        <label>የብር መጠን (ETB):</label>
        <input type="number" name="amount" required>
        @error('amount') <p class="error-msg">{{ $message }}</p> @enderror

        <button type="submit" class="btn">አረጋግጥና አስቀምጥ</button>
    </form>
</div>
</body>
</html>
