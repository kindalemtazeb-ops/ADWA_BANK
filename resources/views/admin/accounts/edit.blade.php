<!DOCTYPE html>
<html lang="am">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADWA BANK - EDIT CUSTOMER</title>
    <style>
        :root {
            --cbe-purple: #4B1A60;
            --cbe-yellow: #FDB913;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f4f7f6;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }

        .container {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 5px 25px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 500px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid var(--cbe-yellow);
            padding-bottom: 10px;
        }

        h2 {
            color: var(--cbe-purple);
            margin: 0;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #555;
        }

        input[type="text"],
        input[type="number"],
        input[type="password"],
        input[type="file"] {
            width: 100%;
            padding: 12px;
            border: 2px solid #eee;
            border-radius: 8px;
            box-sizing: border-box;
            outline: none;
            transition: 0.3s;
        }

        input:focus {
            border-color: var(--cbe-purple);
        }

        .current-photo {
            margin-top: 10px;
            text-align: center;
        }

        .current-photo img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--cbe-yellow);
        }

        .btn-container {
            display: flex;
            gap: 10px;
            margin-top: 25px;
        }

        .btn-save {
            flex: 2;
            background: var(--cbe-purple);
            color: white;
            border: none;
            padding: 12px;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn-save:hover { background: #311b92; }

        .btn-cancel {
            flex: 1;
            background: #e74c3c;
            color: white;
            text-decoration: none;
            text-align: center;
            padding: 12px;
            border-radius: 8px;
            font-weight: bold;
            transition: 0.3s;
        }

        .btn-cancel:hover { background: #c0392b; }

        .error-list {
            background: #f8d7da;
            color: #721c24;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h2>✎ የደንበኛ መረጃ ማስተካከያ</h2>
        <p style="color: #888; font-size: 14px;">መረጃውን ቀይረው ሲጨርሱ "መረጃውን አድስ" የሚለውን ይጫኑ</p>
    </div>

    <!-- ስህተት ካለ ማሳያ -->
    @if ($errors->any())
        <div class="error-list">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.accounts.update', $account->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="full_name">ሙሉ ስም</label>
            <input type="text" name="full_name" id="full_name" value="{{ old('full_name', $account->full_name) }}" required>
        </div>

        <div class="form-group">
            <label for="account_number">የሂሳብ ቁጥር</label>
            <input type="text" name="account_number" id="account_number" value="{{ old('account_number', $account->account_number) }}" required>
        </div>

        <!-- አዲስ የተጨመረ፡ የስልክ ቁጥር -->
        <div class="form-group">
            <label for="phone_number">የስልክ ቁጥር</label>
            <input type="text" name="phone_number" id="phone_number" value="{{ old('phone_number', $account->phone_number) }}" required>
        </div>

        <!-- አዲስ የተጨመረ፡ PIN -->
        <div class="form-group">
            <label for="pin">PIN (ሚስጥር ቁጥር)</label>
            <input type="password" name="pin" id="pin" placeholder="ሚስጥር ቁጥሩን ለመቀየር ከፈለጉ ብቻ ይሙሉት">
        </div>

        <div class="form-group">
            <label for="photo">ፎቶ (ካልፈለጉ አይምረጡ)</label>
            <input type="file" name="photo" id="photo" accept="image/*">

            @if($account->photo)
                <div class="current-photo">
                    <p style="font-size: 12px; color: #888;">ያሁኑ ፎቶ፦</p>
                    <img src="{{ asset('storage/' . $account->photo) }}" alt="Current Photo">
                </div>
            @endif
        </div>

        <div class="btn-container">
            <button type="submit" class="btn-save">💾 መረጃውን አድስ</button>
            <a href="{{ route('admin.accounts.index') }}" class="btn-cancel">ተመለስ</a>
        </div>
    </form>
</div>

</body>
</html>
