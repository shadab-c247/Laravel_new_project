<aside class="panel-sidebar">
    <div class="sidebar-brand">
        <div class="logo">SecureAuth</div>
        <span>Admin Console</span>
    </div>

    <div class="admin-mini">
        <strong>{{ auth()->user()->name }}</strong>
        <span>{{ auth()->user()->email }}</span>
    </div>

    <nav class="side-nav">
        <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">Dashboard</a>
        <a href="{{ route('admin.users') }}" class="{{ request()->routeIs('admin.users') ? 'active' : '' }}">Users</a>
        <a href="{{ route('admin.departments') }}" class="{{ request()->routeIs('admin.departments') ? 'active' : '' }}">Departments</a>
        <a href="{{ route('admin.roles') }}" class="{{ request()->routeIs('admin.roles') ? 'active' : '' }}">Roles</a>
        <a href="{{ route('admin.positions') }}" class="{{ request()->routeIs('admin.positions') ? 'active' : '' }}">Positions</a>
        <a href="{{ route('admin.activity-logs') }}" class="{{ request()->routeIs('admin.activity-logs') ? 'active' : '' }}">Activity Logs</a>
        <a href="{{ route('profile.edit') }}">Profile</a>
    </nav>

    <form method="POST" action="{{ route('logout') }}" class="sidebar-logout">
        @csrf
        <button type="submit">Logout</button>
    </form>
</aside>
