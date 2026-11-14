@extends('um.layouts.app')

@section('content')
<section class="section">
   <div class="row">
      <div class="col-lg-12">
         <div class="card-soft p-4 position-relative">
            <div class="table-responsive">
               <table class="table table-hover align-middle w-100" id="usersTable">
                  <thead>
                     <tr>
                        <th>Member Details</th>
                        <th>Household / Company</th>
                        <th>Contact No</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Action Toggle</th>
                     </tr>
                  </thead>
               </table>
            </div>
         </div>
      </div>
   </div>
</section>
<a href="{{ route('users.create') }}" title="Add New Account" style="position: fixed; bottom: 25px; right: 25px; width: 55px; height: 55px; background-color: #334e68; color: #fff; border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 15px rgba(51, 78, 104, 0.4); z-index: 9999; font-size: 24px; transition: transform 0.2s;">
   <i class="bi bi-plus-lg"></i>
</a>
@endsection

@section('scripts')
<script>
   let dataTable;

   $(document).ready(function() {
      dataTable = $('#usersTable').DataTable({
         processing: true,
         serverSide: true,
         order: [],
         ajax: {
            url: "{{ route('um.users.datatable') }}",
            data: function(d) {
               d.role = "{{ $role ?? 3 }}";
            }
         },
         dom: 'Bfrtip',
         buttons: [{
            extend: 'excel',
            text: 'Excel',
            className: 'buttons-excel dt-button',
            exportOptions: {
               columns: [0, 1, 2, 3],
               format: {
                  body: function(data, row, column, node) {
                     return data ? data.replace(/<[^>]*>?/gm, ' ').replace(/\s+/g, ' ').trim() : '';
                  }
               }
            }
         }],
         columnDefs: [{
            targets: [3, 4],
            className: 'text-center'
         }],
         columns: [{
               data: 'user_details',
               name: 'first_name'
            },
            {
               data: 'company_name',
               name: 'company.company_name'
            },
            {
               data: 'contact_info',
               name: 'contact_no'
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

      // Status Toggle AJAX
      $(document).on('change', '.status-toggle', function() {
         const toggle = $(this);
         const id = toggle.data('id');
         const status = toggle.is(':checked') ? 1 : 0;
         $('#nms-loader-overlay').addClass('active');

         $.ajax({
            url: "{{ route('um.users.update-status') }}",
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
               alert('Error updating status.');
            }
         });
      });
   });
</script>
@endsection