@extends('layouts.panel', ['title' => 'Permissions Management'])

@section('body')
<div class="panel-shell">
    @include('admin.partials.sidebar')

    <main class="panel-main">
        <header class="topbar">
            <div>
                <h1>Permissions Management</h1>
                <p>Manage module-based permissions for users.</p>
            </div>
        </header>

        <section class="content-area">
            @if (session('status'))
                <div class="alert">{{ session('status') }}</div>
            @endif

            <div class="card">
                <div class="table-wrap">
                    <table class="users-table">
                        <thead>
                            <tr>
                                <th>S.No.</th>
                                <th>User</th>
                                <th>Assignments</th>
                                <th class="sticky-actions">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td>{{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}</td>
                                    <td>
                                        <strong>{{ $user->name }}</strong>
                                        <div class="user-email">{{ $user->email }}</div>
                                    </td>
                                    <td>
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>S.No.</th>
                                                        <th>Role</th>
                                                        <th>Department</th>
                                                        <th>Position</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse ($user->userRoles as $assignment)
                                                        <tr>
                                                            <td>{{ $loop->iteration }}</td>
                                                            <td>{{ $assignment->role?->name ?? 'N/A' }}</td>
                                                            <td>{{ $assignment->department?->name ?? 'N/A' }}</td>
                                                            <td>{{ $assignment->position?->name ?? 'N/A' }}</td>
                                                            <td>
                                                                <a href="{{ route('admin.permissions.manage', [$user->id, $assignment->id]) }}" class="btn btn-primary btn-small">Manage Permissions</a>
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="5" class="text-center text-muted">
                                                                No assignment
                                                            </td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </td>
                                    <td class="sticky-actions">
                                        @if ($user->userRoles->isNotEmpty())
                                            <span class="muted">See assignments</span>
                                        @else
                                            <span class="muted">No role</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="pagination">
                    {{ $users->links() }}
                </div>
            </div>
        </section>
    </main>
</div>
@endsection
