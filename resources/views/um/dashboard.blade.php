@extends('um.layouts.app')

@section('styles')
<style>
   /* App Launcher Grid & Tile Custom Styling */
   .sa-metrics-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
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
      transition: transform 0.2s ease;
   }

   .sa-metric-card:hover {
      transform: translateY(-2px);
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
      font-size: 24px;
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
      background: #334e68 !important; /* Dark Slate Theme Color */
      color: #ffffff !important;
      padding: 14px 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      cursor: pointer;
      user-select: none;
      transition: background 0.2s ease;
   }

   .erp-group-header:hover {
      background: #243b53 !important;
   }

   .erp-header-left {
      display: flex;
      align-items: center;
      gap: 10px;
      font-weight: 700;
      color: #ffffff !important;
      font-size: 15px;
   }

   .erp-header-left i {
      font-size: 18px;
      color: #ffffff !important;
   }

   .erp-header-badge {
      background: rgba(255, 255, 255, 0.2);
      color: #ffffff !important;
      font-size: 11px;
      padding: 2px 8px;
      border-radius: 20px;
      font-weight: 600;
   }

   .erp-header-chevron {
      color: #ffffff !important;
      transition: transform 0.3s ease;
   }

   .erp-group-header.collapsed .erp-header-chevron {
      transform: rotate(180deg);
   }

   .erp-group-body {
      padding: 16px;
      background: #fff;
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
   <div>
      <h4 class="fw-bold text-dark mb-1">{{ $greeting }}, {{ $userName }}!</h4>
      <p class="text-muted small mb-0">User Management & Access Control Analytics Dashboard.</p>
   </div>
</div>

<!-- TOP METRIC CARDS (Sirf Admins & Users) -->
<div class="sa-metrics-grid mb-4">
   <div class="sa-metric-card">
      <div class="sa-metric-icon" style="background:#dcfce7; color:#16a34a;">
         <i class="bi bi-person-badge-fill"></i>
      </div>
      <div>
         <div class="sa-metric-val">{{ $totalAdmins }}</div>
         <div class="sa-metric-lbl">Total House Admins</div>
      </div>
   </div>

   <div class="sa-metric-card">
      <div class="sa-metric-icon" style="background:#e0f2fe; color:#0284c7;">
         <i class="bi bi-people-fill"></i>
      </div>
      <div>
         <div class="sa-metric-val">{{ $totalUsers }}</div>
         <div class="sa-metric-lbl">Total Family Members / Users</div>
      </div>
   </div>

   <div class="sa-metric-card">
      <div class="sa-metric-icon" style="background:#fef3c7; color:#d97706;">
         <i class="bi bi-person-check-fill"></i>
      </div>
      <div>
         <div class="sa-metric-val">{{ $activeUsersCount }}</div>
         <div class="sa-metric-lbl">Active Accounts</div>
      </div>
   </div>
</div>

<!-- ANALYTICS CHARTS SECTION -->
<div class="row g-4 mb-4">
   <!-- Chart 1: Role Distribution Donut Chart -->
   <div class="col-lg-6">
      <div class="card-soft p-4 h-100">
         <h5 class="card-title fs-6 fw-bold text-dark mb-3"><i class="bi bi-pie-chart-fill text-primary me-2"></i>User Role Breakdown</h5>
         <div style="position: relative; height: 260px; display: flex; justify-content: center; align-items: center;">
            <canvas id="roleDistributionChart"></canvas>
         </div>
      </div>
   </div>

   <!-- Chart 2: Account Status Bar Chart -->
   <div class="col-lg-6">
      <div class="card-soft p-4 h-100">
         <h5 class="card-title fs-6 fw-bold text-dark mb-3"><i class="bi bi-bar-chart-fill text-primary me-2"></i>Account Status Overview</h5>
         <div style="position: relative; height: 260px; display: flex; justify-content: center; align-items: center;">
            <canvas id="statusOverviewChart"></canvas>
         </div>
      </div>
   </div>
</div>
@endsection

@section('scripts')
<!-- Chart.js CDN (if not already included in layout) -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
   document.addEventListener('DOMContentLoaded', function() {
      // Tooltips init
      const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
      tooltipTriggerList.map(function(tooltipTriggerEl) {
         return new bootstrap.Tooltip(tooltipTriggerEl, {
            delay: { show: 150, hide: 50 },
            boundary: document.body
         });
      });

      // 1. Role Distribution Donut Chart
      const ctxRole = document.getElementById('roleDistributionChart').getContext('2d');
      new Chart(ctxRole, {
         type: 'doughnut',
         data: {
            labels: ['House Admins', 'Family Members / Users'],
            datasets: [{
               data: [{{ $totalAdmins }}, {{ $totalUsers }}],
               backgroundColor: ['#16a34a', '#0284c7'],
               borderWidth: 2,
               borderColor: '#ffffff'
            }]
         },
         options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
               legend: {
                  position: 'bottom',
                  labels: { font: { size: 12, family: 'inherit' }, boxWidth: 15 }
               }
            }
         }
      });

      // 2. Account Status Bar Chart
      const ctxStatus = document.getElementById('statusOverviewChart').getContext('2d');
      new Chart(ctxStatus, {
         type: 'bar',
         data: {
            labels: ['Active Accounts', 'Inactive Accounts'],
            datasets: [{
               label: 'Users Count',
               data: [{{ $activeUsersCount }}, {{ $inactiveUsersCount }}],
               backgroundColor: ['#334e68', '#94a3b8'],
               borderRadius: 6,
            }]
         },
         options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
               legend: { display: false }
            },
            scales: {
               y: {
                  beginAtZero: true,
                  ticks: { precision: 0 }
               },
               x: {
                  grid: { display: false }
               }
            }
         }
      });
   });
</script>
@endsection