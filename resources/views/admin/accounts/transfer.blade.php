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
        input, select { width: 100%; padding: 12px; margin: 8px 0; border: 1px solid #ccc; border-radius: 5px; box-sizing: border-box; font-size: 16px; }
        .btn { width: 100%; padding: 14px; background: #f39c12; color: white; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; font-size: 16px; margin-top: 20px; }
        .btn:hover { background: #e67e22; }
        .btn:disabled { background: #bdc3c7; cursor: not-allowed; }
        .name-info { font-size: 13px; font-weight: bold; display: block; margin-bottom: 5px; }
        .error-text { color: #e74c3c; font-size: 12px; display: block; margin-top: 5px; }
        .back-link { display: block; text-align: center; margin-top: 15px; color: #3498db; text-decoration: none; font-size: 14px; }
        .preview-box { display: flex; align-items: center; gap: 10px; margin-top: 5px; padding: 10px; border-radius: 8px; display: none; background: #f0f3f1; transition: 0.3s; }
        .preview-img { width: 50px; height: 50px; border-radius: 50%; object-fit: cover; border: 2px solid #27ae60; }
        .sender-box { border-left: 4px solid #4B1A60; }
        .receiver-box { border-left: 4px solid #27ae60; }
        .external-box { border-left: 4px solid #3498db; background: #e8f4fd; }
        .bank-tag { background: #2c3e50; color: white; padding: 2px 8px; border-radius: 4px; font-size: 10px; margin-left: auto; }
    </style>
</head>
<body>
<div class="container">
    <h2>💸 ብር ማስተላለፊያ (TRANSFER)</h2>

    <form action="{{ route('admin.accounts.doTransfer') }}" method="POST">
        @csrf

        <!-- ላኪ (Sender) -->
        <label>የላኪ አካውንት ቁጥር:</label>
        <input type="text" name="from_account" id="from_account"
               placeholder="የላኪውን የአካውንት ቁጥር ያስገቡ"
               oninput="this.value = this.value.replace(/\s/g, ''); checkUser(this.value, 'sender_info', 'sender_photo', 'sender_name')" required>

        <div id="sender_info" class="preview-box sender-box">
            <img id="sender_photo" src="" class="preview-img">
            <span id="sender_name" class="name-info"></span>
            <span class="bank-tag">Adwa Bank</span>
        </div>
        @error('from_account') <span class="error-text">{{ $message }}</span> @enderror

        <label>የላኪ ሚስጥር ቁጥር (PIN):</label>
        <input type="password" name="pin" placeholder="የላኪውን 4 ድጂት PIN ያስገቡ" maxlength="4" required>

        <hr style="border: 0.5px solid #eee; margin: 25px 0;">

        <!-- የባንክ ምርጫ -->
        <label>ተቀባይ ባንክ:</label>
        <select name="bank_name" id="bank_name" onchange="resetReceiverField()" required>
            <option value="Adwa Bank">Adwa Bank (Internal)</option>
            <optgroup label="የኢትዮጵያ ባንኮች (EthSwitch)">
                <option value="CBE">የኢትዮጵያ ንግድ ባንክ (CBE)</option>
                <option value="Dashen">ዳሽን ባንክ (Dashen)</option>
                <option value="Awash">አዋሽ ባንክ (Awash)</option>
                <option value="Abyssinia">አቢሲኒያ ባንክ (Abyssinia)</option>
                <option value="Zemen">ዘመን ባንክ (Zemen)</option>
                <option value="Oromia">ኦሮሚያ ባንክ (Oromia)</option>
                <option value="Hibret">ህብረት ባንክ (Hibret)</option>
                <option value="Coop">የኦሮሚያ ህብረት ስራ ባንክ (Coop)</option>
                <option value="Berhan">ብርሃን ባንክ (Berhan)</option>
                <option value="Bunna">ቡና ባንክ (Bunna)</option>
                <option value="Abay">አባይ ባንክ (Abay)</option>
                <option value="Enat">እናት ባንክ (Enat)</option>
                <option value="Global">ግሎባል ባንክ (Global)</option>
            </optgroup>
        </select>

        <!-- ተቀባይ (Receiver) -->
        <label>የተቀባይ አካውንት ቁጥር:</label>
        <input type="text" name="to_account" id="to_account"
               placeholder="የተቀባዩን የአካውንት ቁጥር ያስገቡ"
               oninput="this.value = this.value.replace(/\s/g, ''); checkUser(this.value, 'receiver_info', 'receiver_photo', 'receiver_name', true)" required>

        <div id="receiver_info" class="preview-box receiver-box">
            <img id="receiver_photo" src="" class="preview-img">
            <span id="receiver_name" class="name-info"></span>
            <span id="target_bank_tag" class="bank-tag">Adwa Bank User</span>
        </div>
        @error('to_account') <span class="error-text">{{ $message }}</span> @enderror

        <label>የብር መጠን:</label>
        <input type="number" name="amount" placeholder="የሚላከው የገንዘብ መጠን" min="1" required>
        @error('amount') <span class="error-text">{{ $message }}</span> @enderror

        <button type="submit" id="submit_btn" class="btn" disabled>አስተላልፍ</button>
    </form>

    <a href="{{ route('admin.accounts.index') }}" class="back-link">ወደ ዝርዝር ተመለስ</a>
</div>

<script>
    function resetReceiverField() {
        document.getElementById('to_account').value = '';
        document.getElementById('receiver_info').style.display = "none";
        document.getElementById('submit_btn').disabled = true;
    }

    function checkUser(query, boxId, photoId, nameId, isReceiver = false) {
        let bank = document.getElementById('bank_name').value;
        let box = document.getElementById(boxId);
        let nameField = document.getElementById(nameId);
        let photoField = document.getElementById(photoId);
        let bankTag = document.getElementById('target_bank_tag');
        let submitBtn = document.getElementById('submit_btn');

        // ማንኛውንም ስፔስ አጥፋ
        let cleanQuery = query.replace(/\s+/g, '');

        if (cleanQuery.length >= 10) {
            // በ ባንክ ምርጫው መሰረት URL መቀየር (ለ Adwa Bank 'search' ሌላ ከሆነ 'check-external')
            let url = (bank === 'Adwa Bank' || !isReceiver)
                      ? `/admin/accounts/search/${cleanQuery}`
                      : `/admin/accounts/check-external/${bank}/${cleanQuery}`;

            fetch(url)
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        nameField.innerText = (isReceiver && bank !== 'Adwa Bank' ? "🏢 " : "👤 ") + data.name;
                        nameField.style.color = "#2c3e50";

                        if (photoField) {
                            if (data.photo && bank === 'Adwa Bank') {
                                // ፎቶው ካለ እና Adwa Bank ከሆነ ብቻ አሳይ
                                photoField.src = data.photo.startsWith('http') ? data.photo : "/storage/" + data.photo;
                                photoField.style.display = "block";
                            } else {
                                photoField.style.display = "none";
                            }
                        }

                        if (isReceiver) {
                            if (bank !== 'Adwa Bank') box.classList.add('external-box');
                            else box.classList.remove('external-box');

                            if(bankTag) bankTag.innerText = bank + " User";
                            submitBtn.disabled = false;
                        }
                        box.style.display = "flex";
                    } else {
                        nameField.innerText = "❌ አካውንቱ አልተገኘም!";
                        nameField.style.color = "red";
                        if (photoField) photoField.style.display = "none";
                        box.style.display = "flex";
                        if (isReceiver) submitBtn.disabled = true;
                    }
                })
                .catch(err => {
                    console.error("Error:", err);
                    if (isReceiver) submitBtn.disabled = true;
                });
        } else {
            box.style.display = "none";
            if (isReceiver) submitBtn.disabled = true;
        }
    }
</script>
</body>
</html>
