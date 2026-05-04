@extends('layouts.panel', ['title' => 'Positions'])

@section('body')
<div class="panel-shell">
    @include('user.partials.sidebar')

    <main class="panel-main">
        <header class="topbar">
            <div>
                <h1>Positions Management</h1>
                <p>Manage all positions in the system.</p>
            </div>
            @if(auth()->user()->hasModulePermission('positions', 'create'))
                 <button onclick="openCreatePositionModal()" class="btn btn-primary">Add Position</button>
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
                                <th class="sticky-actions">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($positions as $position)
                                <tr>
                                    <td>{{$loop->iteration }}</td>
                                    <td>{{ $position->id }}</td>
                                    <td><strong>{{ $position->name }}</strong></td>
                                    <td>{{ $position->created_at->format('Y-m-d H:i') }}</td>
                                    <td class="sticky-actions">
                                        <div class="action-stack">
                                            @if(auth()->user()->hasModulePermission('positions', 'edit'))
                                                <button onclick="openUpdatePositionModal({{ $position->id }}, '{{ $position->name }}')" class="btn btn-secondary btn-small">Edit</button>
                                            @endif
                                            @if(auth()->user()->hasModulePermission('positions', 'delete'))
                                                <button onclick="openDeletePositionModal({{ $position->id }}, '{{ $position->name }}')" class="btn btn-danger btn-small">Delete</button>
                                            @endif
                                        </div>
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


<!-- Create Position Modal -->
<div id="createPositionModal" class="modal-backdrop" style="display: none;">
    <div class="modal-card">
        <h3>Create New Position</h3>
        @if ($errors->any())
            <div class="alert alert-error">{{ $errors->first() }}</div>
        @endif
        <form method="POST" action="{{ route('user.positions.store') }}">
            @csrf
            <input name="name" placeholder="Position Name" required value="{{ old('name') }}">
            <div class="modal-actions">
                <button type="button" onclick="closeCreatePositionModal()" class="btn btn-secondary">Cancel</button>
                <button class="btn btn-primary" type="submit">Create</button>
            </div>
        </form>
    </div>
</div>

<!-- Update Position Modal -->
<div id="updatePositionModal" class="modal-backdrop" style="display: none;">
    <div class="modal-card">
        <h3>Update Position</h3>
        @if ($errors->any())
            <div class="alert alert-error">{{ $errors->first() }}</div>
        @endif
        <form method="POST" id="updatePositionForm">
            @csrf
            @method('PUT')
            <input name="name" id="updatePositionName" placeholder="Position Name" required>
            <div class="modal-actions">
                <button type="button" onclick="closeUpdatePositionModal()" class="btn btn-secondary">Cancel</button>
                <button class="btn btn-primary" type="submit">Update</button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Position Modal -->
<div id="deletePositionModal" class="modal-backdrop" style="display: none;">
    <div class="modal-card">
        <h3>Delete Position</h3>
        <p>Are you sure you want to delete <strong id="deletePositionName"></strong>? This action cannot be undone.</p>
        <form method="POST" id="deletePositionForm">
            @csrf
            @method('DELETE')
            <div class="modal-actions">
                <button type="button" onclick="closeDeletePositionModal()" class="btn btn-secondary">Cancel</button>
                <button class="btn btn-danger" type="submit">Delete</button>
            </div>
        </form>
    </div>
</div>

<script>
function openCreatePositionModal() {
    document.getElementById('createPositionModal').style.display = 'flex';
}

function closeCreatePositionModal() {
    document.getElementById('createPositionModal').style.display = 'none';
}

function openUpdatePositionModal(id, name) {
    document.getElementById('updatePositionName').value = name;
    document.getElementById('updatePositionForm').action = '/user/positions/' + id;
    document.getElementById('updatePositionModal').style.display = 'flex';
}

function closeUpdatePositionModal() {
    document.getElementById('updatePositionModal').style.display = 'none';
}

function openDeletePositionModal(id, name) {
    document.getElementById('deletePositionName').textContent = name;
    document.getElementById('deletePositionForm').action = '/user/positions/' + id;
    document.getElementById('deletePositionModal').style.display = 'flex';
}

function closeDeletePositionModal() {
    document.getElementById('deletePositionModal').style.display = 'none';
}

// Keep modal open if there are validation errors
@if ($errors->any())
    document.addEventListener('DOMContentLoaded', function() {
        openCreatePositionModal();
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


