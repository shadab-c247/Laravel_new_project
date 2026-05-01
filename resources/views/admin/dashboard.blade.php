@extends('layouts.panel', ['title' => 'Admin Panel'])

@section('body')
<div class="panel-shell">
    @include('admin.partials.sidebar')

    <main class="panel-main">
        <header class="topbar">
            <div>
                <h1>Admin Panel</h1>
                <p>Manage users, departments, positions, roles and account activity from one place.</p>
            </div>
            <!-- <a href="{{ route('admin.activity-logs') }}" class="btn btn-secondary">Activity Logs</a> -->
        </header>

        <section class="content-area">
            @if (session('status'))
                <div class="alert">{{ session('status') }}</div>
            @endif

            @if ($errors->any())
                <div class="alert alert-error">{{ $errors->first() }}</div>
            @endif

            <div class="grid stats-grid" style="margin-bottom: 24px;">
                <div class="card">
                    <h3>Total Users</h3>
                    <div class="stat-value">{{ $users->count() }}</div>
                    <p class="muted">Registered accounts</p>
                </div>
                <div class="card">
                    <h3>Departments</h3>
                    <div class="stat-value">{{ $departments->count() }}</div>
                    <p class="muted">Available departments</p>
                </div>
                <div class="card">
                    <h3>Roles</h3>
                    <div class="stat-value">{{ $roles->count() }}</div>
                    <p class="muted">Assignable roles</p>
                </div>
                <div class="card">
                    <h3>Positions</h3>
                    <div class="stat-value">{{ $positions->count() }}</div>
                    <p class="muted">Assignable positions</p>
                </div>
            </div>

            <section class="card">
                <h2>Recent Activity</h2>
                @forelse ($activityLogs as $log)
                    <div class="activity-row">
                        <span>{{ $log->description ?? $log->action }}</span>
                        <span class="muted">{{ $log->user?->email ?? 'System' }} · {{ $log->created_at->diffForHumans() }}</span>
                    </div>
                @empty
                    <p class="muted">No activity yet.</p>
                @endforelse
            </section>
        </section>
    </main>
</div>
@endsection
