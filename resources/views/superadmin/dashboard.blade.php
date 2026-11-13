@extends('superadmin.layouts.app')

@section('styles')
<link rel="stylesheet" href="{{ asset('assets/css/superadmin/dashboard.css') }}">
@endsection

@section('content')
<div class="pagetitle d-flex justify-content-between align-items-center mb-3">
       <div class="d-flex align-items-baseline flex-wrap gap-2">
      <h1 class="mb-0">{{ __("messages.dashboard") ?? 'SuperAdmin Dashboard' }}</h1>
      <span class="text-muted small">(Global ecosystem overview, organization analytics &amp; system modules)</span>
   </div>
   <div class="d-flex gap-2">
      <a href="{{ route('superadmin.companies.create') }}" class="btn btn-primary shadow-sm" style="border-radius:8px; font-weight:600; padding:7px 16px; font-size:13px;">
         <i class="bi bi-building-add me-1"></i> Add Company
      </a>
   </div>
</div>

<!-- WELCOME BANNER -->
<div class="sa-banner mb-3">
   <div style="position:absolute; right:-30px; top:-40px; width:180px; height:180px; border-radius:50%; background:rgba(255,255,255,0.08);"></div>
   <div style="position:absolute; right:80px; top:-60px; width:220px; height:220px; border-radius:50%; background:rgba(255,255,255,0.06);"></div>

   <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
      <div>
         <h2 class="sa-banner-title mb-0">{{ $greeting }}, {{ $userName }} 👋</h2>
         <p class="sa-banner-sub mb-0">System performance is healthy. All multi-tenant services are active.</p>
      </div>
      <div class="d-flex gap-2">
         <div class="sa-stat-pill">
            <div class="num">{{ $activeCompanies }}</div>
            <div class="lbl">Active Clients</div>
         </div>
         <div class="sa-stat-pill">
            <div class="num">{{ $totalActiveModules }}</div>
            <div class="lbl">Active Modules</div>
         </div>
      </div>
   </div>
</div>

<!-- TOP METRIC CARDS -->
<div class="sa-metrics-grid">
   <div class="sa-metric-card">
      <div class="sa-metric-icon" style="background:#e0f2fe; color:#0284c7;">
         <i class="bi bi-buildings"></i>
      </div>
      <div>
         <div class="sa-metric-val">{{ $totalCompanies }}</div>
         <div class="sa-metric-lbl">Total Companies</div>
      </div>
   </div>

   <div class="sa-metric-card">
      <div class="sa-metric-icon" style="background:#dcfce7; color:#16a34a;">
         <i class="bi bi-person-workspace"></i>
      </div>
      <div>
         <div class="sa-metric-val">{{ $totalAdmins }}</div>
         <div class="sa-metric-lbl">Company Admins</div>
      </div>
   </div>

   <div class="sa-metric-card">
      <div class="sa-metric-icon" style="background:#fef3c7; color:#d97706;">
         <i class="bi bi-people"></i>
      </div>
      <div>
         <div class="sa-metric-val">{{ $totalEmployees }}</div>
         <div class="sa-metric-lbl">Active Employees</div>
      </div>
   </div>

   <div class="sa-metric-card">
      <div class="sa-metric-icon" style="background:#E7EDFC; color:#4566C6;">
         <i class="bi bi-grid-fill"></i>
      </div>
      <div>
         <div class="sa-metric-val">{{ $totalActiveModules }} / {{ $totalModules ?? 12 }}</div>
         <div class="sa-metric-lbl">Live Modules</div>
      </div>
   </div>
</div>

<!-- SECTION BAR -->
<div class="d-flex justify-content-between align-items-center mb-2">
   <span class="fw-bold text-dark text-uppercase small" style="letter-spacing: 0.5px;">
      <i class="bi bi-grid-3x3-gap-fill text-primary me-1"></i> System Modules Workspace
   </span>
   <a href="{{ route('superadmin.modules') }}" class="small fw-semibold text-primary text-decoration-none">Manage Permissions ›</a>
</div>

<!-- NMS THEMED 2-COLUMN APP LAUNCHER WORKSPACE -->
@php
   $groupMeta = [
      'HR & People Operations' => [
         'icon' => 'bi bi-people-fill',
      ],
      'Time, Attendance & Payroll' => [
         'icon' => 'bi bi-clock-history',
      ],
      'Workplace & Productivity' => [
         'icon' => 'bi bi-kanban',
      ],
      'Office Admin, Assets & Security' => [
         'icon' => 'bi bi-shield-check',
      ],
   ];
@endphp

<div class="row g-3 mb-3">
   @foreach ($groupedModules as $groupName => $modules)
   @php
      $collapseId = 'collapse_' . Str::slug($groupName);
      $meta = $groupMeta[$groupName] ?? ['icon' => 'bi bi-folder-fill'];
   @endphp
   <div class="col-lg-6">
      <div class="erp-group-box erp-gtone-{{ $loop->index % 3 }}">
         <!-- NMS Dark Purple Header -->
         <div class="erp-group-header" 
              data-bs-toggle="collapse" 
              data-bs-target="#{{ $collapseId }}" 
              aria-expanded="true"
              aria-controls="{{ $collapseId }}">
            <div class="erp-header-left">
               <i class="{{ $meta['icon'] }}"></i>
               <span>{{ $groupName }}</span>
               <span class="erp-header-badge">{{ $modules->count() }}</span>
            </div>
            <i class="bi bi-chevron-up erp-header-chevron"></i>
         </div>

         <!-- App Launcher Grid Body -->
         <div id="{{ $collapseId }}" class="collapse show">
            <div class="erp-group-body">
                  <div class="erp-launcher-grid erp-squares">                             
                  @foreach ($modules as $module)
                  <!-- Uniform Fixed Size Tile with Tooltip -->
                  <a href="{{ url($module->dashboard_url) }}" 
                      class="erp-module-tile erp-tone-{{ $loop->index % 4 }}"
                     data-bs-toggle="tooltip" 
                     data-bs-placement="top" 
                     data-bs-title="{{ $module->description ?: 'Access and manage ' . $module->name }}">
                     
                    <div class="erp-icon-wrapper">
   @include('partials.module-icon', ['module' => $module])
</div>

                                       <div class="erp-tile-text">
                        <span class="erp-tile-label">{{ $module->name }}</span>
                        <span class="erp-tile-code font-monospace">{{ $module->short_name }}</span>
                     </div>
                     <i class="bi bi-arrow-right erp-tile-arrow"></i>                           
                  </a>
                  @endforeach
               </div>
            </div>
         </div>
      </div>
   </div>
   @endforeach
</div>

<!-- RECENT CLIENT ONBOARDING & ACTIVITY -->
<div class="row g-3">
   <div class="col-lg-12">
      <div class="card-soft p-3">
         <div class="d-flex justify-content-between align-items-center mb-2">
            <h6 class="fw-bold text-dark mb-0 fs-6"><i class="bi bi-building-check text-primary me-2"></i>Recently Onboarded Companies</h6>
            <a href="{{ route('superadmin.companies.index') }}" class="small fw-semibold text-primary text-decoration-none">View All Clients ›</a>
         </div>

         @if($recentCompanies->count() > 0)
         <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="font-size:13px;">
               <thead class="table-light">
                  <tr>
                     <th>Company</th>
                     <th>Primary Admin</th>
                     <th>Contact Email</th>
                     <th class="text-center">Status</th>
                     <th class="text-center">Action</th>
                  </tr>
               </thead>
               <tbody>
                  @foreach($recentCompanies as $comp)
                  @php $adm = $comp->users->first(); @endphp
                  <tr>
                     <td>
                        <div class="fw-bold text-dark">{{ $comp->company_name }}</div>
                        <small class="text-muted"><i class="bi bi-geo-alt me-1"></i>{{ Str::limit($comp->company_address, 40) }}</small>
                     </td>
                     <td>{{ $adm ? $adm->first_name . ' ' . $adm->last_name : 'No Admin' }}</td>
                     <td>{{ $adm ? $adm->email : '-' }}</td>
                     <td class="text-center">
                        @if($comp->status == 1)
                        <span class="badge bg-success" style="font-size: 11px;">Active</span>
                        @else
                        <span class="badge bg-danger" style="font-size: 11px;">Inactive</span>
                        @endif
                     </td>
                     <td class="text-center">
                        <a href="{{ route('superadmin.companies.edit', $comp->id) }}" class="btn btn-sm btn-outline-primary" title="Edit Company">
                           <i class="bi bi-pencil"></i>
                        </a>
                     </td>
                  </tr>
                  @endforeach
               </tbody>
            </table>
         </div>
         @else
         <div class="text-center py-3 text-muted small">No companies onboarded yet.</div>
         @endif
      </div>
   </div>
</div>
@endsection

@section('scripts')
<script>
   document.addEventListener('DOMContentLoaded', function () {
      // Initialize Bootstrap Tooltips for full module card tiles
      const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
      tooltipTriggerList.map(function (tooltipTriggerEl) {
         return new bootstrap.Tooltip(tooltipTriggerEl, {
            delay: { show: 150, hide: 50 },
            boundary: document.body
         });
      });
   });
</script>
@endsection