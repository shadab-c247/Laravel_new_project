@extends('layouts.panel', ['title' => 'User Panel'])

@section('body')
<div class="panel-shell">
    @include('user.partials.sidebar')

    <main class="panel-main">
        <header class="topbar">
            <div>
                <h1>User Panel</h1>
                <p>Your login profile and the currently selected department, position and role details.</p>
            </div>

            @if (session('admin_impersonator_id'))
                <form method="POST" action="{{ route('admin.switch-back') }}">
                    @csrf
                    <button class="btn btn-primary" type="submit">Back to Admin Panel</button>
                </form>
            @endif
        </header>

        <section class="content-area">
            @if (session('status'))
                <div class="alert">{{ session('status') }}</div>
            @endif

            @if (session('admin_impersonator_id'))
                <div class="alert">
                    You are viewing this account as an admin. Use “Back to Admin Panel” to return.
                </div>
            @endif

            <div class="grid stats-grid" style="margin-bottom: 24px;">
    
    <div class="card">
        <h3>Department</h3>
        <div class="stat-value" style="font-size: 18px;">
            @foreach($userRoles as $role)
                <div>
                    {{ $role->department?->name ?? 'N/A' }}
                    
                    @if($selectedUserRole?->id === $role->id)
                        <span style="color:#38bdf8;">(Active)</span>
                    @endif
                </div>
            @endforeach
        </div>
        <p class="muted">All departments</p>
    </div>

    <div class="card">
        <h3>Role</h3>
        <div class="stat-value" style="font-size: 18px;">
            @foreach($userRoles as $role)
                <div>
                    {{ $role->role?->name ?? 'N/A' }}

                    @if($selectedUserRole?->id === $role->id)
                        <span style="color:#38bdf8;">(Active)</span>
                    @endif
                </div>
            @endforeach
        </div>
        <p class="muted">All roles</p>
    </div>

    <div class="card">
        <h3>Position</h3>
        <div class="stat-value" style="font-size: 18px;">
            @foreach($userRoles as $role)
                <div>
                    {{ $role->position?->name ?? 'N/A' }}

                    @if($selectedUserRole?->id === $role->id)
                        <span style="color:#38bdf8;">(Active)</span>
                    @endif
                </div>
            @endforeach
        </div>
        <p class="muted">All positions</p>
    </div>

</div>

            <section class="card">
                <h2>Logged In User Details</h2>
                <div class="table-wrap">
                    <table>
                        <tbody>
                            <tr>
                                <th>Name</th>
                                <td>{{ $user->name }}</td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td>{{ $user->email }}</td>
                            </tr>
                            <tr>
                                <th>Department</th>
                                <td>{{ $selectedUserRole?->department?->name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Role</th>
                                <td>{{ $selectedUserRole?->role?->name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Position</th>
                                <td>{{ $selectedUserRole?->position?->name ?? 'N/A' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>
        </section>
    </main>
</div>
@endsection
