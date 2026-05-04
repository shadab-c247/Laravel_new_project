@extends('layouts.panel', ['title' => 'Manage Permissions'])

@section('body')
<div class="panel-shell">
    @include('admin.partials.sidebar')

    <main class="panel-main">
        <header class="topbar">
            <div>
                <h1>Manage Permissions</h1>
                <p>Configure module permissions for {{ $user->name }} ({{ $user->email }})</p>
            </div>
            <a href="{{ route('admin.permissions.index') }}" class="btn btn-secondary">Back to List</a>
        </header>

        <section class="content-area">
            @if (session('status'))
                <div class="alert">{{ session('status') }}</div>
            @endif

            @if ($errors->any())
                <div class="alert alert-error">{{ $errors->first() }}</div>
            @endif
            <div class="card">
                <div class="assignment-info">
                    <h3>Current Assignment</h3>
                    <p>
                        <strong>Role:</strong> {{ $userRole->role?->name ?? 'N/A' }} |
                        <strong>Department:</strong> {{ $userRole->department?->name ?? 'N/A' }} |
                        <strong>Position:</strong> {{ $userRole->position?->name ?? 'N/A' }}
                    </p>
                </div>

                <form method="POST" action="{{ route('admin.permissions.store', [$user->id, $userRole->id]) }}">
                    @csrf
                    <div class="permissions-grid">
                        @foreach ($modules as $module)
                            <div class="module-card">
                                <h4>{{ $module->name }}</h4>
                                
                                @foreach ($module->modulePermissions as $modulePermission)
                                    <div class="permission-row">
                                        <div class="permission-label">
                                            <strong>{{ $modulePermission->name }}</strong>
                                            <small>{{ $modulePermission->slug }}</small>
                                        </div>
                                        <div class="permission-checkboxes">
                                            <label class="checkbox-label">
                                                <input type="checkbox" 
                                                    name="permissions[{{ $modulePermission->id }}][module_permission_id]" 
                                                    value="{{ $modulePermission->id }}"
                                                    {{ $existingPermissions->has($modulePermission->id) ? 'checked' : '' }}
                                                    onchange="togglePermissionCheckboxes(this, {{ $modulePermission->id }})"
                                                >
                                                <span>Enable</span>
                                            </label>
                                            
                                            <div class="action-checkboxes" id="checkboxes-{{ $modulePermission->id }}" 
                                                style="{{ $existingPermissions->has($modulePermission->id) ? '' : 'display: none;' }}">
                                                @if ($modulePermission->action === 'view')
                                                    <label class="checkbox-label">
                                                        <input type="checkbox" 
                                                            name="permissions[{{ $modulePermission->id }}][can_view]" 
                                                            value="1"
                                                            {{ $existingPermissions->has($modulePermission->id) && $existingPermissions[$modulePermission->id]->can_view ? 'checked' : '' }}
                                                        >
                                                        <span>View</span>
                                                    </label>
                                                @endif

                                                @if ($modulePermission->action === 'create')
                                                    <label class="checkbox-label">
                                                        <input type="checkbox" 
                                                            name="permissions[{{ $modulePermission->id }}][can_create]" 
                                                            value="1"
                                                            {{ $existingPermissions->has($modulePermission->id) && $existingPermissions[$modulePermission->id]->can_create ? 'checked' : '' }}
                                                        >
                                                        <span>Create</span>
                                                    </label>
                                                @endif

                                                @if ($modulePermission->action === 'edit')
                                                    <label class="checkbox-label">
                                                        <input type="checkbox" 
                                                            name="permissions[{{ $modulePermission->id }}][can_edit]" 
                                                            value="1"
                                                            {{ $existingPermissions->has($modulePermission->id) && $existingPermissions[$modulePermission->id]->can_edit ? 'checked' : '' }}
                                                        >
                                                        <span>Edit</span>
                                                    </label>
                                                @endif

                                                @if ($modulePermission->action === 'delete')
                                                    <label class="checkbox-label">
                                                        <input type="checkbox" 
                                                            name="permissions[{{ $modulePermission->id }}][can_delete]" 
                                                            value="1"
                                                            {{ $existingPermissions->has($modulePermission->id) && $existingPermissions[$modulePermission->id]->can_delete ? 'checked' : '' }}
                                                        >
                                                        <span>Delete</span>
                                                    </label>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Save Permissions</button>
                        <a href="{{ route('admin.permissions.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </section>
    </main>
</div>

<style>
.permissions-grid {
    display: grid;
    gap: 20px;
    margin-top: 20px;
}

.module-card {
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    padding: 20px;
    background: #f9fafb;
}

.module-card h4 {
    margin: 0 0 15px 0;
    color: #1f2937;
    border-bottom: 2px solid #e5e7eb;
    padding-bottom: 10px;
}

.permission-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 0;
    border-bottom: 1px solid #e5e7eb;
}

.permission-row:last-child {
    border-bottom: none;
}

.permission-label {
    flex: 1;
}

.permission-label strong {
    display: block;
    color: #374151;
}

.permission-label small {
    color: #6b7280;
    font-size: 0.85em;
}

.permission-checkboxes {
    display: flex;
    gap: 15px;
    align-items: center;
}

.checkbox-label {
    display: flex;
    align-items: center;
    gap: 6px;
    cursor: pointer;
}

.checkbox-label input[type="checkbox"] {
    width: 18px;
    height: 18px;
    cursor: pointer;
}

.checkbox-label span {
    font-size: 0.9em;
    color: #374151;
}

.action-checkboxes {
    display: flex;
    gap: 10px;
}

.assignment-info {
    background: #f0f9ff;
    border: 1px solid #bae6fd;
    border-radius: 8px;
    padding: 15px;
    margin-bottom: 20px;
}

.assignment-info h3 {
    margin: 0 0 10px 0;
    color: #0369a1;
}

.assignment-info p {
    margin: 0;
    color: #0c4a6e;
}

.form-actions {
    margin-top: 30px;
    display: flex;
    gap: 10px;
}
</style>

<script>
function togglePermissionCheckboxes(checkbox, permissionId) {
    const checkboxesDiv = document.getElementById('checkboxes-' + permissionId);
    if (checkbox.checked) {
        checkboxesDiv.style.display = 'flex';
    } else {
        checkboxesDiv.style.display = 'none';
        // Uncheck all action checkboxes
        const actionCheckboxes = checkboxesDiv.querySelectorAll('input[type="checkbox"]');
        actionCheckboxes.forEach(cb => cb.checked = false);
    }
}
</script>
@endsection
