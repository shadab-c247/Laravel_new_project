@extends('layouts.panel', ['title' => 'Users Management'])

@section('body')
<div class="panel-shell">
    @include('admin.partials.sidebar')

    <main class="panel-main">
        <header class="topbar">
            <div>
                <h1>Users Management</h1>
                <p>Manage all users and their assignments.</p>
            </div>
            <button onclick="openCreateUserModal()" class="btn btn-primary">Create User</button>
        </header>

        <section class="content-area">
            @if (session('status'))
                <div class="alert">{{ session('status') }}</div>
            @endif

            @if ($errors->any())
                <div class="alert alert-error">{{ $errors->first() }}</div>
            @endif

            <div class="card">
                <div class="table-wrap">
                    <table class="users-table">
                        <thead>
                            <tr>
                                <th>S.No.</th>
                                <th>User</th>
                                <th>Assignments</th>
                                <th>Add Assignment</th>
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
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse ($user->userRoles as $assignment)
                                                        <tr>
                                                            <td>{{ $loop->iteration }}</td>
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
                <div class="pagination">
                    {{ $users->links() }}
                </div>
            </div>
        </section>
    </main>
</div>

<!-- Activity Export Modal -->
<div id="exportModal" class="modal-backdrop" style="display: none;">
    <div class="modal-card">
        <h3>Export Activities</h3>

        <form method="GET" action="{{ route('admin.activities.export') }}" class="form-grid">
            
            <!-- Step 1: Format -->
            <label>Export Format</label>
            <select name="format" id="format" required onchange="toggleDateFields()">
                <option value="">Select Format</option>
                <option value="excel">Excel</option>
                <option value="pdf">PDF</option>
            </select>

            <!-- Step 2: Date Range -->
            <div id="dateRangeFields" style="display: none;">
                <label>From Date</label>
                <input type="date" name="from_date">

                <label>To Date</label>
                <input type="date" name="to_date">
            </div>

            <div class="modal-actions">
                <button type="button" onclick="closeExportModal()" class="btn btn-secondary">Cancel</button>
                <button class="btn btn-primary" type="submit">Export</button>
            </div>
        </form>
    </div>
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
                        <option value="">Select Assignment</option>
                        @foreach ($user->userRoles as $assignment)
                            <option value="{{ $assignment->id }}">
                                {{ $assignment->role?->name ?? 'N/A' }} / {{ $assignment->department?->name ?? 'N/A' }} / {{ $assignment->position?->name ?? 'N/A' }}
                            </option>
                        @endforeach
                    </select>
                    <div class="modal-actions">
                        <a href="#" class="btn btn-secondary" onclick="closeModal('switch-user-{{ $user->id }}')">Cancel</a>
                        <button class="btn btn-primary" type="submit">Switch</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <div id="delete-user-{{ $user->id }}" class="modal-backdrop">
        <div class="modal-card">
            <h3>Delete {{ $user->name }}</h3>
            <p>Are you sure you want to delete this user? This action cannot be undone.</p>
            <form method="POST" action="{{ route('admin.users.destroy', $user) }}">
                @csrf
                @method('DELETE')
                <div class="modal-actions">
                    <a href="#" class="btn btn-secondary" onclick="closeModal('delete-user-{{ $user->id }}')">Cancel</a>
                    <button class="btn btn-danger" type="submit">Delete</button>
                </div>
            </form>
        </div>
    </div>
@endforeach

<script>
function openCreateUserModal() {
    document.getElementById('createUserModal').style.display = 'flex';
}

function closeCreateUserModal() {
    document.getElementById('createUserModal').style.display = 'none';
}

function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const btn = field.nextElementSibling;
    const svg = btn.querySelector('svg');
    
    if (field.type === 'password') {
        field.type = 'text';
        // Eye off icon
        svg.innerHTML = `
            <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
            <line x1="1" y1="1" x2="23" y2="23"></line>
        `;
    } else {
        field.type = 'password';
        // Eye icon
        svg.innerHTML = `
            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
            <circle cx="12" cy="12" r="3"></circle>
        `;
    }
}

function closeModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
}

// Keep modal open if there are validation errors
@if ($errors->any())
    document.addEventListener('DOMContentLoaded', function() {
        openCreateUserModal();
    });
@endif

// Close modal when clicking outside
document.addEventListener('click', function(e) {
    const modal = document.getElementById('createUserModal');
    if (e.target === modal) {
        closeCreateUserModal();
    }
});
</script>
@endsection
