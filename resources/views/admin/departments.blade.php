@extends('layouts.panel', ['title' => 'Departments Management'])

@section('body')
<div class="panel-shell">
    @include('admin.partials.sidebar')

    <main class="panel-main">
        <header class="topbar">
            <div>
                <h1>Departments Management</h1>
                <p>Manage all departments in the system.</p>
            </div>
            <button onclick="openCreateDepartmentModal()" class="btn btn-primary">Add Department</button>
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
                                <th class="sticky-actions">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($departments as $department)
                                <tr>
                                    <td>{{ ($departments->currentPage() - 1) * $departments->perPage() + $loop->iteration }}</td>
                                    <td>{{ $department->id }}</td>
                                    <td><strong>{{ $department->name }}</strong></td>
                                    <td>{{ $department->created_at->format('Y-m-d H:i') }}</td>
                                    <td class="sticky-actions">
                                        <div class="action-stack">
                                            <button onclick="openUpdateDepartmentModal({{ $department->id }}, '{{ $department->name }}')" class="btn btn-secondary btn-small">Edit</button>
                                            <button onclick="openDeleteDepartmentModal({{ $department->id }}, '{{ $department->name }}')" class="btn btn-danger btn-small">Delete</button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="pagination">
                    {{ $departments->links() }}
                </div>
            </div>
        </section>
    </main>
</div>

<!-- Create Department Modal -->
<div id="createDepartmentModal" class="modal-backdrop" style="display: none;">
    <div class="modal-card">
        <h3>Create New Department</h3>
        @if ($errors->any())
            <div class="alert alert-error">{{ $errors->first() }}</div>
        @endif
        <form method="POST" action="{{ route('admin.departments.store') }}">
            @csrf
            <input name="name" placeholder="Department Name" required value="{{ old('name') }}">
            <div class="modal-actions">
                <button type="button" onclick="closeCreateDepartmentModal()" class="btn btn-secondary">Cancel</button>
                <button class="btn btn-primary" type="submit">Create</button>
            </div>
        </form>
    </div>
</div>

<!-- Update Department Modal -->
<div id="updateDepartmentModal" class="modal-backdrop" style="display: none;">
    <div class="modal-card">
        <h3>Update Department</h3>
        @if ($errors->any())
            <div class="alert alert-error">{{ $errors->first() }}</div>
        @endif
        <form method="POST" id="updateDepartmentForm">
            @csrf
            @method('PUT')
            <input name="name" id="updateDepartmentName" placeholder="Department Name" required>
            <div class="modal-actions">
                <button type="button" onclick="closeUpdateDepartmentModal()" class="btn btn-secondary">Cancel</button>
                <button class="btn btn-primary" type="submit">Update</button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Department Modal -->
<div id="deleteDepartmentModal" class="modal-backdrop" style="display: none;">
    <div class="modal-card">
        <h3>Delete Department</h3>
        <p>Are you sure you want to delete <strong id="deleteDepartmentName"></strong>? This action cannot be undone.</p>
        <form method="POST" id="deleteDepartmentForm">
            @csrf
            @method('DELETE')
            <div class="modal-actions">
                <button type="button" onclick="closeDeleteDepartmentModal()" class="btn btn-secondary">Cancel</button>
                <button class="btn btn-danger" type="submit">Delete</button>
            </div>
        </form>
    </div>
</div>

<script>
function openCreateDepartmentModal() {
    document.getElementById('createDepartmentModal').style.display = 'flex';
}

function closeCreateDepartmentModal() {
    document.getElementById('createDepartmentModal').style.display = 'none';
}

function openUpdateDepartmentModal(id, name) {
    document.getElementById('updateDepartmentName').value = name;
    document.getElementById('updateDepartmentForm').action = '/admin/departments/' + id;
    document.getElementById('updateDepartmentModal').style.display = 'flex';
}

function closeUpdateDepartmentModal() {
    document.getElementById('updateDepartmentModal').style.display = 'none';
}

function openDeleteDepartmentModal(id, name) {
    document.getElementById('deleteDepartmentName').textContent = name;
    document.getElementById('deleteDepartmentForm').action = '/admin/departments/' + id;
    document.getElementById('deleteDepartmentModal').style.display = 'flex';
}

function closeDeleteDepartmentModal() {
    document.getElementById('deleteDepartmentModal').style.display = 'none';
}

// Keep modal open if there are validation errors
@if ($errors->any())
    document.addEventListener('DOMContentLoaded', function() {
        openCreateDepartmentModal();
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
