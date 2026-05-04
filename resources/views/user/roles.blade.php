@extends('layouts.panel', ['title' => 'Roles Management'])

@section('body')
<div class="panel-shell">
    @include('user.partials.sidebar')

    <main class="panel-main">
        <header class="topbar">
            <div>
                <h1>Roles Management</h1>
                <p>Manage all roles in the system.</p>
            </div>
            @if(auth()->user()->hasModulePermission('roles', 'create'))
                <button onclick="openCreateRoleModal()" class="btn btn-primary">Add Role</button>
            @endif
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
                                <th>ID</th>
                                <th>Name</th>
                                <th>Created At</th>
                                @if(auth()->user()->hasModulePermission('roles', 'edit') || auth()->user()->hasModulePermission('roles', 'delete'))
                                    <th class="sticky-actions">Actions</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($roles as $role)
                                <tr>
                                    <td>{{$loop->iteration }}</td>
                                    <td>{{ $role->id }}</td>
                                    <td><strong>{{ $role->name }}</strong></td>
                                    <td>{{ $role->created_at->format('Y-m-d H:i') }}</td>
                                    @if(auth()->user()->hasModulePermission('roles', 'edit') || auth()->user()->hasModulePermission('roles', 'delete'))
                                        <td class="sticky-actions">
                                            <div class="action-stack">
                                                @if(auth()->user()->hasModulePermission('roles', 'edit'))
                                                    <button onclick="openUpdateRoleModal({{ $role->id }}, '{{ $role->name }}')" class="btn btn-secondary btn-small">Edit</button>
                                                @endif
                                                @if(auth()->user()->hasModulePermission('roles', 'delete'))
                                                    <button onclick="openDeleteRoleModal({{ $role->id }}, '{{ $role->name }}')" class="btn btn-danger btn-small">Delete</button>
                                                @endif
                                            </div>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </main>
</div>

<!-- Create Role Modal -->
<div id="createRoleModal" class="modal-backdrop" style="display: none;">
    <div class="modal-card">
        <h3>Create New Role</h3>
        @if ($errors->any())
            <div class="alert alert-error">{{ $errors->first() }}</div>
        @endif
        <form method="POST" action="{{ route('user.roles.store') }}">
            @csrf
            <input name="name" placeholder="Role Name" required value="{{ old('name') }}">
            <div class="modal-actions">
                <button type="button" onclick="closeCreateRoleModal()" class="btn btn-secondary">Cancel</button>
                <button class="btn btn-primary" type="submit">Create</button>
            </div>
        </form>
    </div>
</div>

<!-- Update Role Modal -->
<div id="updateRoleModal" class="modal-backdrop" style="display: none;">
    <div class="modal-card">
        <h3>Update Role</h3>
        @if ($errors->any())
            <div class="alert alert-error">{{ $errors->first() }}</div>
        @endif
        <form method="POST" id="updateRoleForm">
            @csrf
            @method('PUT')
            <input name="name" id="updateRoleName" placeholder="Role Name" required>
            <div class="modal-actions">
                <button type="button" onclick="closeUpdateRoleModal()" class="btn btn-secondary">Cancel</button>
                <button class="btn btn-primary" type="submit">Update</button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Role Modal -->
<div id="deleteRoleModal" class="modal-backdrop" style="display: none;">
    <div class="modal-card">
        <h3>Delete Role</h3>
        <p>Are you sure you want to delete <strong id="deleteRoleName"></strong>? This action cannot be undone.</p>
        <form method="POST" id="deleteRoleForm">
            @csrf
            @method('DELETE')
            <div class="modal-actions">
                <button type="button" onclick="closeDeleteRoleModal()" class="btn btn-secondary">Cancel</button>
                <button class="btn btn-danger" type="submit">Delete</button>
            </div>
        </form>
    </div>
</div>

<script>
function openCreateRoleModal() {
    document.getElementById('createRoleModal').style.display = 'flex';
}

function closeCreateRoleModal() {
    document.getElementById('createRoleModal').style.display = 'none';
}

function openUpdateRoleModal(id, name) {
    document.getElementById('updateRoleName').value = name;
    document.getElementById('updateRoleForm').action = '/user/roles/' + id;
    document.getElementById('updateRoleModal').style.display = 'flex';
}

function closeUpdateRoleModal() {
    document.getElementById('updateRoleModal').style.display = 'none';
}

function openDeleteRoleModal(id, name) {
    document.getElementById('deleteRoleName').textContent = name;
    document.getElementById('deleteRoleForm').action = '/user/roles/' + id;
    document.getElementById('deleteRoleModal').style.display = 'flex';
}

function closeDeleteRoleModal() {
    document.getElementById('deleteRoleModal').style.display = 'none';
}

// Keep modal open if there are validation errors
@if ($errors->any())
    document.addEventListener('DOMContentLoaded', function() {
        openCreateRoleModal();
    });
@endif

// Close modals when clicking outside
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('modal-backdrop')) {
        e.target.style.display = 'none';
    }
});
</script>
@endsection
