<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Verify OTP - SecureAuth</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
<style>
* { margin: 0; padding: 0; box-sizing: border-box; }

body {
    font-family: 'Inter', sans-serif;
    background: #0f172a;
    color: #e2e8f0;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

.container {
    width: 100%;
    max-width: 420px;
    background: #1e293b;
    padding: 35px;
    border-radius: 16px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.4);
    text-align: center;
}

h2 {
    margin-bottom: 20px;
    color: #38bdf8;
}

.sub-text {
    font-size: 14px;
    color: #94a3b8;
    margin-bottom: 20px;
}

.error {
    background: rgba(248, 113, 113, 0.1);
    color: #f87171;
    padding: 10px;
    border-radius: 8px;
    margin-bottom: 15px;
    font-size: 13px;
}

.otp-show {
    background: rgba(34, 197, 94, 0.1);
    color: #22c55e;
    padding: 10px;
    border-radius: 8px;
    margin-bottom: 15px;
    font-size: 14px;
    font-weight: 600;
}

label {
    display: block;
    text-align: left;
    margin-bottom: 6px;
    font-size: 14px;
}

input {
    width: 100%;
    padding: 12px;
    border-radius: 8px;
    border: none;
    margin-bottom: 15px;
    background: #0f172a;
    color: white;
    font-size: 16px;
    text-align: center;
    letter-spacing: 4px;
}

input:focus {
    outline: 1px solid #38bdf8;
}

.btn {
    width: 100%;
    padding: 12px;
    border: none;
    border-radius: 8px;
    background: #38bdf8;
    color: #0f172a;
    font-weight: 600;
    cursor: pointer;
    transition: 0.3s;
}

.btn:hover {
    background: #0ea5e9;
}

.resend {
    margin-top: 15px;
    font-size: 14px;
}

.resend a {
    color: #38bdf8;
    text-decoration: none;
}

.resend a:hover {
    text-decoration: underline;
}

</style>
</head>
<body>

<div class="container">
    <h2>Verify OTP</h2>
    <div class="sub-text">Enter the 6-digit code sent to your email</div>

    @if(session('otp'))
        <div class="otp-show">
            Your OTP is: {{ session('otp') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="error">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('verify.otp') }}">
        @csrf

        <input type="hidden" name="email" value="{{ session('email') }}">

        <label>OTP Code</label>
        <input type="text" name="otp" value="{{ old('otp') }}" maxlength="6" required>

        <button class="btn">Verify OTP</button>
    </form>

    <div class="resend">
        Didn’t receive code? <a href="#">Resend OTP</a>
    </div>
</div>

</body>
</html>