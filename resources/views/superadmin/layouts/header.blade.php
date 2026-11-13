<header id="header" class="header fixed-top d-flex align-items-center">

	<div class="d-flex align-items-center justify-content-between">
		<a href="{{ auth()->user()->role == 1 ? route('superadmin.dashboard') : (auth()->user()->role == 2 ? route('admin.dashboard') : route('user.dashboard')) }}" class="logo d-flex align-items-center">
			<img src="{{ asset('assets/img/offnexa-logo-v2.png') }}" alt="NMSoffice" style="max-height:62px;">
		</a>
		<i class="bi bi-list toggle-sidebar-btn" title="Collapse / expand menu"></i>
	</div>

	<div class="header-page-title">Super Admin</div>

	<nav class="header-nav ms-auto">
		<ul class="d-flex align-items-center">
			<!-- Language Switcher Dropdown -->
			<li class="nav-item dropdown">
				<a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown" title="Change Language">
					<i class="bi bi-translate"></i>
				</a>
				<ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow messages shadow-sm border-0" style="border-radius: 12px; min-width: 160px;">
					<li class="message-item py-1">
						<a href="{{ route('language.change', 'en') }}" class="d-flex align-items-center px-3 py-2 text-decoration-none">
							<span class="badge bg-light text-dark border me-2" style="width: 26px; height: 26px; display: inline-flex; align-items: center; justify-content: center; border-radius: 6px;">EN</span>
							<span class="text-dark fw-semibold">English</span>
						</a>
					</li>
					<li>
						<hr class="dropdown-divider my-1">
					</li>
					<li class="message-item py-1">
						<a href="{{ route('language.change', 'hi') }}" class="d-flex align-items-center px-3 py-2 text-decoration-none">
							<span class="badge bg-light text-dark border me-2" style="width: 26px; height: 26px; display: inline-flex; align-items: center; justify-content: center; border-radius: 6px;">HI</span>
							<span class="text-dark fw-semibold">हिन्दी</span>
						</a>
					</li>
					<li>
						<hr class="dropdown-divider my-1">
					</li>
					<li class="message-item py-1">
						<a href="{{ route('language.change', 'gu') }}" class="d-flex align-items-center px-3 py-2 text-decoration-none">
							<span class="badge bg-light text-dark border me-2" style="width: 26px; height: 26px; display: inline-flex; align-items: center; justify-content: center; border-radius: 6px;">GU</span>
							<span class="text-dark fw-semibold">ગુજરાતી</span>
						</a>
					</li>
				</ul>
			</li><!-- End Language Switcher -->

			<!-- Profile Nav Item -->
			<li class="nav-item dropdown pe-3">
				<a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
					<!-- Dynamic Header Avatar Wrapper for Live Sync -->
					<span id="headerAvatarWrap">
						@if(!empty(auth()->user()->profile_image) && file_exists(public_path(auth()->user()->profile_image)))
						<img src="{{ asset(auth()->user()->profile_image) }}" alt="Profile" class="rounded-circle header-profile-img" style="width:36px;height:36px;object-fit:cover;">
						@else
						<div class="profile-initial rounded-circle">
							{{ strtoupper(substr(auth()->user()->first_name, 0, 1)) }}{{ strtoupper(substr(auth()->user()->last_name, 0, 1)) }}
						</div>
						@endif
					</span>
					<span class="d-none d-md-block dropdown-toggle ps-2 header-user-name">{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</span>
				</a>

				<ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile shadow-sm border-0" style="border-radius: 12px;">
					<li class="dropdown-header text-start px-3 py-2">
						<h6 class="mb-0 text-dark fw-bold">{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</h6>
						<span class="text-muted small">{{ auth()->user()->email }}</span>
					</li>
					<li>
						<hr class="dropdown-divider">
					</li>
					<li>
						<a class="dropdown-item d-flex align-items-center" href="{{ route('superadmin.profile') }}">
							<i class="bi bi-person me-2 text-primary"></i>
							<span>{{ __('messages.my_profile') ?? 'My Profile' }}</span>
						</a>
					</li>
					<li>
						<hr class="dropdown-divider">
					</li>
					<li>
						<a class="dropdown-item d-flex align-items-center text-danger" href="{{ route('logout') }}">
							<i class="bi bi-box-arrow-right me-2"></i>
							<span>{{ __('messages.sign_out') ?? 'Sign Out' }}</span>
						</a>
					</li>
				</ul><!-- End Profile Dropdown Items -->
			</li><!-- End Profile Nav -->
		</ul>
	</nav><!-- End Icons Navigation -->
</header>