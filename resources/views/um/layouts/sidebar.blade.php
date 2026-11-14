<aside id="sidebar" class="sidebar">
    <ul class="sidebar-nav" id="sidebar-nav">

        <!-- Dashboard -->
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('um.dashboard') ? '' : 'collapsed' }}" href="{{ route('um.dashboard') }}">
                <i class="bi bi-speedometer2"></i>
                <span>Dashboard</span>
            </a>
        </li><!-- End Dashboard Nav -->

        <!-- House Admins Management -->
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('um.admins*') ? '' : 'collapsed' }}" href="{{ route('um.admins.index') }}">
                <i class="bi bi-person-badge-fill"></i>
                <span>House Admins</span>
            </a>
        </li><!-- End House Admins Nav -->

        <!-- Family Members / Users Management -->
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('um.users*') ? '' : 'collapsed' }}" href="{{ route('um.users.index') }}">
                <i class="bi bi-people-fill"></i>
                <span>Family Members</span>
            </a>
        </li><!-- End Family Members Nav -->

    </ul>
</aside>