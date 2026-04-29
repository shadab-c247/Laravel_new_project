@extends('layouts.panel', ['title' => 'Activity Logs'])

@section('body')
<div class="panel-shell">
    @include('admin.partials.sidebar')

    <main class="panel-main">
        <header class="topbar">
            <div>
                <h1>Activity Logs</h1>
                <p>Track login, switching and admin actions performed inside the system.</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Admin Panel</a>
        </header>

        <section class="content-area">
            <div class="card">
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>Time</th>
                                <th>User</th>
                                <th>Action</th>
                                <th>Description</th>
                                <th>IP</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($activityLogs as $log)
                                <tr>
                                    <td>{{ $log->created_at->format('d M Y, h:i A') }}</td>
                                    <td>{{ $log->user?->email ?? 'System' }}</td>
                                    <td>{{ $log->action }}</td>
                                    <td>{{ $log->description }}</td>
                                    <td>{{ $log->ip_address }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="muted">No activity recorded.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="pagination">
                    {{ $activityLogs->links() }}
                </div>
            </div>
        </section>
    </main>
</div>
@endsection
