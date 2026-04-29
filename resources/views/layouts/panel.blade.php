<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Secure Auth Platform' }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body {
            min-height: 100vh;
            font-family: 'Inter', sans-serif;
            background: #0f172a;
            color: #e2e8f0;
        }
        a { color: inherit; text-decoration: none; }
        button, input, select { font: inherit; }
        .panel-shell { min-height: 100vh; display: flex; }
        .panel-sidebar {
            width: 280px;
            min-height: 100vh;
            position: sticky;
            top: 0;
            padding: 24px;
            background: rgba(15, 23, 42, 0.96);
            border-right: 1px solid #1e293b;
        }
        .logo { font-size: 20px; font-weight: 700; color: #38bdf8; }
        .sidebar-brand span { display: block; margin-top: 6px; color: #94a3b8; font-size: 13px; }
        .admin-mini {
            margin: 26px 0;
            padding: 16px;
            background: #1e293b;
            border: 1px solid #273449;
            border-radius: 8px;
        }
        .admin-mini strong { display: block; color: #f8fafc; font-size: 15px; }
        .admin-mini span { display: block; margin-top: 5px; color: #94a3b8; font-size: 12px; overflow-wrap: anywhere; }
        .side-nav { display: grid; gap: 8px; }
        .side-nav a {
            padding: 12px 14px;
            border-radius: 8px;
            color: #cbd5f5;
            font-weight: 600;
            transition: 0.2s;
        }
        .side-nav a:hover,
        .side-nav a.active {
            background: #1e293b;
            color: #38bdf8;
        }
        .sidebar-logout { margin-top: 28px; }
        .sidebar-logout button,
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 0;
            border-radius: 8px;
            padding: 12px 18px;
            font-weight: 700;
            cursor: pointer;
            transition: 0.2s;
        }
        .sidebar-logout button,
        .btn-primary {
            background: #38bdf8;
            color: #0f172a;
        }
        .sidebar-logout button { width: 100%; }
        .sidebar-logout button:hover,
        .btn-primary:hover { background: #7dd3fc; }
        .btn-secondary {
            background: transparent;
            color: #38bdf8;
            border: 1px solid #38bdf8;
        }
        .btn-danger { background: #ef4444; color: #fff; }
        .btn-danger:hover { background: #dc2626; }
        .btn-small { padding: 9px 12px; font-size: 13px; }
        .panel-main { flex: 1; min-width: 0; }
        .topbar {
            min-height: 76px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding: 20px 36px;
            background: rgba(15, 23, 42, 0.9);
            border-bottom: 1px solid #1e293b;
        }
        .topbar h1 { font-size: 24px; line-height: 1.2; }
        .topbar p { margin-top: 5px; color: #94a3b8; font-size: 14px; }
        .content-area { padding: 32px 36px; }
        .grid { display: grid; gap: 24px; }
        .stats-grid { grid-template-columns: repeat(auto-fit, minmax(210px, 1fr)); }
        .form-grid { grid-template-columns: repeat(auto-fit, minmax(210px, 1fr)); }
        .form-stack { display: grid; gap: 18px; }
        .card {
            background: #1e293b;
            border: 1px solid #273449;
            border-radius: 8px;
            padding: 24px;
            transition: 0.2s;
        }
        .card:hover { background: #273449; }
        .card h2,
        .card h3 { color: #38bdf8; margin-bottom: 14px; }
        .muted { color: #94a3b8; }
        .stat-value { font-size: 32px; font-weight: 700; color: #f8fafc; }
        .alert {
            margin-bottom: 20px;
            border-radius: 8px;
            padding: 14px 16px;
            background: #082f49;
            color: #bae6fd;
            border: 1px solid #075985;
        }
        .alert-error {
            background: #450a0a;
            color: #fecaca;
            border-color: #991b1b;
        }
        input, select {
            width: 100%;
            padding: 12px 14px;
            background: #0f172a;
            border: 1px solid #334155;
            border-radius: 8px;
            color: #e2e8f0;
            outline: none;
        }
        input:focus,
        select:focus { border-color: #38bdf8; }
        label {
            display: block;
            margin-bottom: 8px;
            color: #cbd5f5;
            font-size: 14px;
            font-weight: 600;
        }
        .field-error {
            margin-top: 8px;
            color: #fca5a5;
            font-size: 13px;
        }
        .success-note {
            color: #7dd3fc;
            font-size: 14px;
            font-weight: 600;
        }
        .actions {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }
        .table-wrap {
            width: 100%;
            overflow-x: auto;
            overflow-y: hidden;
            padding-bottom: 10px;
            -webkit-overflow-scrolling: touch;
        }
        .table-wrap::-webkit-scrollbar { height: 10px; }
        .table-wrap::-webkit-scrollbar-track { background: #0f172a; border-radius: 999px; }
        .table-wrap::-webkit-scrollbar-thumb { background: #334155; border-radius: 999px; }
        .table-wrap::-webkit-scrollbar-thumb:hover { background: #38bdf8; }
        table { width: 100%; border-collapse: collapse; min-width: 900px; }
        .users-table { min-width: 1180px; }
        th, td { padding: 14px; border-bottom: 1px solid #334155; text-align: left; vertical-align: top; }
        th { color: #38bdf8; font-size: 12px; text-transform: uppercase; letter-spacing: 0.05em; }
        td { color: #e2e8f0; }
        .sticky-actions {
            position: sticky;
            right: 0;
            z-index: 2;
            min-width: 190px;
            background: #1e293b;
            box-shadow: -12px 0 18px rgba(15, 23, 42, 0.65);
        }
        th.sticky-actions { z-index: 3; }
        .user-email { margin-top: 4px; font-size: 12px; color: #94a3b8; }
        .inline-form { display: grid; gap: 10px; min-width: 260px; }
        .action-stack { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; min-width: 180px; }
        .assignment-list { display: grid; gap: 8px; min-width: 260px; }
        .assignment-pill {
            display: inline-flex;
            width: fit-content;
            max-width: 100%;
            border: 1px solid #334155;
            border-radius: 999px;
            padding: 7px 11px;
            background: #0f172a;
            color: #cbd5f5;
            font-size: 12px;
            line-height: 1.4;
            white-space: nowrap;
        }
        .activity-row {
            display: flex;
            justify-content: space-between;
            gap: 16px;
            padding: 14px 0;
            border-bottom: 1px solid #334155;
        }
        .pagination { margin-top: 18px; }
        .modal-backdrop {
            position: fixed;
            inset: 0;
            z-index: 50;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 20px;
            background: rgba(2, 6, 23, 0.78);
        }
        .modal-backdrop:target { display: flex; }
        .modal-card {
            width: min(460px, 100%);
            background: #1e293b;
            border: 1px solid #334155;
            border-radius: 8px;
            padding: 24px;
            box-shadow: 0 24px 70px rgba(0, 0, 0, 0.35);
        }
        .modal-card h3 { margin-bottom: 10px; color: #f8fafc; }
        .modal-card p { color: #94a3b8; line-height: 1.6; }
        .modal-card select { margin-top: 16px; }
        .modal-actions {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            margin-top: 22px;
            flex-wrap: wrap;
        }
        @media (max-width: 900px) {
            .panel-shell { flex-direction: column; }
            .panel-sidebar { position: static; width: 100%; min-height: auto; }
            .topbar { align-items: flex-start; flex-direction: column; padding: 20px; }
            .content-area { padding: 20px; }
        }
    </style>
</head>
<body>
    @yield('body')
</body>
</html>
