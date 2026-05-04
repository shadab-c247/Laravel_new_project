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
        
        @php
            $accessibleModules = auth()->user()->getAccessibleModules();
        @endphp
        
        @foreach($accessibleModules as $module)
            @if($module->admin_route && $module->slug !== 'dashboard')
                <a href="{{ route($module->admin_route) }}" class="{{ request()->routeIs($module->admin_route) ? 'active' : '' }}">
                    {{ $module->name }}
                </a>
            @endif
        @endforeach
        
        <a href="{{ route('admin.permissions.index') }}" class="{{ request()->routeIs('admin.permissions.*') ? 'active' : '' }}">Permissions</a>
        <a href="{{ route('profile.edit') }}">Profile</a>
    </nav>

    <form method="POST" action="{{ route('logout') }}" class="sidebar-logout">
        @csrf
        <button type="submit">Logout</button>
    </form>
</aside>
