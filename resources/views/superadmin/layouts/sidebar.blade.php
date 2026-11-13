@php
    $wbPages  = \App\Support\WebsiteCms::pages();
    $wbOpen   = request()->routeIs('superadmin.website.*');
    $wbActive = $wbOpen ? request()->route('page') : null;
@endphp

<aside id="sidebar" class="sidebar">
        @include('partials.sidebar-brand')
        
    

    <a class="nav-link-item {{ request()->routeIs('superadmin.dashboard') ? 'active' : '' }}" href="{{ route('superadmin.dashboard') }}">
        <i class="bi bi-speedometer2"></i>
        <span>{{ __("messages.dashboard") }}</span>
    </a>

    <a class="nav-link-item {{ request()->routeIs('superadmin.modules') ? 'active' : '' }}" href="{{ route('superadmin.modules') }}">
        <i class="bi bi-calendar2-check"></i>
        <span>Modules</span>
    </a>

    <a class="nav-link-item {{ request()->routeIs('superadmin.companies.*') ? 'active' : '' }}" href="{{ route('superadmin.companies.index') }}">
        <i class="bi bi-door-open-fill"></i>
        <span>Companies</span>
    </a>

    <a class="nav-link-item {{ request()->routeIs('superadmin.user.*') ? 'active' : '' }}" href="{{ route('superadmin.user.index') }}">
        <i class="bi bi-people"></i>
        <span>Users</span>
    </a>

    <!-- Website (with sub menu) -->
    <a class="nav-link-item wb-toggle {{ $wbOpen ? 'wb-open' : '' }}"
       data-bs-toggle="collapse" href="#websiteMenu" role="button"
       aria-expanded="{{ $wbOpen ? 'true' : 'false' }}" aria-controls="websiteMenu">
        <i class="bi bi-globe2"></i>
        <span>Website</span>
        <span class="setting-arrow-badge ms-auto">
            <i class="bi bi-chevron-down setting-arrow"></i>
        </span>
    </a>
    <div id="websiteMenu" class="collapse wb-sub {{ $wbOpen ? 'show' : '' }}">
        @foreach ($wbPages as $key => $p)
            <a class="nav-link-item {{ $wbActive === $key ? 'active' : '' }}" href="{{ route('superadmin.website.page', $key) }}">
                <i class="bi {{ $p['icon'] }}"></i>
                <span>{{ $p['title'] }}</span>
            </a>
        @endforeach
    </div>

        <!-- Demo Requests (from website Book a Demo) -->
    <a class="nav-link-item {{ request()->routeIs('superadmin.demo-requests.*') ? 'active' : '' }}" href="{{ route('superadmin.demo-requests.index') }}">
        <i class="bi bi-envelope-paper"></i>
        <span>Demo Requests</span>
        @php $newDemos = \App\Models\DemoRequest::where('status', 'new')->count(); @endphp
        @if ($newDemos > 0)
            <span class="badge bg-danger rounded-pill ms-auto">{{ $newDemos }}</span>
        @endif
    </a>
    <!-- Company Settings Link -->
    <a class="nav-link-item {{ request()->routeIs('superadmin.company_settings.*') ? 'active' : '' }}" href="{{ route('superadmin.company_settings.index') }}">
        <i class="bi bi-gear-wide-connected"></i>
        <span>Company Settings</span>
    </a>



    <style>
        #sidebar .wb-toggle { cursor: pointer; }
        #sidebar .wb-toggle.wb-open { background: var(--sb-hover) !important; color: var(--sb-ink-strong) !important; }
        #sidebar .wb-toggle[aria-expanded="true"] .setting-arrow { transform: rotate(180deg); }
        #sidebar .wb-sub { margin: 0 0 6px 22px; padding-left: 8px; border-left: 2px solid rgba(74, 107, 240, .2); }
        #sidebar .wb-sub .nav-link-item { height: 36px; font-size: 13px; margin-bottom: 2px !important; }
        #sidebar .wb-sub .nav-link-item i { width: 24px !important; height: 24px !important; font-size: 14px !important; }
        body.toggle-sidebar #sidebar .wb-sub { display: none !important; }
    </style>
    @include('partials.sidebar-notifications')
    @include('partials.sidebar-bottom')

</aside>