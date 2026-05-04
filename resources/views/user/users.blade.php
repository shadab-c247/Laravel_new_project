@extends('layouts.panel', ['title' => 'Users'])

@section('body')
<div class="panel-shell">
    @include('user.partials.sidebar')

    <main class="panel-main">
        <header class="topbar">
            <div>
                <h1>Users Management</h1>
                <p>Manage all users and their assignments.</p>
            </div>
            @if(auth()->user()->hasModulePermission('users', 'create'))
               <button onclick="openCreateUserModal()" class="btn btn-primary">Create User</button>
            @endif
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
                                @if(auth()->user()->hasModulePermission('users', 'edit'))
                                    <th>Add Assignment</th>
                                @endif
                                <th class="sticky-actions">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td>{{$loop->iteration }}</td>
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
                                        @if(auth()->user()->hasModulePermission('users', 'edit'))
                                            <form method="POST" action="{{ route('user.users.assignment.update', $user) }}" class="inline-form">
                                                @csrf
                                                @method('PUT')
                                                @include('admin.partials.assignment-selects')
                                                <button class="btn btn-primary" type="submit">Add Assignment</button>
                                            </form>
                                        @endif  
                                    </td>
                                    <td class="sticky-actions">
                                        @if ($user->id === auth()->id())
                                            <span class="muted">Current</span>
                                        @else
                                            <div class="action-stack">
                                                @if(auth()->user()->hasModulePermission('users', 'switch'))
                                                    @if ($user->userRoles->isNotEmpty())
                                                        <a href="#switch-user-{{ $user->id }}" class="btn btn-secondary btn-small">Switch</a>
                                                    @else
                                                        <span class="muted">No role</span>
                                                    @endif
                                                @endif

                                                @if(auth()->user()->hasModulePermission('users', 'delete'))
                                                    <a href="#delete-user-{{ $user->id }}" class="btn btn-danger btn-small">Delete</a>
                                                @endif
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </main>
</div>


<!-- Create User Modal -->
<div id="createUserModal" class="modal-backdrop" style="display: none;">
    <div class="modal-card">
        <h3>Create New User</h3>
        @if ($errors->any())
            <div class="alert alert-error">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif
        <form method="POST" action="{{ route('user.users.store') }}" class="form-grid">
            @csrf
            <input name="name" placeholder="Name" required value="{{ old('name') }}">
            <input name="email" type="email" placeholder="Email" required value="{{ old('email') }}">
           <div class="password-field">
                <input name="password" type="password" id="password" placeholder="Password" required>
                
                <button type="button" onclick="togglePassword('password')" class="eye-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                        <circle cx="12" cy="12" r="3"></circle>
                    </svg>
                </button>
            </div>

            <div class="password-field">
                <input name="password_confirmation" type="password" id="password_confirmation" placeholder="Confirm Password" required>
                
                <button type="button" onclick="togglePassword('password_confirmation')" class="eye-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                        <circle cx="12" cy="12" r="3"></circle>
                    </svg>
                </button>
            </div>
            @include('admin.partials.assignment-selects')
            <div class="modal-actions">
                <button type="button" onclick="closeCreateUserModal()" class="btn btn-secondary">Cancel</button>
                <button class="btn btn-primary" type="submit">Create and Assign</button>
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
            <form method="POST" action="{{ route('user.users.destroy', $user) }}">
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
