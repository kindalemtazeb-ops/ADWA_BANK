<!DOCTYPE html>
<html lang="am">
<head>
    <meta charset="UTF-8">
    <title>ADWA BANK - TRANSFER</title>
    <style>
        body { font-family: sans-serif; background: #f8f9fa; padding: 30px; }
        .container { background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 15px rgba(0,0,0,0.1); max-width: 500px; margin: auto; }
        h2 { text-align: center; color: #2c3e50; margin-bottom: 20px; }
        label { font-weight: bold; color: #34495e; display: block; margin-top: 15px; }
        input { width: 100%; padding: 12px; margin: 8px 0; border: 1px solid #ccc; border-radius: 5px; box-sizing: border-box; font-size: 16px; }
        .btn { width: 100%; padding: 14px; background: #f39c12; color: white; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; font-size: 16px; margin-top: 20px; }
        .btn:hover { background: #e67e22; }
        .name-info { font-size: 13px; font-weight: bold; min-height: 18px; display: block; margin-bottom: 5px; }
        .error-text { color: #e74c3c; font-size: 12px; display: block; margin-top: 5px; }
        .back-link { display: block; text-align: center; margin-top: 15px; color: #3498db; text-decoration: none; font-size: 14px; }
    </style>
</head>
<body>
<div class="container">
    <h2>💸 ብር ማስተላለፊያ (TRANSFER)</h2>

    <form action="{{ route('admin.accounts.doTransfer') }}" method="POST">
        @csrf

        <label>መነሻ አካውንት:</label>
        <input type="text" name="from_account" id="from_account"
               placeholder="የላኪ 13 ዲጂት አካውንት" maxlength="13"
               oninput="this.value = this.value.replace(/[^0-9]/g, ''); checkName(this.value, 'sender_name')" required>
        <span id="sender_name" class="name-info"></span>
        @error('from_account') <span class="error-text">{{ $message }}</span> @enderror

        <label>የላኪ ሚስጥር ቁጥር (PIN):</label>
        <input type="password" name="pin" placeholder="የላኪውን 4 ድጂት PIN ያስገቡ" maxlength="4" required>
        @error('pin') <span class="error-text">{{ $message }}</span> @enderror

        <hr style="border: 0.5px solid #eee; margin: 20px 0;">

        <label>መድረሻ አካውንት:</label>
        <input type="text" name="to_account" id="to_account"
               placeholder="የተቀባይ 13 ዲጂት አካውንት" maxlength="13"
               oninput="this.value = this.value.replace(/[^0-9]/g, ''); checkName(this.value, 'receiver_name')" required>
        <span id="receiver_name" class="name-info"></span>
        @error('to_account') <span class="error-text">{{ $message }}</span> @enderror

        <label>የብር መጠን:</label>
        <input type="number" name="amount" placeholder="የሚላከው የገንዘብ መጠን" min="1" required>
        @error('amount') <span class="error-text">{{ $message }}</span> @enderror

        <button type="submit" class="btn">አስተላልፍ</button>
    </form>

    <a href="{{ route('admin.accounts.index') }}" class="back-link">ወደ ዝርዝር ተመለስ</a>
</div>

<script>
    function checkName(accountNumber, displayId) {
        let display = document.getElementById(displayId);

        if (accountNumber.length === 13) {
            display.innerText = "በመፈለግ ላይ...";
            display.style.color = "#d35400";

            fetch(`/admin/accounts/search/${accountNumber}`)
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        display.innerText = "✅ " + data.name;
                        display.style.color = "#27ae60";
                    } else {
                        display.innerText = "❌ አካውንቱ አልተገኘም!";
                        display.style.color = "#e74c3c";
                    }
                })
                .catch(() => {
                    display.innerText = "⚠️ ሲስተም ስህተት!";
                });
        } else {
            display.innerText = "";
        }
    }
</script>
</body>
</html>
