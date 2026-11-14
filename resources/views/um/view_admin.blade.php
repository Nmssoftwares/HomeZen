@extends('um.layouts.app')

@section('content')
<section class="section">
   <div class="row">
      <div class="col-lg-12">
         
         <div class="card-soft p-4 position-relative">

            <div class="table-responsive">
               <table class="table table-hover align-middle w-100" id="adminsTable">
                  <thead>
                     <tr>
                        <th>Admin Details</th>
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
@endsection

@section('scripts')
<script>
   let dataTable;

   $(document).ready(function() {
      dataTable = $('#adminsTable').DataTable({
         processing: true,
         serverSide: true,
         order: [],
         ajax: {
            url: "{{ route('um.admins.datatable') }}"
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
         }, {
            extend: 'pdf',
            text: 'PDF',
            className: 'buttons-pdf dt-button',
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
         columns: [
            { data: 'admin_details', name: 'first_name' },
            { data: 'company_name', name: 'company.company_name' },
            { data: 'contact_info', name: 'contact_no' },
            { data: 'status_badge', name: 'status' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
         ]
      });

      // Status Toggle AJAX
      $(document).on('change', '.status-toggle', function() {
         const toggle = $(this);
         const id = toggle.data('id');
         const status = toggle.is(':checked') ? 1 : 0;
         $('#nms-loader-overlay').addClass('active');

         $.ajax({
            url: "{{ route('um.admins.update-status') }}",
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