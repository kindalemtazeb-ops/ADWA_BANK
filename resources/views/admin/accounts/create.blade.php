<!DOCTYPE html>
<html lang="am">
<head>
    <meta charset="UTF-8">
    <title>አዲስ ደንበኛ መመዝገቢያ</title>
    <style>
        body { font-family: sans-serif; background: #f4f4f4; padding: 20px; }
        .form-container { background: white; padding: 25px; max-width: 450px; margin: auto; border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        h2 { text-align: center; color: #2c3e50; }
        input { width: 100%; padding: 12px; margin: 8px 0; border: 1px solid #ddd; border-radius: 6px; box-sizing: border-box; }
        label { font-weight: bold; font-size: 14px; color: #34495e; display: block; margin-top: 10px; }
        .btn { background: #27ae60; color: white; border: none; padding: 12px; width: 100%; border-radius: 6px; font-weight: bold; cursor: pointer; font-size: 16px; margin-top: 20px; }
        .btn:hover { background: #219150; }
        .back-link { display: block; text-align: center; margin-top: 15px; color: #3498db; text-decoration: none; }
        .error-msg { color: #e74c3c; font-size: 12px; margin-bottom: 5px; display: block; font-weight: bold; }
        .info-text { font-size: 12px; color: #7f8c8d; font-style: italic; margin-bottom: 10px; display: block; }
        .is-invalid { border: 1px solid #e74c3c; }

        .file-input-wrapper {
            background: #f9f9f9;
            border: 2px dashed #cbd5e0;
            padding: 10px;
            border-radius: 8px;
            text-align: center;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>➕ አዲስ ደንበኛ መመዝገቢያ</h2>

        <form action="{{ route('admin.accounts.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <label>የደንበኛው ፎቶ</label>
            <div class="file-input-wrapper">
                <input type="file" name="photo" accept="image/*" class="{{ $errors->has('photo') ? 'is-invalid' : '' }}" required>
                <span class="info-text">የደንበኛውን መታወቂያ ፎቶ እዚህ ይጫኑ</span>
            </div>
            @error('photo')
                <span class="error-msg">{{ $message }}</span>
            @enderror

            <label>ሙሉ ስም (የራስ፣ የአባት እና የአያት ስም)</label>
            <!--
                አዲስ የተስተካከለ pattern: ^[A-Za-z]+(\s+[A-Za-z]+){2,}$
                ይህ ቢያንስ 3 ስሞች እንዲኖሩ እና ቁጥር እንዳይገባ ይከለክላል።
            -->
            <input type="text"
                   name="full_name"
                   class="{{ $errors->has('full_name') ? 'is-invalid' : '' }}"
                   placeholder="ለምሳሌ፡ Abebe Kebede Alamu"
                   value="{{ old('full_name') }}"
                   pattern="^[A-Za-z]+(\s+[A-Za-z]+){2,}$"
                   title="እባክዎ ፊደላትን ብቻ በመጠቀም የራስዎን፣ የአባትዎን እና የአያትዎን ስም ያስገቡ (ቁጥር አይፈቀድም)"
                   required>
            <span class="info-text" style="color: #2980b9;">* ሶስቱንም ስም ያለ ቁጥር ማስገባት ግዴታ ነው።</span>
            @error('full_name')
                <span class="error-msg">{{ $message }}</span>
            @enderror

            <label>የአካውንት ቁጥር</label>
            <input type="text" value="በራሱ ይመነጫል (1896XXXXXXXXX)" readonly style="background: #eee; cursor: not-allowed;">
            <span class="info-text">ማሳሰቢያ፡ ቁጥሩ በሲስተሙ በራስ-ሰር ይፈጠራል።</span>

            <label>ስልክ ቁጥር</label>
            <input type="text"
                   name="phone_number"
                   class="{{ $errors->has('phone_number') ? 'is-invalid' : '' }}"
                   placeholder="09XXXXXXXX"
                   value="{{ old('phone_number') }}"
                   pattern="^(09|07)\d{8}$"
                   title="ትክክለኛ የኢትዮጵያ ስልክ ቁጥር ያስገቡ (በ09 ወይም 07 የሚጀምር)"
                   required>
            @error('phone_number')
                <span class="error-msg">{{ $message }}</span>
            @enderror

            <label>ሚስጥር ቁጥር (4 ድጂት)</label>
            <input type="password" name="pin" class="{{ $errors->has('pin') ? 'is-invalid' : '' }}" placeholder="****" maxlength="4" pattern="\d{4}" title="እባክዎ 4 አሃዝ ብቻ ያስገቡ" required>
            @error('pin')
                <span class="error-msg">{{ $message }}</span>
            @enderror

            <label>የመጀመሪያ ተቀማጭ (ብር)</label>
            <input type="number" name="balance" class="{{ $errors->has('balance') ? 'is-invalid' : '' }}" placeholder="0.00" value="{{ old('balance', 100) }}" min="100" required>
            @error('balance')
                <span class="error-msg">{{ $message }}</span>
            @enderror

            <button type="submit" class="btn">ደንበኛውን መዝግብ</button>
        </form>

        <a href="{{ route('admin.accounts.index') }}" class="back-link">ወደ ዝርዝር ተመለስ</a>
    </div>
</body>
</html>
