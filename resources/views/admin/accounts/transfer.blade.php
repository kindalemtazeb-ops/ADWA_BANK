<!DOCTYPE html>
<html lang="am">
<head>
    <meta charset="UTF-8">
    <title>አድዋ ባንክ - ገንዘብ ማስተላለፊያ</title>
    <style>
        body { font-family: sans-serif; background: #f4f4f4; padding: 40px; }
        .card { background: white; padding: 30px; border-radius: 12px; max-width: 500px; margin: auto; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        input { width: 100%; padding: 12px; margin: 5px 0; border: 1px solid #ddd; border-radius: 6px; box-sizing: border-box; }
        .name-info { font-size: 13px; color: #27ae60; font-weight: bold; margin-bottom: 10px; display: block; min-height: 18px; }
        button { width: 100%; padding: 12px; background: #c0392b; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 16px; font-weight: bold; margin-top: 10px; }
        .error { color: #721c24; background: #f8d7da; padding: 10px; border-radius: 5px; margin-bottom: 15px; }
    </style>
</head>
<body>
    <div class="card">
        <h2>💸 ገንዘብ ማስተላለፊያ</h2>

        @if ($errors->any())
            <div class="error">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </div>
        @endif

        <form action="{{ route('admin.accounts.doTransfer') }}" method="POST" id="transferForm">
            @csrf
            <label>የላኪ አካውንት ቁጥር:</label>
            <input type="text" name="sender_account" id="sender_acc" placeholder="የላኪው ቁጥር..." required autocomplete="off">
            <span id="sender_name" class="name-info"></span>

            <label>የተቀባይ አካውንት ቁጥር:</label>
            <input type="text" name="receiver_account" id="receiver_acc" placeholder="የተቀባዩ ቁጥር..." required autocomplete="off">
            <span id="receiver_name" class="name-info"></span>

            <label>የገንዘብ መጠን (ETB):</label>
            <input type="number" name="amount" required>

            <label>የእርስዎ ሚስጥር ቁጥር (PIN):</label>
            <input type="password" name="pin" maxlength="4" required>

            <button type="submit">አሁን አስተላልፍ</button>
        </form>
    </div>

    <script>
        function setupLiveSearch(inputId, labelId) {
            const input = document.getElementById(inputId);
            const label = document.getElementById(labelId);

            input.addEventListener('input', function() {
                const accNo = this.value;
                if (accNo.length >= 9) { // አካውንት ቁጥሩ ሲጠናቀቅ
                    fetch('/admin/accounts/search/' + accNo)
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                label.innerText = "👤 ስም: " + data.name;
                                label.style.color = "#27ae60";
                            } else {
                                label.innerText = "❌ አካውንቱ አልተገኘም!";
                                label.style.color = "#c0392b";
                            }
                        });
                } else {
                    label.innerText = "";
                }
            });
        }

        setupLiveSearch('sender_acc', 'sender_name');
        setupLiveSearch('receiver_acc', 'receiver_name');

        // ሲላክ ማረጋገጫ መጠየቅ
        document.getElementById('transferForm').onsubmit = function() {
            return confirm("እርግጠኛ ነህ? ገንዘቡ ሊላክ ነው!");
        };
    </script>
</body>
</html>
