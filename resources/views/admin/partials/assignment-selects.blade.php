@php
    $selectedUser = $selectedUser ?? null;
    $selectedAssignment = $selectedAssignment ?? $selectedUser?->userRoles?->first();
    $roleId = old('role_id', $selectedAssignment?->role_id);
    $departmentId = old('department_id', $selectedAssignment?->department_id);
    $positionId = old('position_id', $selectedAssignment?->position_id);
@endphp

<select name="role_id" required>
    <option value="">Role</option>
    @foreach ($roles as $role)
        <option value="{{ $role->id }}" @selected((int) $roleId === (int) $role->id)>{{ ucfirst($role->name) }}</option>
    @endforeach
</select>

<select name="department_id" required>
    <option value="">Department</option>
    @foreach ($departments as $department)
        <option value="{{ $department->id }}" @selected((int) $departmentId === (int) $department->id)>{{ $department->name }}</option>
    @endforeach
</select>

<select name="position_id" required>
    <option value="">Position</option>
    @foreach ($positions as $position)
        <option value="{{ $position->id }}" @selected((int) $positionId === (int) $position->id)>{{ $position->name }}</option>
    @endforeach
</select>
