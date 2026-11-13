@extends('superadmin.layouts.app')

@section('content')
<div class="pagetitle d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
   <div>
      <h1 class="mb-0 fs-4 fw-bold text-dark">System Modules</h1>
      <div class="text-muted small mt-1">Configure accessible global modules, functional suites, and system status.</div>
   </div>
</div>

<section class="section">
   <div class="row">
      <div class="col-lg-12">

         <!-- Soft Card with Outer Edge Sticky Filter Button -->
         <div class="card-soft p-4 position-relative">
            <div class="card-edge-filter-wrap dropdown">
               <button class="card-edge-filter-btn dropdown-toggle hide-arrow" type="button" id="cardFilterDropdown" data-bs-toggle="dropdown" aria-expanded="false" title="Filter Modules">
                  <i class="bi bi-funnel-fill fs-5"></i>
               </button>
               <div class="dropdown-menu dropdown-menu-end shadow-lg" aria-labelledby="cardFilterDropdown">
                  <label class="form-label small fw-semibold text-muted mb-1">Filter by Functional Suite:</label>
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

            <div class="table-responsive">
               <table class="table table-hover align-middle w-100" id="modulesTable">
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
            className: 'buttons-excel dt-button',
            exportOptions: {
               columns: [0, 1, 2, 3, 4],
               format: {
                  body: function(data, row, column, node) {
                     return data ? data.replace(/<[^>]*>?/gm, ' ').replace(/\s+/g, ' ').trim() : '';
                  }
               }
            }
         }, {
            extend: 'pdf',
            text: 'PDF',
            className: 'buttons-pdf dt-button',
            action: function(e, dt, node, config) {
               let groupName = $('#groupFilter').val();
               let url = "{{ route('superadmin.modules.export.pdf') }}";
               if (groupName) {
                  url += "?group_name=" + encodeURIComponent(groupName);
               }
               window.location.href = url;
            }
         }],
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

      $('#groupFilter').on('change', function() {
         dataTable.ajax.reload();
      });

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