<header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">
        <a href="{{ auth()->user()->role == 1 ? route('superadmin.dashboard') : route('user.dashboard') }}" class="logo d-flex align-items-center text-decoration-none">
            <i class="bi bi-house-heart-fill fs-3 text-primary me-2"></i>
            <span class="fs-4 fw-bold text-dark">HomeZen</span>
        </a>
        <i class="bi bi-list toggle-sidebar-btn" title="Collapse / expand menu"></i>
    </div>

    <div class="header-page-title d-none d-md-block text-secondary fw-semibold">Super Admin Portal</div>

    <nav class="header-nav ms-auto">
        <ul class="d-flex align-items-center">
            <!-- Language Switcher Dropdown -->
            <li class="nav-item dropdown">
                <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown" title="Change Language">
                    <i class="bi bi-translate"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow shadow-sm border-0" style="border-radius: 12px; min-width: 160px;">
                    <li class="py-1">
                        <a href="{{ route('language.change', 'en') }}" class="d-flex align-items-center px-3 py-2 text-decoration-none">
                            <span class="badge bg-light text-dark border me-2" style="width: 26px; height: 26px; display: inline-flex; align-items: center; justify-content: center; border-radius: 6px;">EN</span>
                            <span class="text-dark fw-semibold">English</span>
                        </a>
                    </li>
                    <li><hr class="dropdown-divider my-1"></li>
                    <li class="py-1">
                        <a href="{{ route('language.change', 'hi') }}" class="d-flex align-items-center px-3 py-2 text-decoration-none">
                            <span class="badge bg-light text-dark border me-2" style="width: 26px; height: 26px; display: inline-flex; align-items: center; justify-content: center; border-radius: 6px;">HI</span>
                            <span class="text-dark fw-semibold">हिन्दी</span>
                        </a>
                    </li>
                    <li><hr class="dropdown-divider my-1"></li>
                    <li class="py-1">
                        <a href="{{ route('language.change', 'gu') }}" class="d-flex align-items-center px-3 py-2 text-decoration-none">
                            <span class="badge bg-light text-dark border me-2" style="width: 26px; height: 26px; display: inline-flex; align-items: center; justify-content: center; border-radius: 6px;">GU</span>
                            <span class="text-dark fw-semibold">ગુજરાતી</span>
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Profile Nav Item -->
            <li class="nav-item dropdown pe-3">
                <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
                    <span id="headerAvatarWrap">
                        @php 
                            $user = auth()->user(); 
                            $firstName = $user->first_name ?? $user->name ?? 'User';
                            $lastName = $user->last_name ?? '';
                        @endphp
                        @if(!empty($user->profile_image) && file_exists(public_path($user->profile_image)))
                        <img src="{{ asset($user->profile_image) }}" alt="Profile" class="rounded-circle header-profile-img" style="width:36px;height:36px;object-fit:cover;">
                        @else
                        <div class="profile-initial rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center fw-bold" style="width:36px;height:36px;font-size:14px;">
                            {{ strtoupper(substr($firstName, 0, 1)) }}{{ !empty($lastName) ? strtoupper(substr($lastName, 0, 1)) : '' }}
                        </div>
                        @endif
                    </span>
                    <span class="d-none d-md-block dropdown-toggle ps-2 header-user-name fw-medium">{{ $firstName }} {{ $lastName }}</span>
                </a>

                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile shadow-sm border-0" style="border-radius: 12px;">
                    <li class="dropdown-header text-start px-3 py-2">
                        <h6 class="mb-0 text-dark fw-bold">{{ $firstName }} {{ $lastName }}</h6>
                        <span class="text-muted small">{{ $user->email }}</span>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="{{ route('superadmin.profile') }}">
                            <i class="bi bi-person me-2 text-primary"></i>
                            <span>My Profile</span>
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item d-flex align-items-center text-danger" href="{{ route('logout') }}">
                            <i class="bi bi-box-arrow-right me-2"></i>
                            <span>Sign Out</span>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </nav>
</header>