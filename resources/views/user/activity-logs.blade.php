@extends('layouts.panel', ['title' => 'Activity Logs'])

@section('body')
<div class="panel-shell">
    @include('user.partials.sidebar')

    <main class="panel-main">
        <header class="topbar">
            <div>
                <h1>Activity Logs</h1>
                <p>View all system activity logs.</p>
            </div>
        </header>

        <section class="content-area">
            @if (session('status'))
                <div class="alert">{{ session('status') }}</div>
            @endif

            <div class="card">
                @forelse ($activityLogs as $log)
                    <div class="activity-row">
                        <span>{{ $log->description ?? $log->action }}</span>
                        <span class="muted">{{ $log->user?->email ?? 'System' }} · {{ $log->created_at->format('d M Y, h:i A') }}</span>
                    </div>
                @empty
                    <p class="muted">No activity yet.</p>
                @endforelse
            </div>
        </section>
    </main>
</div>
@endsection
