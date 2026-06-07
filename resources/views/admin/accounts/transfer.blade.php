!DOCTYPE html>
<html lang="am">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADWA BANK - TRANSFER</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f0f2f5; padding: 30px; }
        .container {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            max-width: 500px;
            margin: auto;
        }
        h2 { text-align: center; color: #2c3e50; margin-bottom: 25px; border-bottom: 2px solid #3498db; padding-bottom: 10px; }
        .form-group { margin-bottom: 18px; }
        label { display: block; margin-bottom: 8px; font-weight: bold; color: #2c3e50; }
        input, select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-sizing: border-box;
            font-size: 15px;
            transition: 0.3s;
        }
        input:focus, select:focus { border-color: #3498db; outline: none; box-shadow: 0 0 5px rgba(52, 152, 219, 0.2); }

        /* የላኪ እና የተቀባይ መረጃ ማሳያ ሳጥኖች */
        .info-box {
            display: none;
            align-items: center;
            gap: 15px;
            background: #f8f9fa;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        #sender-info { border: 1px dashed #e67e22; background: #fffdfb; }
        #receiver-info { border: 1px dashed #27ae60; background: #f9fdfa; }

        .info-photo {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            object-fit: cover;
            background: #eee;
            image-rendering: -webkit-optimize-contrast;
        }
        #sender-photo { border: 2px solid #e67e22; }
        #receiver-photo { border: 2px solid #27ae60; }

        .details { font-size: 14px; color: #2c3e50; }

        .btn {
            width: 100%;
            padding: 14px;
            background: #3498db;
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
            font-size: 16px;
            text-transform: uppercase;
            transition: 0.3s;
        }
        .btn:hover { background: #2980b9; }
        .error { color: #e74c3c; font-size: 13px; margin-top: 5px; font-weight: 500; }
        .back-link { display: block; text-align: center; margin-top: 20px; text-decoration: none; color: #7f8c8d; font-size: 14px; }
        .back-link:hover { color: #2c3e50; }
    </style>
</head>
<body>

<div class="container">
    <h2>💸 ገንዘብ ማስተላለፊያ (Transfer)</h2>

    <div id="sender-info" class="info-box">
        <img id="sender-photo" class="info-photo" src="" alt="Sender Photo">
        <div class="details">
            <div id="sender_name" style="font-weight: bold; color: #e67e22; font-size: 16px;"></div>
            <div id="sender_phone" style="color: #555; margin-top: 3px;"></div>
        </div>
    </div>

    <div id="receiver-info" class="info-box">
        <img id="receiver-photo" class="info-photo" src="" alt="Receiver Photo">
        <div class="details">
            <div id="receiver_name" style="font-weight: bold; color: #27ae60; font-size: 16px;"></div>
            <div id="receiver_phone" style="color: #555; margin-top: 3px;"></div>
        </div>
    </div>

    <form action="{{ route('admin.accounts.doTransfer') }}" method="POST">
        @csrf

        <input type="hidden" name="sender_id" value="{{ $account->id ?? '' }}">

        <div class="form-group">
            <label>የላኪ አካውንት ቁጥር (Sender Account):</label>
            <input type="text" id="sender_account" name="from_account" placeholder="የላኪውን አካውንት ቁጥር ያስገቡ..." value="{{ old('from_account') }}" required autocomplete="off">
            @error('from_account') <div class="error">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
    <label>የባንክ አይነት (Select Bank):</label>
    <select name="bank_type" id="bank_type" required>
        <option value="adwa">ADWA BANK (የእኛ ባንክ)</option>
        <option value="cbe">CBE (የኢትዮጵያ ንግድ ባንክ)</option>
        <option value="awash">Awash Bank (አዋሽ ባንክ)</option>
        <option value="dashen">Dashen Bank (ዳሽን ባንክ)</option>
        <option value="boa">Bank of Abyssinia (አቢሲኒያ ባንክ)</option>
        <option value="wegagen">Wegagen Bank (ወጋገን ባንክ)</option>
        <option value="united">Hibret Bank (ኅብረት ባንክ)</option>
        <option value="nib">Nib International Bank (ንብ ባንክ)</option>
        <option value="coop">Cooperative Bank of Oromia (ኦሮሚያ ኅብረት ሥራ ባንክ)</option>
        <option value="oromia">Oromia Bank (ኦሮሚያ ባንክ)</option>
        <option value="zemen">Zemen Bank (ዛመን ባንክ)</option>
        <option value="abay">Abay Bank (ዐባይ ባንክ)</option>
        <option value="berhan">Berhan Bank (ብርሃን ባንክ)</option>
        <option value="bunna">Bunna Bank (ቡና ባንክ)</option>
        <option value="awach">Awach SACCO (አዋች ብድርና ቁጠባ)</option>
        <option value="enat">Enat Bank (እናት ባንክ)</option>
        <option value="global">Amhara Bank (አማራ ባንክ)</option>
        <option value="siinqee">Siinqee Bank (ሲንቄ ባንክ)</option>
        <option value="tsehay">Tsehay Bank (ፀሐይ ባንክ)</option>
        <option value="goh">Goh Betoch Bank (ጎህ ቤቶች ባንክ)</option>
        <option value="hijra">Hijra Bank (ሂጅራ ባንክ - ከወለድ ነፃ)</option>
        <option value="zamzam">ZamZam Bank (ዘምዘም ባንክ - ከወለድ ነፃ)</option>
        <option value="telebirr">Telebirr (ቴሌብር)</option>
        <option value="cbe_birr">CBE Birr (ሲቢኢ ብር)</option>
    </select>
</div>

        <div class="form-group">
            <label>የተቀባይ አካውንት ቁጥር (Receiver Account):</label>
            <input type="text" id="receiver_account" name="to_account"
                   placeholder="የተቀባዩን አካውንት ቁጥር ያስገቡ..."
                   value="{{ old('to_account') }}" required autocomplete="off">
            @error('to_account') <div class="error">{{ $message }}</div> @enderror
        </div>

        <input type="hidden" id="hidden_receiver_name" name="receiver_name">
        <input type="hidden" id="hidden_receiver_phone" name="receiver_phone">

        <div class="form-group">
            <label>የብር መጠን (Amount in ETB):</label>
            <input type="number" name="amount" placeholder="ማስተላለፍ የሚፈልጉትን መጠን..." value="{{ old('amount') }}" required>
            @error('amount') <div class="error">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label>ሚስጥር ቁጥር (PIN):</label>
            <input type="password" name="pin" placeholder="የአካውንቱን 4 ድጂት ፒን ያስገቡ" maxlength="4" required>
            @error('pin') <div class="error">{{ $message }}</div> @enderror
        </div>

        <button type="submit" class="btn">አሁኑኑ አስተላልፍ (Transfer Now)</button>

        <a href="{{ route('admin.accounts.index') }}" class="back-link">← ወደ ዝርዝር ተመለስ</a>
    </form>
</div>

<script>
    // --- 1. የላኪ አካውንት መፈለጊያ ጃቫስክሪፕት (Sender Live Search) ---
    document.getElementById('sender_account').addEventListener('input', function() {
        let accNo = this.value.replace(/\s+/g, '');
        let infoDiv = document.getElementById('sender-info');
        let nameDisplay = document.getElementById('sender_name');
        let phoneDisplay = document.getElementById('sender_phone');
        let photoDisplay = document.getElementById('sender-photo');

        if (accNo.length >= 13) {
            fetch(`/admin/accounts/search/${accNo}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        nameDisplay.innerText = "👤 ላኪ፡ " + data.name;
                        let phone = data.phone ? data.phone : 'የለም';
                        phoneDisplay.innerText = "📞 ስልክ፡ " + phone;

                        if (data.photo) {
                            photoDisplay.src = data.photo;
                        } else {
                            photoDisplay.src = "https://ui-avatars.com/api/?name=" + encodeURIComponent(data.name) + "&background=random&color=fff";
                        }
                        infoDiv.style.display = "flex";
                    } else {
                        infoDiv.style.display = "none";
                    }
                })
                .catch(err => {
                    console.error("Error fetching sender:", err);
                    infoDiv.style.display = "none";
                });
        } else {
            infoDiv.style.display = "none";
        }
    });

    // --- 2. የተቀባይ አካውንት መፈለጊያ ጃቫስክሪፕት (Receiver Live Search) ---
    document.getElementById('receiver_account').addEventListener('input', function() {
        let accNo = this.value.replace(/\s+/g, '');
        let bankType = document.getElementById('bank_type').value;

        let infoDiv = document.getElementById('receiver-info');
        let nameDisplay = document.getElementById('receiver_name');
        let phoneDisplay = document.getElementById('receiver_phone');
        let photoDisplay = document.getElementById('receiver-photo');

        let hiddenName = document.getElementById('hidden_receiver_name');
        let hiddenPhone = document.getElementById('hidden_receiver_phone');

        if (bankType === 'adwa' && accNo.length >= 13) {
            fetch(`/admin/accounts/search/${accNo}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        nameDisplay.innerText = "👤 ተቀባይ፡ " + data.name;
                        let phone = data.phone ? data.phone : 'የለም';
                        phoneDisplay.innerText = "📞 ስልክ፡ " + phone;

                        hiddenName.value = data.name;
                        hiddenPhone.value = data.phone;

                        if (data.photo) {
                            photoDisplay.src = data.photo;
                        } else {
                            photoDisplay.src = "https://ui-avatars.com/api/?name=" + encodeURIComponent(data.name) + "&background=random&color=fff";
                        }
                        infoDiv.style.display = "flex";
                    } else {
                        setDefaultExternal(accNo, "የማይታወቅ ደንበኛ");
                    }
                })
                .catch(err => {
                    console.error("Error fetching receiver:", err);
                    infoDiv.style.display = "none";
                });
        }
        else if (bankType !== 'adwa' && accNo.length >= 10) {
            let bankName = bankType.toUpperCase();
            setDefaultExternal(accNo, bankName + " User");
        } else {
            infoDiv.style.display = "none";
            hiddenName.value = "";
            hiddenPhone.value = "";
        }

        function setDefaultExternal(accountNum, defaultName) {
            nameDisplay.innerText = "👤 ተቀባይ፡ " + defaultName;
            phoneDisplay.innerText = "📞 ስልክ፡ አልተያያዘም (ውጭ ባንክ)";

            hiddenName.value = defaultName;
            hiddenPhone.value = "N/A";

            photoDisplay.src = "https://ui-avatars.com/api/?name=" + encodeURIComponent(defaultName) + "&background=6f42c1&color=fff";
            infoDiv.style.display = "flex";
        }
    });

    document.getElementById('bank_type').addEventListener('change', function() {
        document.getElementById('receiver_account').value = "";
        document.getElementById('receiver-info').style.display = "none";
        document.getElementById('receiver_account').dispatchEvent(new Event('input'));
    });
</script>

</body>
</html>
