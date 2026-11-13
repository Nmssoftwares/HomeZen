@extends('superadmin.layouts.app')

@section('content')
<div class="pagetitle d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
   <div>
      <h1 class="mb-0">System Modules</h1>
      <div class="text-muted small mt-1">Configure accessible global modules, functional suites, and system status.</div>
   </div>
</div>

<section class="section">
   <div class="row">
      <div class="col-lg-12">
         <div class="card-soft">
            <div class="p-3">
               <!-- Filter Bar -->
               <div class="row g-2 mb-3 align-items-center">
                  <div class="col-md-4 col-lg-3">
                     <label class="form-label small fw-semibold text-muted mb-1">Filter by Functional Suite / Group:</label>
                     <select class="form-select form-select-sm" id="groupFilter">
                        <option value="">All Groups</option>
                        @if(isset($groups) && count($groups) > 0)
                        @foreach($groups as $grp)
                        <option value="{{ $grp }}">{{ $grp }}</option>
                        @endforeach
                        @endif
                     </select>
                  </div>
               </div>

               <table class="table table-bordered align-middle" id="modulesTable" width="100%">
                  <thead>
                     <tr>
                        <th>Module Details</th>
                        <th class="text-center">Code</th>
                        <th>Functional Suite / Group</th>
                        <th>Dashboard Entry URL</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Active Toggle</th>
                     </tr>
                  </thead>
               </table>
            </div>
         </div>
      </div>
   </div>
</section>

<!-- Floating Export Buttons -->
<div class="floating-export-wrapper" style="position: fixed; bottom: 20px; right: 20px; z-index: 9999;">
   <a href="javascript:void(0);" id="exportExcel" title="Export Excel" class="floating-export-option excel-btn"><i class="bi bi-file-earmark-excel"></i></a>
   <a href="javascript:void(0);" id="exportPdf" title="Export PDF" class="floating-export-option pdf-btn"><i class="bi bi-file-earmark-pdf"></i></a>
   <a href="javascript:void(0);" id="exportMainBtn" title="Export" class="floating-excel-btn"><i class="bi bi-download" id="exportMainIcon"></i></a>
</div>
@endsection

@section('scripts')
<script>
   let dataTable;

   $(document).ready(function() {
      dataTable = $('#modulesTable').DataTable({
         processing: true,
         serverSide: true,
         order: [],
         ajax: {
            url: "{{ route('superadmin.modules.datatable') }}",
            data: function(d) {
               d.group_name = $('#groupFilter').val();
            }
         },
         dom: 'Bfrtip',
         buttons: [{
               extend: 'excel',
               text: 'Excel',
               className: 'btn btn-success btn-sm',
               exportOptions: {
                  columns: [0, 1, 2, 3, 4],
                  // Excel me HTML tags hatane ke liye
                  format: {
                     body: function(data, row, column, node) {
                        return data ? data.replace(/<[^>]*>?/gm, ' ').replace(/\s+/g, ' ').trim() : '';
                     }
                  }
               }
            }
         ],
         columnDefs: [{
            targets: [1, 4, 5],
            className: 'text-center'
         }],
         columns: [{
               data: 'module_card',
               name: 'name'
            },
            {
               data: 'short_name_badge',
               name: 'short_name'
            },
            {
               data: 'group_badge',
               name: 'group_name'
            },
            {
               data: 'dashboard_url_link',
               name: 'dashboard_url'
            },
            {
               data: 'status_badge',
               name: 'status'
            },
            {
               data: 'action',
               name: 'action',
               orderable: false,
               searchable: false
            }
         ]
      });

      // Reload Table when Group Filter changes
      $('#groupFilter').on('change', function() {
         dataTable.ajax.reload();
      });

      // Floating Export
      $('#exportMainBtn').on('click', function(e) {
         e.preventDefault();
         $('.floating-export-wrapper').toggleClass('active');
      });

      $('#exportExcel').on('click', function() {
         dataTable.button('.buttons-excel').trigger();
         $('.floating-export-wrapper').removeClass('active');
      });

      // Custom Backend PDF Generate logic
      $('#exportPdf').on('click', function() {
         let groupName = $('#groupFilter').val();
         let url = "{{ route('superadmin.modules.export.pdf') }}";

         // Agar dropdown se koi filter select kiya hai, toh usko parameter me bhejenge
         if (groupName) {
            url += "?group_name=" + encodeURIComponent(groupName);
         }

         window.location.href = url;
         $('.floating-export-wrapper').removeClass('active');
      });

      // Real-time Status Switch Toggle
      $(document).on('change', '.status-toggle', function() {
         const toggle = $(this);
         const id = toggle.data('id');
         const status = toggle.is(':checked') ? 1 : 0;
         $('#nms-loader-overlay').addClass('active');

         $.ajax({
            url: "{{ route('superadmin.modules.update-status') }}",
            type: 'POST',
            data: {
               id: id,
               status: status,
               _token: '{{ csrf_token() }}'
            },
            success: function(response) {
               if (typeof showAlert === 'function') {
                  showAlert('success', response.message);
               }
               dataTable.ajax.reload(null, false);
               $('#nms-loader-overlay').removeClass('active');
            },
            error: function(xhr) {
               toggle.prop('checked', !status);
               $('#nms-loader-overlay').removeClass('active');
               alert(xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Error updating module status.');
            }
         });
      });
   });
</script>
@endsection