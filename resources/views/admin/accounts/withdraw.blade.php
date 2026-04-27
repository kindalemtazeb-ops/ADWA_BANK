<!DOCTYPE html>
<html lang="am">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADWA BANK - WITHDRAW</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f0f2f5; padding: 30px; }
        .container {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            max-width: 450px;
            margin: auto;
        }
        h2 { text-align: center; color: #c0392b; margin-bottom: 25px; border-bottom: 2px solid #f8d7da; padding-bottom: 10px; }
        .form-group { margin-bottom: 18px; }
        label { display: block; margin-bottom: 8px; font-weight: bold; color: #2c3e50; }
        input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-sizing: border-box;
            font-size: 15px;
            transition: 0.3s;
        }
        input:focus { border-color: #e74c3c; outline: none; box-shadow: 0 0 5px rgba(231, 76, 60, 0.2); }
        .btn {
            width: 100%;
            padding: 14px;
            background: #e74c3c;
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
            font-size: 16px;
            text-transform: uppercase;
            transition: 0.3s;
        }
        .btn:hover { background: #c0392b; }
        .error { color: #e74c3c; font-size: 13px; margin-top: 5px; font-weight: 500; }
        .account-name-display {
            margin-top: 5px;
            font-size: 14px;
            color: #27ae60;
            font-weight: bold;
            min-height: 20px;
        }
        .back-link { display: block; text-align: center; margin-top: 20px; text-decoration: none; color: #7f8c8d; font-size: 14px; }
        .back-link:hover { color: #2c3e50; }
    </style>
</head>
<body>

<div class="container">
    <h2>🏧 ብር ማውጫ (Withdraw)</h2>

    <form action="{{ route('admin.accounts.doWithdraw') }}" method="POST">
        @csrf

        <div class="form-group">
            <label>የአካውንት ቁጥር (Account Number):</label>
            <input type="text" id="account_number" name="account_number"
                   placeholder="ለምሳሌ፡ 250000001"
                   value="{{ old('account_number') }}" required>
            <div id="account_name" class="account-name-display"></div>
            @error('account_number') <div class="error">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label>የገንዘብ መጠን (Amount in ETB):</label>
            <input type="number" name="amount" placeholder="ማውጣት የሚፈልጉትን መጠን..." value="{{ old('amount') }}" required>
            @error('amount') <div class="error">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label>ሚስጥር ቁጥር (PIN):</label>
            <input type="password" name="pin" placeholder="የአካውንቱን 4 ድጂት ፒን ያስገቡ" maxlength="4" required>
            @error('pin') <div class="error">{{ $message }}</div> @enderror
        </div>

        <button type="submit" class="btn">አሁኑኑ አውጣ (Withdraw Now)</button>

        <a href="{{ route('admin.accounts.index') }}" class="back-link">← ወደ ዝርዝር ተመለስ</a>
    </form>
</div>

<script>
    // አካውንት ቁጥር ሲጻፍ ስም ፈልጎ ለማምጣት
    document.getElementById('account_number').addEventListener('input', function() {
        let accNo = this.value;
        let nameDisplay = document.getElementById('account_name');

        if (accNo.length >= 5) {
            fetch(`/admin/accounts/search/${accNo}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        nameDisplay.innerText = "👤 ባለቤት፡ " + data.name;
                        nameDisplay.style.color = "#27ae60";
                    } else {
                        nameDisplay.innerText = "❌ አካውንቱ አልተገኘም!";
                        nameDisplay.style.color = "#e74c3c";
                    }
                });
        } else {
            nameDisplay.innerText = "";
        }
    });
</script>

</body>
</html>
