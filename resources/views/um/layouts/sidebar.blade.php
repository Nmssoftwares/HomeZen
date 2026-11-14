<aside id="sidebar" class="sidebar">
    <ul class="sidebar-nav" id="sidebar-nav">

        <!-- Dashboard -->
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('um.dashboard') ? '' : 'collapsed' }}" href="{{ route('um.dashboard') }}">
                <i class="bi bi-speedometer2"></i>
                <span>Dashboard</span>
            </a>
        </li><!-- End Dashboard Nav -->

        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('um.users.create') ? '' : 'collapsed' }}" href="{{ route('um.users.create') }}">
                <i class="bi bi-person-badge-fill"></i>
                <span>Add User/Admin</span>
            </a>
        </li>
        
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('um.admins*') ? '' : 'collapsed' }}" href="{{ route('um.admins.index') }}">
                <i class="bi bi-person-badge-fill"></i>
                <span>Admins</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('um.users.index') ? '' : 'collapsed' }}" href="{{ route('um.users.index') }}">
                <i class="bi bi-people-fill"></i>
                <span>Users</span>
            </a>
        </li>
    </ul>
</aside>