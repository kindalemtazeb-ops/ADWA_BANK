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

        /* የፎቶ ማስቀመጫ ስታይል ማስተካከያ */
        .customer-preview {
            text-align: center;
            margin-bottom: 20px;
            padding: 15px;
            border: 2px dashed #ddd;
            border-radius: 10px;
            display: none;
        }
        .customer-preview img {
            width: 110px;
            height: 110px;
            border-radius: 50%;
            object-fit: cover; /* ፎቶው እንዳይጨማደድ እና እንዳይረዝም ያደርጋል */
            border: 3px solid #27ae60;
            background: #eee;
            image-rendering: -webkit-optimize-contrast; /* የፎቶውን የጥራት ደረጃ ለመጨመር */
        }
        .customer-name {
            display: block;
            margin-top: 10px;
            font-weight: bold;
            color: #27ae60;
        }

        /* ህትመት (Print) በሚደረግበት ጊዜ ጥራቱን ለመጠበቅ የተጨመረ CSS */
        @media print {
            body {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            img {
                image-rendering: -webkit-optimize-contrast !important;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <h2 style="text-align: center;">💰 ብር ማስቀመጫ (DEPOSIT)</h2>

    <div id="customer-info" class="customer-preview">
        <img id="customer-photo" src="" alt="Customer Photo">
        <span id="account-name" class="customer-name"></span>
    </div>

    <form action="{{ route('admin.accounts.doDeposit') }}" method="POST">
        @csrf

        <label>የአካውንት ቁጥር:</label>
        <input type="text" id="account_input" name="account_number" value="{{ old('account_number') }}" placeholder="የአካውንት ቁጥሩን እዚህ ያስገቡ..." required autocomplete="off">
        @error('account_number') <p class="error-msg">{{ $message }}</p> @enderror

        <label>የብር መጠን (ETB):</label>
        <input type="number" name="amount" placeholder="የሚቀመጠው የብር መጠን..." required>
        @error('amount') <p class="error-msg">{{ $message }}</p> @enderror

        <button type="submit" class="btn">አረጋግጥና አስቀምጥ</button>
    </form>
</div>

<script>
    document.getElementById('account_input').addEventListener('input', function() {
        // ስፔስ ካለው እናጸዳዋለን
        let query = this.value.replace(/\s+/g, '');
        let infoBox = document.getElementById('customer-info');
        let nameDisplay = document.getElementById('account-name');
        let photoDisplay = document.getElementById('customer-photo');

        if (query.length >= 13) {
            fetch(`/admin/accounts/search/${query}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        nameDisplay.innerText = "👤 ባለቤት፡ " + data.name;

                        // ማስተካከያ፡ data.photo ቀጥታ ሙሉ URL ስለሆነ መጨመር አያስፈልገውም
                        if (data.photo) {
                            photoDisplay.src = data.photo;
                        } else {
                            // ፎቶ ከሌለ ይህን default ይጠቀማል
                            photoDisplay.src = "https://ui-avatars.com/api/?name=" + urlencode(data.name) + "&background=random";
                        }

                        // ማስተካከያ፡ ከ .style.style.display ወደ ትክክለኛው .style.display ተቀይሯል
                        infoBox.style.display = 'block';
                    } else {
                        infoBox.style.display = 'none';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    infoBox.style.display = 'none';
                });
        } else {
            infoBox.style.display = 'none';
        }
    });

    // ዩአርኤልን ሴፍ ለማድረግ የረዳት ፈንክሽን
    function urlencode(str) {
        return encodeURIComponent(str).replace(/[!'()*]/g, function(c) {
            return '%' + c.charCodeAt(0).toString(16);
        });
    }
</script>

</body>
</html>
