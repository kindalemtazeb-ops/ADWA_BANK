<!DOCTYPE html>
<html lang="am">
<head>
    <meta charset="UTF-8">
    <title>አድዋ ባንክ - መመዝገቢያ</title>
    <style>
        body { font-family: sans-serif; background: #f4f4f4; padding: 40px; }
        .form-card { background: white; padding: 30px; border-radius: 10px; max-width: 450px; margin: auto; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        input { width: 100%; padding: 12px; margin: 8px 0; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box; }
        button { width: 100%; padding: 12px; background: #2c3e50; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; }
        .error-list { color: #721c24; background: #f8d7da; padding: 10px; border-radius: 5px; margin-bottom: 15px; font-size: 14px; }
    </style>
</head>
<body>

<div class="form-card">
    <h2>አድዋ ባንክ - ደንበኛ መመዝገቢያ</h2>

    @if ($errors->any())
        <div class="error-list">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </div>
    @endif

    <form action="{{ route('admin.accounts.store') }}" method="POST">
        @csrf
        <label>የደንበኛው ሙሉ ስም:</label>
        <input type="text" name="full_name" value="{{ old('full_name') }}" required>

        <label>ስልክ ቁጥር:</label>
        <input type="text" name="phone_number" value="{{ old('phone_number') }}" placeholder="09..." required>

        <label>መነሻ ተቀማጭ (ቢያንስ 100 ETB):</label>
        <input type="number" name="balance" value="{{ old('balance', 100) }}" required>

        <label>ሚስጥር ቁጥር (4 አሃዝ):</label>
        <input type="password" name="pin" maxlength="4" required>

        <button type="submit">አካውንት ክፈት</button>
    </form>
</div>

</body>
</html>
