<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login - SecureAuth</title>
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
}

h2 {
    text-align: center;
    margin-bottom: 25px;
    color: #38bdf8;
}

.sub-text {
    text-align: center;
    font-size: 14px;
    color: #94a3b8;
    margin-bottom: 20px;
}

label {
    font-size: 14px;
    margin-bottom: 5px;
    display: block;
}

input[type="email"],
input[type="password"] {
    width: 100%;
    padding: 12px;
    border-radius: 8px;
    border: none;
    margin-bottom: 10px;
    background: #0f172a;
    color: white;
}

input:focus {
    outline: 1px solid #38bdf8;
}

.error {
    color: #f87171;
    font-size: 12px;
    margin-bottom: 10px;
}

.remember {
    display: flex;
    align-items: center;
    font-size: 14px;
    margin-top: 10px;
}

.remember input {
    margin-right: 8px;
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
    margin-top: 15px;
}

.btn:hover {
    background: #0ea5e9;
}

.footer {
    text-align: center;
    margin-top: 15px;
    font-size: 14px;
}

.footer a {
    color: #38bdf8;
    text-decoration: none;
}

.footer a:hover {
    text-decoration: underline;
}

.top-link {
    text-align: right;
    font-size: 13px;
    margin-bottom: 10px;
}

.top-link a {
    color: #38bdf8;
    text-decoration: none;
}

.top-link a:hover {
    text-decoration: underline;
}

</style>
</head>
<body>

<div class="container">
    <h2>Welcome Back</h2>
    <div class="sub-text">Login to continue to SecureAuth</div>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <label>Email</label>
        <input type="email" name="email" value="{{ old('email') }}" required autofocus>
        @error('email') <div class="error">{{ $message }}</div> @enderror

        <label>Password</label>
        <input type="password" name="password" required>
        @error('password') <div class="error">{{ $message }}</div> @enderror

        <div class="remember">
            <input type="checkbox" name="remember">
            <span>Remember me</span>
        </div>

        <div class="top-link">
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}">Forgot Password?</a>
            @endif
        </div>

        <button class="btn">Login</button>
    </form>

    <div class="footer">
        Don’t have an account? <a href="{{ route('register') }}">Register</a>
    </div>
</div>

</body>
</html>