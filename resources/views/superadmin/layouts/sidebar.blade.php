<aside id="sidebar" class="sidebar">
    <ul class="sidebar-nav" id="sidebar-nav">
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('superadmin.dashboard') ? '' : 'collapsed' }}" href="{{ route('superadmin.dashboard') }}">
                <i class="bi bi-speedometer2"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('superadmin.companies.*') ? '' : 'collapsed' }}" href="{{ route('superadmin.companies.index') }}">
                <i class="bi bi-buildings"></i>
                <span>Households / Clients</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('superadmin.modules*') ? '' : 'collapsed' }}" href="{{ route('superadmin.modules') }}">
                <i class="bi bi-grid-fill"></i>
                <span>Modules Master</span>
            </a>
        </li>
    </ul>
</aside>