@extends('superadmin.layouts.app')

@section('styles')
<style>
   /* App Launcher Grid & Tile Custom Styling (Inspired by Reference UI) */
   .sa-banner {
      background: linear-gradient(135deg, #2c6f9b, #1e5578);
      color: #fff;
      border-radius: 16px;
      padding: 24px 30px;
      box-shadow: 0 10px 30px rgba(44, 111, 155, 0.2);
   }

   .sa-banner-title {
      font-size: 24px;
      font-weight: 700;
   }

   .sa-banner-sub {
      font-size: 14px;
      opacity: 0.85;
   }

   .sa-stat-pill {
      background: rgba(255, 255, 255, 0.15);
      padding: 10px 18px;
      border-radius: 12px;
      text-align: center;
      backdrop-filter: blur(5px);
   }

   .sa-stat-pill .num {
      font-size: 20px;
      font-weight: 700;
      line-height: 1;
   }

   .sa-stat-pill .lbl {
      font-size: 11px;
      text-transform: uppercase;
      opacity: 0.8;
      margin-top: 4px;
   }

   .sa-metrics-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      gap: 20px;
   }

   .sa-metric-card {
      background: #fff;
      padding: 20px;
      border-radius: 14px;
      box-shadow: 0 4px 20px rgba(1, 41, 112, 0.05);
      display: flex;
      align-items: center;
      gap: 16px;
      border: 1px solid #eef2f7;
   }

   .sa-metric-icon {
      width: 50px;
      height: 50px;
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 24px;
   }

   .sa-metric-val {
      font-size: 22px;
      font-weight: 700;
      color: #012970;
      line-height: 1.2;
   }

   .sa-metric-lbl {
      font-size: 13px;
      color: #6c757d;
   }

   /* ERP Group Box & Launcher Grid */
   .erp-group-box {
      background: #fff;
      border-radius: 14px;
      box-shadow: 0 4px 20px rgba(1, 41, 112, 0.05);
      border: 1px solid #eef2f7;
      overflow: hidden;
      margin-bottom: 20px;
   }

   .erp-group-header {
      background: #f8fafc;
      padding: 14px 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      cursor: pointer;
      border-bottom: 1px solid #eef2f7;
      user-select: none;
   }

   .erp-header-left {
      display: flex;
      align-items: center;
      gap: 10px;
      font-weight: 700;
      color: #012970;
      font-size: 15px;
   }

   .erp-header-left i {
      font-size: 18px;
      color: #2c6f9b;
   }

   .erp-header-badge {
      background: #e0f2fe;
      color: #0284c7;
      font-size: 11px;
      padding: 2px 8px;
      border-radius: 20px;
      font-weight: 600;
   }

   .erp-group-body {
      padding: 16px;
   }

   .erp-launcher-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
      gap: 14px;
   }

   .erp-module-tile {
      background: #fdfdfe;
      border: 1px solid #e2e8f0;
      border-radius: 12px;
      padding: 16px;
      text-align: center;
      text-decoration: none;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      transition: all 0.2s ease;
      position: relative;
      min-height: 110px;
   }

   .erp-module-tile:hover {
      transform: translateY(-3px);
      box-shadow: 0 8px 20px rgba(44, 111, 155, 0.12);
      border-color: #2c6f9b;
      background: #fff;
   }

   .erp-icon-wrapper {
      font-size: 32px;
      color: #2c6f9b;
      margin-bottom: 8px;
   }

   .erp-tile-label {
      font-size: 13px;
      font-weight: 600;
      color: #1e293b;
      display: block;
      line-height: 1.3;
   }

   .erp-tile-code {
      font-size: 10px;
      color: #94a3b8;
      margin-top: 2px;
   }

   .card-soft {
      background: #fff;
      border-radius: 14px;
      box-shadow: 0 4px 20px rgba(1, 41, 112, 0.05);
      border: 1px solid #eef2f7;
   }
</style>
@endsection

@section('content')
<div class="pagetitle d-flex justify-content-between align-items-center mb-3">
   <div class="d-flex gap-2">
      <a href="{{ route('superadmin.companies.create') }}" class="btn btn-primary shadow-sm" style="border-radius:8px; font-weight:600; padding:7px 16px; font-size:13px;">
         <i class="bi bi-building-add me-1"></i> Add Household
      </a>
   </div>
</div>
<!-- TOP METRIC CARDS -->
<div class="sa-metrics-grid mb-4">
   <div class="sa-metric-card">
      <div class="sa-metric-icon" style="background:#e0f2fe; color:#0284c7;">
         <i class="bi bi-houses"></i>
      </div>
      <div>
         <div class="sa-metric-val">{{ $totalCompanies }}</div>
         <div class="sa-metric-lbl">Total Households</div>
      </div>
   </div>

   <div class="sa-metric-card">
      <div class="sa-metric-icon" style="background:#dcfce7; color:#16a34a;">
         <i class="bi bi-person-badge"></i>
      </div>
      <div>
         <div class="sa-metric-val">{{ $totalAdmins }}</div>
         <div class="sa-metric-lbl">House Admins</div>
      </div>
   </div>

   <div class="sa-metric-card">
      <div class="sa-metric-icon" style="background:#fef3c7; color:#d97706;">
         <i class="bi bi-people"></i>
      </div>
      <div>
         <div class="sa-metric-val">{{ $totalUsers ?? 0 }}</div>
         <div class="sa-metric-lbl">Family Members</div>
      </div>
   </div>

   <div class="sa-metric-card">
      <div class="sa-metric-icon" style="background:#E7EDFC; color:#4566C6;">
         <i class="bi bi-grid-fill"></i>
      </div>
      <div>
         <div class="sa-metric-val">{{ $totalActiveModules }} / {{ $totalModules }}</div>
         <div class="sa-metric-lbl">Live Modules</div>
      </div>
   </div>
</div>

<!-- SECTION BAR -->
<div class="d-flex justify-content-between align-items-center mb-3">
   <span class="fw-bold text-dark text-uppercase small" style="letter-spacing: 0.5px;">
      <i class="bi bi-grid-3x3-gap-fill text-primary me-1"></i> System Modules Workspace
   </span>
   <a href="{{ route('superadmin.modules') }}" class="small fw-semibold text-primary text-decoration-none">Manage Modules ›</a>
</div>

<!-- APP LAUNCHER WORKSPACE (Grid Box Layout) -->
@php
$groupMeta = [
'System & Access Control' => ['icon' => 'bi bi-shield-lock-fill'],
'Daily Management' => ['icon' => 'bi bi-wallet2'],
'General Operations' => ['icon' => 'bi bi-folder-fill'],
];
@endphp

<div class="row g-4 mb-4">
   @foreach ($groupedModules as $groupName => $modules)
   @php
   $collapseId = 'collapse_' . Str::slug($groupName);
   $meta = $groupMeta[$groupName] ?? ['icon' => 'bi bi-folder-fill'];
   @endphp
   <div class="col-lg-6">
      <div class="erp-group-box">
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
            <i class="bi bi-chevron-up erp-header-chevron text-muted"></i>
         </div>

         <div id="{{ $collapseId }}" class="collapse show">
            <div class="erp-group-body">
               <div class="erp-launcher-grid">
                  @foreach ($modules as $module)
                  <a href="{{ url($module->dashboard_url) }}"
                     class="erp-module-tile"
                     data-bs-toggle="tooltip"
                     data-bs-placement="top"
                     data-bs-title="{{ $module->description ?: 'Access and manage ' . $module->name }}">

                     <div class="erp-icon-wrapper">
                        <i class="{{ $module->icon ?: 'bi bi-box' }}"></i>
                     </div>
                     <div class="erp-tile-text">
                        <span class="erp-tile-label">{{ $module->name }}</span>
                        <span class="erp-tile-code font-monospace">{{ $module->short_name }}</span>
                     </div>
                  </a>
                  @endforeach
               </div>
            </div>
         </div>
      </div>
   </div>
   @endforeach
</div>
@endsection

@section('scripts')
<script>
   document.addEventListener('DOMContentLoaded', function() {
      const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
      tooltipTriggerList.map(function(tooltipTriggerEl) {
         return new bootstrap.Tooltip(tooltipTriggerEl, {
            delay: {
               show: 150,
               hide: 50
            },
            boundary: document.body
         });
      });
   });
</script>
@endsection