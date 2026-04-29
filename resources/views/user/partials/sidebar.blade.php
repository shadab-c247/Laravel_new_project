<aside class="panel-sidebar">
    <div class="sidebar-brand">
        <div class="logo">SecureAuth</div>
        <span>User Panel</span>
    </div>

    <div class="admin-mini">
        <strong>{{ auth()->user()->name }}</strong>
        <span>{{ auth()->user()->email }}</span>
    </div>

    <nav class="side-nav">
        <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') || request()->routeIs('user.dashboard') ? 'active' : '' }}">Dashboard</a>
        <a href="{{ route('profile.edit') }}">Profile</a>
    </nav>

    @if (session('admin_impersonator_id'))
        <form method="POST" action="{{ route('admin.switch-back') }}" class="sidebar-logout">
            @csrf
            <button type="submit">Back to Admin Panel</button>
        </form>
    @else
        <form method="POST" action="{{ route('logout') }}" class="sidebar-logout">
            @csrf
            <button type="submit">Logout</button>
        </form>
    @endif
</aside>
