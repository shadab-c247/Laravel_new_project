<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Secure Auth Platform</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }

    body {
        font-family: 'Inter', sans-serif;
        background: #0f172a;
        color: #e2e8f0;
    }

    .navbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px 60px;
        background: rgba(15, 23, 42, 0.9);
        border-bottom: 1px solid #1e293b;
    }

    .logo {
        font-size: 20px;
        font-weight: 700;
        color: #38bdf8;
    }

    .nav-links a {
        margin-left: 25px;
        text-decoration: none;
        color: #cbd5f5;
        font-weight: 500;
        transition: 0.3s;
    }

    .nav-links a:hover {
        color: #38bdf8;
    }

    .hero {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 80px 60px;
    }

    .hero-text {
        max-width: 550px;
    }

    .hero-text h1 {
        font-size: 48px;
        margin-bottom: 20px;
        line-height: 1.2;
    }

    .hero-text p {
        color: #94a3b8;
        margin-bottom: 30px;
        font-size: 18px;
    }

    .btn {
        padding: 12px 25px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        margin-right: 10px;
    }

    .btn-primary {
        background: #38bdf8;
        color: #0f172a;
    }

    .btn-secondary {
        border: 1px solid #38bdf8;
        color: #38bdf8;
    }

    .features {
        padding: 60px;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 30px;
    }

    .card {
        background: #1e293b;
        padding: 25px;
        border-radius: 12px;
        transition: 0.3s;
    }

    .card:hover {
        transform: translateY(-5px);
        background: #273449;
    }

    .card h3 {
        margin-bottom: 10px;
        color: #38bdf8;
    }

    .card p {
        color: #94a3b8;
    }

    .roles {
        padding: 60px;
        text-align: center;
    }

    .roles h2 {
        margin-bottom: 20px;
    }

    .role-tags span {
        display: inline-block;
        margin: 8px;
        padding: 10px 18px;
        background: #1e293b;
        border-radius: 20px;
        color: #38bdf8;
        font-size: 14px;
    }

    .cta {
        padding: 60px;
        text-align: center;
        background: #020617;
    }

    .cta h2 {
        margin-bottom: 20px;
    }

    footer {
        text-align: center;
        padding: 20px;
        background: #020617;
        font-size: 14px;
        color: #64748b;
    }
</style>
</head>
<body>

<div class="navbar">
    <div class="logo">SecureAuth</div>
    <div class="nav-links">
        <a href="#features">Features</a>
        <a href="#roles">Roles</a>
        <a href="{{ route('login') }}">Login</a>
        <a href="{{ route('register') }}">Register</a>
    </div>
</div>

<section class="hero">
    <div class="hero-text">
        <h1>Enterprise-Level Authentication System</h1>
        <p>Secure your application with JWT authentication, multi-factor authentication (MFA), and dynamic role switching across departments and positions.</p>
        <a href="{{ route('register') }}" class="btn btn-primary">Get Started</a>
        <a href="#features" class="btn btn-secondary">Learn More</a>
    </div>
</section>

<section id="features" class="features">
    <div class="card">
        <h3>JWT Authentication</h3>
        <p>Stateless, secure token-based authentication for scalable applications.</p>
    </div>
    <div class="card">
        <h3>Multi-Factor Authentication</h3>
        <p>Enhance security with OTP, email, or authenticator apps.</p>
    </div>
    <div class="card">
        <h3>Role Switching</h3>
        <p>Seamlessly switch between multiple roles without re-login.</p>
    </div>
    <div class="card">
        <h3>Department Mapping</h3>
        <p>Assign roles based on departments and positions dynamically.</p>
    </div>
</section>

<section id="roles" class="roles">
    <h2>Flexible Role Management</h2>
    <p>Create and manage unlimited roles tailored to your organization.</p>
    <div class="role-tags">
        <span>Admin</span>
        <span>Manager</span>
        <span>HR</span>
        <span>Developer</span>
        <span>Finance</span>
        <span>Support</span>
    </div>
</section>

<section class="cta">
    <h2>Start Building Secure Applications Today</h2>
    <a href="{{ route('register') }}" class="btn btn-primary">Create Account</a>
</section>

<footer>
    © 2026 SecureAuth Platform. All rights reserved.
</footer>

</body>
</html>
