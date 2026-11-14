<header id="header" class="header fixed-top d-flex align-items-center justify-content-between px-3 px-md-4" style="background: #334e68 !important; height: 60px; z-index: 997;">
    <!-- Left: Logo & Sidebar Toggle Button -->
    <div class="d-flex align-items-center gap-3">
        <a href="{{ auth()->user()->role == 1 ? route('superadmin.dashboard') : route('user.dashboard') }}" class="logo d-flex align-items-center text-decoration-none">
            <i class="bi bi-house-heart-fill fs-3 text-white me-2"></i>
            <span class="fs-4 fw-bold text-white tracking-wide">HomeZen</span>
        </a>

        <!-- Back Arrow / Toggle Sidebar Button -->
        <button type="button" class="btn btn-link text-white p-0 border-0 toggle-sidebar-btn shadow-none d-flex align-items-center justify-content-center" style="font-size: 20px; width: 36px; height: 36px; border-radius: 8px; transition: background 0.2s;" title="Toggle Sidebar" onmouseover="this.style.background='rgba(255,255,255,0.1)'" onmouseout="this.style.background='transparent'">
            <i class="bi bi-list"></i>
        </button>
    </div>

    <!-- Center: Page / Portal Title -->
    <div class="header-page-title d-none d-lg-block text-white-50 small fw-semibold text-uppercase" style="letter-spacing: 0.5px;">
        User Management
    </div>

    <!-- Right: Navigation (Language Switcher & Profile Dropdown) -->
    <nav class="header-nav ms-auto">
        <ul class="d-flex align-items-center mb-0 gap-2">
            <!-- Language Switcher Dropdown -->
            <li class="nav-item dropdown">
                <a class="nav-link nav-icon d-flex align-items-center justify-content-center text-white rounded-circle" href="#" data-bs-toggle="dropdown" title="Change Language" style="width: 38px; height: 38px; transition: background 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.1)'" onmouseout="this.style.background='transparent'">
                    <i class="bi bi-translate fs-5"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 mt-2" style="background-color: #ffffff !important; border-radius: 12px; min-width: 160px; padding: 6px; z-index: 999999 !important;">
                    <li class="py-1">
                        <a href="{{ route('language.change', 'en') }}" class="d-flex align-items-center px-3 py-2 text-decoration-none rounded text-dark" style="transition: background 0.2s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                            <span class="badge bg-light text-dark border me-2" style="width: 26px; height: 26px; display: inline-flex; align-items: center; justify-content: center; border-radius: 6px; font-size: 11px;">EN</span>
                            <span class="small fw-semibold">English</span>
                        </a>
                    </li>
                    <li>
                        <hr class="dropdown-divider my-1 border-light">
                    </li>
                    <li class="py-1">
                        <a href="{{ route('language.change', 'hi') }}" class="d-flex align-items-center px-3 py-2 text-decoration-none rounded text-dark" style="transition: background 0.2s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                            <span class="badge bg-light text-dark border me-2" style="width: 26px; height: 26px; display: inline-flex; align-items: center; justify-content: center; border-radius: 6px; font-size: 11px;">HI</span>
                            <span class="small fw-semibold">हिन्दी</span>
                        </a>
                    </li>
                    <li>
                        <hr class="dropdown-divider my-1 border-light">
                    </li>
                    <li class="py-1">
                        <a href="{{ route('language.change', 'gu') }}" class="d-flex align-items-center px-3 py-2 text-decoration-none rounded text-dark" style="transition: background 0.2s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                            <span class="badge bg-light text-dark border me-2" style="width: 26px; height: 26px; display: inline-flex; align-items: center; justify-content: center; border-radius: 6px; font-size: 11px;">GU</span>
                            <span class="small fw-semibold">ગુજરાતી</span>
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Profile Nav Item -->
            <li class="nav-item dropdown ps-1">
                <a class="nav-link nav-profile d-flex align-items-center pe-0 gap-2 text-white" href="#" data-bs-toggle="dropdown" style="padding: 4px 8px; border-radius: 30px; transition: background 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.1)'" onmouseout="this.style.background='transparent'">
                    <span id="headerAvatarWrap">
                        @php
                        $user = auth()->user();
                        $firstName = $user->first_name ?? $user->name ?? 'User';
                        $lastName = $user->last_name ?? '';
                        @endphp
                        @if(!empty($user->profile_image) && file_exists(public_path($user->profile_image)))
                        <img src="{{ asset($user->profile_image) }}" alt="Profile" class="rounded-circle header-profile-img shadow-2xs" style="width:36px; height:36px; object-fit:cover; border: 2px solid #cbd5e1;">
                        @else
                        <div class="profile-initial rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center fw-bold shadow-2xs" style="width:36px; height:36px; font-size:13px; border: 2px solid #cbd5e1;">
                            {{ strtoupper(substr($firstName, 0, 1)) }}{{ !empty($lastName) ? strtoupper(substr($lastName, 0, 1)) : '' }}
                        </div>
                        @endif
                    </span>
                    <span class="d-none d-md-block dropdown-toggle ps-1 header-user-name fw-semibold text-white" style="font-size: 13.5px;">{{ $firstName }} {{ $lastName }}</span>
                </a>

                <!-- Explicit Solid Background Dropdown Menu -->
                <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 mt-2" style="background-color: #ffffff !important; border-radius: 14px; min-width: 210px; padding: 8px; z-index: 999999 !important;">
                    <li class="dropdown-header text-start px-3 py-2 bg-light rounded-3 mb-1">
                        <h6 class="mb-0 text-dark fw-bold" style="font-size: 14px;">{{ $firstName }} {{ $lastName }}</h6>
                        <span class="text-muted" style="font-size: 12px; word-break: break-all;">{{ $user->email }}</span>
                    </li>
                    <li>
                        <hr class="dropdown-divider my-1 border-light">
                    </li>
                    <li class="py-1">
                        <a class="dropdown-item d-flex align-items-center px-3 py-2 rounded text-dark fw-medium" href="{{ route('superadmin.profile') }}" style="font-size: 13.5px; transition: background 0.2s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                            <i class="bi bi-person me-2 text-primary fs-5"></i>
                            <span>My Profile</span>
                        </a>
                    </li>
                    <li>
                        <hr class="dropdown-divider my-1 border-light">
                    </li>
                    <li class="py-1">
                        <a class="dropdown-item d-flex align-items-center px-3 py-2 rounded text-danger fw-medium" href="{{ route('logout') }}" style="font-size: 13.5px; transition: background 0.2s;" onmouseover="this.style.background='#fef2f2'" onmouseout="this.style.background='transparent'">
                            <i class="bi bi-box-arrow-right me-2 fs-5"></i>
                            <span>Sign Out</span>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </nav>
</header>