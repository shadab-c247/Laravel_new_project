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
            <a href="{{ route('admin.activity-logs') }}" class="btn btn-secondary">Activity Logs</a>
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

            <section id="create-user" class="card" style="margin-bottom: 24px;">
                <h2>Create User</h2>
                <form method="POST" action="{{ route('admin.users.store') }}" class="grid form-grid">
                    @csrf
                    <input name="name" placeholder="Name" required>
                    <input name="email" type="email" placeholder="Email" required>
                    <input name="password" type="password" placeholder="Password" required>
                    <input name="password_confirmation" type="password" placeholder="Confirm Password" required>
                    @include('admin.partials.assignment-selects')
                    <button class="btn btn-primary" type="submit">Create and Assign</button>
                </form>
            </section>

            <section id="users" class="card" style="margin-bottom: 24px;">
                <h2>Users</h2>
                <div class="table-wrap">
                    <table class="users-table">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Assignments</th>
                                <th>Add Assignment</th>
                                <th class="sticky-actions">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td>
                                        <strong>{{ $user->name }}</strong>
                                        <div class="user-email">{{ $user->email }}</div>
                                    </td>
                                    <td>
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Role</th>
                                                        <th>Department</th>
                                                        <th>Position</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse ($user->userRoles as $assignment)
                                                        <tr>
                                                            <td>{{ $assignment->role?->name ?? 'N/A' }}</td>
                                                            <td>{{ $assignment->department?->name ?? 'N/A' }}</td>
                                                            <td>{{ $assignment->position?->name ?? 'N/A' }}</td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="3" class="text-center text-muted">
                                                                No assignment
                                                            </td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </td>
                                    <td>
                                        <form method="POST" action="{{ route('admin.users.assignment.update', $user) }}" class="inline-form">
                                            @csrf
                                            @method('PUT')
                                            @include('admin.partials.assignment-selects')
                                            <button class="btn btn-primary" type="submit">Add Assignment</button>
                                        </form>
                                    </td>
                                    <td class="sticky-actions">
                                        @if ($user->id === auth()->id())
                                            <span class="muted">Current</span>
                                        @else
                                            <div class="action-stack">
                                                @if ($user->userRoles->isNotEmpty())
                                                    <a href="#switch-user-{{ $user->id }}" class="btn btn-secondary btn-small">Switch</a>
                                                @else
                                                    <span class="muted">No role</span>
                                                @endif

                                                <a href="#delete-user-{{ $user->id }}" class="btn btn-danger btn-small">Delete</a>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>

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

@foreach ($users as $user)
    @if ($user->id !== auth()->id())
        <div id="switch-user-{{ $user->id }}" class="modal-backdrop">
            <div class="modal-card">
                <h3>Switch into {{ $user->name }}</h3>
                <p>Select which role, department and position you want to use for this user session.</p>

                <form method="POST" action="{{ route('admin.users.switch', $user) }}">
                    @csrf
                    <select name="user_role_id" required>
                        <option value="">Choose assignment</option>
                        @foreach ($user->userRoles as $assignment)
                            <option value="{{ $assignment->id }}">
                                {{ $assignment->role?->name ?? 'N/A' }} / {{ $assignment->department?->name ?? 'N/A' }} / {{ $assignment->position?->name ?? 'N/A' }}
                            </option>
                        @endforeach
                    </select>

                    <div class="modal-actions">
                        <a href="#users" class="btn btn-secondary">Cancel</a>
                        <button class="btn btn-primary" type="submit">Confirm Switch</button>
                    </div>
                </form>
            </div>
        </div>

        <div id="delete-user-{{ $user->id }}" class="modal-backdrop">
            <div class="modal-card">
                <h3>Delete {{ $user->name }}?</h3>
                <p>
                    This will permanently delete <strong>{{ $user->email }}</strong> and related role assignment data.
                    This action cannot be undone.
                </p>

                <div class="modal-actions">
                    <a href="#users" class="btn btn-secondary">Cancel</a>
                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger" type="submit">Confirm Delete</button>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endforeach
@endsection
