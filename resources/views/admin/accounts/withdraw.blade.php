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

        /* የፎቶ ማሳያ ስታይል */
        #customer-info {
            display: none;
            flex-direction: column;
            align-items: center;
            background: #f9f9f9;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            border: 1px dashed #ccc;
        }
        #customer-photo {
            width: 110px;
            height: 110px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #e74c3c;
            margin-bottom: 10px;
            background: #eee;
        }

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
            font-size: 16px;
            color: #27ae60;
            font-weight: bold;
            text-align: center;
        }
        .back-link { display: block; text-align: center; margin-top: 20px; text-decoration: none; color: #7f8c8d; font-size: 14px; }
        .back-link:hover { color: #2c3e50; }
    </style>
</head>
<body>

<div class="container">
    <h2>🏧 ብር ማውጫ (Withdraw)</h2>

    <!-- የደንበኛ ፎቶ እና ስም ማሳያ -->
    <div id="customer-info">
        <img id="customer-photo" src="" alt="Customer Photo">
        <div id="account_name" class="account-name-display"></div>
    </div>

    <form action="{{ route('admin.accounts.doWithdraw') }}" method="POST">
        @csrf

        <div class="form-group">
            <label>የአካውንት ቁጥር (Account Number):</label>
            <input type="text" id="account_number" name="account_number"
                   placeholder="የአካውንት ቁጥሩን እዚህ ያስገቡ..."
                   value="{{ old('account_number') }}" required autocomplete="off">
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
    document.getElementById('account_number').addEventListener('input', function() {
        // ስፔስ ካለ እናጸዳዋለን
        let accNo = this.value.replace(/\s+/g, '');
        let infoDiv = document.getElementById('customer-info');
        let nameDisplay = document.getElementById('account_name');
        let photoDisplay = document.getElementById('customer-photo');

        if (accNo.length >= 13) {
            fetch(`/admin/accounts/search/${accNo}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        nameDisplay.innerText = "👤 ባለቤት፡ " + data.name;
                        nameDisplay.style.color = "#27ae60";

                        // ማስተካከያ፡ data.photo ሙሉ URL ስለሆነ መጨመር አያስፈልገውም
                        if (data.photo) {
                            photoDisplay.src = data.photo;
                        } else {
                            // ፎቶ ከሌለ ስሙን ተጠቅሞ Avatar ይፈጥራል
                            photoDisplay.src = "https://ui-avatars.com/api/?name=" + data.name + "&background=random";
                        }

                        infoDiv.style.display = "flex";
                    } else {
                        nameDisplay.innerText = "❌ አካውንቱ አልተገኘም!";
                        nameDisplay.style.color = "#e74c3c";
                        photoDisplay.src = "https://ui-avatars.com/api/?name=Unknown&background=e74c3c&color=fff";
                        infoDiv.style.display = "flex";
                    }
                })
                .catch(err => {
                    console.error("Error fetching data:", err);
                    infoDiv.style.display = "none";
                });
        } else {
            infoDiv.style.display = "none";
        }
    });
</script>

</body>
</html>
