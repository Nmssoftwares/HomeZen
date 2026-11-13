@extends('superadmin.layouts.app')

@section('content')
<section class="section">
   <div class="row">
      <div class="col-lg-12">
         <div class="card-soft p-4">
            <div class="table-responsive">
               <table class="table table-hover align-middle w-100" id="companiesTable">
                  <thead>
                     <tr>
                        <th>Household Details</th>
                        <th>Admin Name</th>
                        <th>Admin Email</th>
                        <th class="text-center">Admin Phone</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Actions</th>
                     </tr>
                  </thead>
               </table>
            </div>
         </div>
      </div>
   </div>
</section>

<!-- Password Reset Modal -->
<div class="modal fade" id="changePasswordModal" tabindex="-1" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content border-0 shadow-lg" style="border-radius: 12px;">
         <div class="modal-header border-0 pb-0">
            <h5 class="modal-title fw-bold text-dark"><i class="bi bi-key text-primary me-2"></i>Reset Admin Password</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
         </div>
         <form id="passwordModalForm">
            <div class="modal-body pt-3">
               <input type="hidden" id="userId">

               <div class="mb-3">
                  <label for="password" class="form-label fw-semibold small">New Password <span class="text-danger">*</span></label>
                  <div class="input-group">
                     <input type="password" class="form-control" id="password" name="password" placeholder="Minimum 6 characters">
                     <span class="input-group-text toggle-modal-password bg-light" data-target="#password" style="cursor: pointer;">
                        <i class="bi bi-eye"></i>
                     </span>
                  </div>
                  <small class="text-danger error" id="password_error"></small>
               </div>

               <div class="mb-3">
                  <label for="confirm_password" class="form-label fw-semibold small">Confirm Password <span class="text-danger">*</span></label>
                  <div class="input-group">
                     <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Re-enter new password">
                     <span class="input-group-text toggle-modal-password bg-light" data-target="#confirm_password" style="cursor: pointer;">
                        <i class="bi bi-eye"></i>
                     </span>
                  </div>
                  <small class="text-danger error" id="confirm_password_error"></small>
               </div>
            </div>
            <div class="modal-footer border-0 pt-0">
               <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
               <button type="submit" class="btn px-4" id="updatePasswordBtn" style="background-color: #334e68; color: #fff;">Update Password</button>
            </div>
         </form>
      </div>
   </div>
</div>

<!-- Manage Modules Modal -->
<div class="modal fade" id="changeCompanyModule" tabindex="-1" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content border-0 shadow-lg" style="border-radius: 12px;">
         <div class="modal-header border-bottom py-3 px-4">
            <div class="d-flex align-items-center gap-2">
               <div class="rounded bg-primary-subtle text-primary p-2 d-flex align-items-center justify-content-center">
                  <i class="bi bi-grid-3x3-gap fs-5"></i>
               </div>
               <div>
                  <h5 class="modal-title fw-bold text-dark mb-0">Manage Subscribed Modules</h5>
                  <small class="text-muted">Configure active groups and features for this household.</small>
               </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            <input type="hidden" id="company_id">
         </div>
         <div class="modal-body p-4" style="max-height: 70vh; overflow-y: auto;">
            <div class="d-flex justify-content-between align-items-center mb-3">
               <span class="text-muted small">Select or toggle features:</span>
               <div class="d-flex gap-1">
                  <button type="button" class="btn btn-sm btn-outline-primary py-0 px-2" id="modalSelectAll">Select All</button>
                  <button type="button" class="btn btn-sm btn-outline-secondary py-0 px-2" id="modalDeselectAll">Deselect All</button>
               </div>
            </div>
            <div id="moduleCheckboxContainer"></div>
            <small class="text-danger error d-block mt-2" id="modules_modal_error"></small>
         </div>
         <div class="modal-footer border-top py-2 px-4">
            <button class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
            <button class="btn px-4" id="saveModules" style="background-color: #334e68; color: #fff;">Save Changes</button>
         </div>
      </div>
   </div>
</div>

<a href="{{ route('superadmin.companies.create') }}" title="View Households" style="position: fixed; bottom: 25px; right: 25px; width: 55px; height: 55px; background-color: #ff9800; color: #fff; border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 15px rgba(255, 152, 0, 0.4); z-index: 9999; font-size: 24px; transition: transform 0.2s;">
   <i class="bi bi-plus"></i>
</a>
@endsection

@section('scripts')
<script>
   let dataTable;

   $(document).ready(function() {
      dataTable = $('#companiesTable').DataTable({
         processing: true,
         serverSide: true,
         ajax: "{{ route('superadmin.companies.datatable') }}",
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
            text: 'PDF',
            className: 'buttons-pdf dt-button',
            action: function(e, dt, node, config) {
               window.location.href = "{{ route('superadmin.companies.export.pdf') }}";
            }
         }],
         columnDefs: [{
            targets: [3, 4, 5],
            className: 'text-center'
         }],
         columns: [{
               data: 'company_info',
               name: 'company_name'
            },
            {
               data: 'admin_name',
               name: 'users.first_name'
            },
            {
               data: 'admin_email',
               name: 'users.email'
            },
            {
               data: 'admin_phone',
               name: 'users.phone'
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

      $('#password, #confirm_password').on('input', function() {
         $(this).removeClass('is-invalid');
         $('#' + $(this).attr('id') + '_error').html('');
      });

      $(document).on('click', '.changePasswordBtn', function() {
         $('#userId').val($(this).data('userid'));
         $('#password, #confirm_password').val('').removeClass('is-invalid');
         $('.error').html('');
         $('#password, #confirm_password').attr('type', 'password');
         $('.toggle-modal-password i').removeClass('bi-eye-slash').addClass('bi-eye');
      });

      $('#passwordModalForm').on('submit', function(e) {
         e.preventDefault();
         let isValid = true;
         $('.error').html('');
         $('#password, #confirm_password').removeClass('is-invalid');

         const password = $('#password').val();
         const confirmPassword = $('#confirm_password').val();

         if (!password) {
            $('#password_error').html('Password is required.');
            $('#password').addClass('is-invalid');
            isValid = false;
         } else if (password.length < 6) {
            $('#password_error').html('Password must be at least 6 characters.');
            $('#password').addClass('is-invalid');
            isValid = false;
         }

         if (!confirmPassword) {
            $('#confirm_password_error').html('Please confirm the password.');
            $('#confirm_password').addClass('is-invalid');
            isValid = false;
         } else if (password !== confirmPassword) {
            $('#confirm_password_error').html('Passwords do not match.');
            $('#confirm_password').addClass('is-invalid');
            isValid = false;
         }

         if (!isValid) return;

         const btn = $('#updatePasswordBtn');
         btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span>Updating...');
         $('#nms-loader-overlay').addClass('active');

         $.ajax({
            url: "{{ route('superadmin.companies.reset.password') }}",
            type: "POST",
            data: {
               id: $('#userId').val(),
               password: password,
               confirm_password: confirmPassword,
               _token: "{{ csrf_token() }}"
            },
            success: function(response) {
               btn.prop('disabled', false).text('Update Password');
               $('#nms-loader-overlay').removeClass('active');
               $('#changePasswordModal').modal('hide');
               if (typeof showAlert === 'function') showAlert('success', response.message);
            },
            error: function(xhr) {
               btn.prop('disabled', false).text('Update Password');
               $('#nms-loader-overlay').removeClass('active');
               if (xhr.status === 422 && xhr.responseJSON.errors) {
                  $.each(xhr.responseJSON.errors, function(key, val) {
                     $('#' + key + '_error').html(val[0]);
                     $('#' + key).addClass('is-invalid');
                  });
               } else {
                  alert(xhr.responseJSON?.message || 'Error updating password.');
               }
            }
         });
      });
   });

   function changeCompanyModuleBtn(companyId) {
      $('#company_id').val(companyId);
      $('#modules_modal_error').html('');
      $('#nms-loader-overlay').addClass('active');
      $('#moduleCheckboxContainer').html('<div class="text-center py-4 text-muted small"><span class="spinner-border spinner-border-sm me-1"></span>Loading modules...</div>');
      $('#changeCompanyModule').modal('show');

      $.ajax({
         url: "{{ route('superadmin.companies.get.modules') }}",
         type: "POST",
         data: {
            company_id: companyId,
            _token: "{{ csrf_token() }}"
         },
         success: function(response) {
            let html = '<div class="row g-3">';
            $.each(response.groupedModules, function(groupName, modules) {
               const groupSlug = groupName.toLowerCase().replace(/[^a-z0-9]/g, '-');
               let groupModulesHtml = '';
               let totalGroupItems = modules.length;
               let checkedGroupItems = 0;

               modules.forEach(function(m) {
                  const isChecked = response.assignedModules.includes(m.id);
                  if (isChecked) checkedGroupItems++;

                  groupModulesHtml += `
                     <div class="p-2 border rounded d-flex align-items-center justify-content-between bg-white mb-1">
                        <label class="form-check-label small fw-medium text-dark mb-0" for="modal_mod_${m.id}" style="cursor: pointer;">
                           <i class="${m.icon || 'bi bi-app'} text-primary me-1"></i> ${m.name}
                        </label>
                        <div class="form-check mb-0">
                           <input class="form-check-input modal-mod-checkbox modal-group-item-${groupSlug}" type="checkbox" name="modal_modules[]" value="${m.id}" id="modal_mod_${m.id}" data-group="${groupSlug}" ${isChecked ? 'checked' : ''}>
                        </div>
                     </div>
                  `;
               });

               const isAllGroupChecked = (totalGroupItems === checkedGroupItems && totalGroupItems > 0);

               html += `
                  <div class="col-md-6">
                     <div class="card border shadow-none h-100" style="border-radius: 8px; background: #fafbfe;">
                        <div class="card-header bg-light py-2 px-3 d-flex align-items-center justify-content-between border-bottom">
                           <span class="fw-bold small text-dark mb-0">${groupName}</span>
                           <div class="form-check mb-0">
                              <input class="form-check-input modal-group-master-switch" type="checkbox" id="modal_grp_${groupSlug}" data-group="${groupSlug}" ${isAllGroupChecked ? 'checked' : ''} title="Toggle Entire Group">
                           </div>
                        </div>
                        <div class="card-body p-2">
                           ${groupModulesHtml}
                        </div>
                     </div>
                  </div>
               `;
            });
            html += '</div>';
            $('#moduleCheckboxContainer').html(html);
            $('#nms-loader-overlay').removeClass('active');
         },
         error: function() {
            $('#nms-loader-overlay').removeClass('active');
            $('#moduleCheckboxContainer').html('<div class="text-danger text-center py-3">Failed to load module details.</div>');
         }
      });
   }

   $(document).on('change', '.modal-group-master-switch', function() {
      const groupSlug = $(this).data('group');
      const isChecked = $(this).is(':checked');
      $('.modal-group-item-' + groupSlug).prop('checked', isChecked);
   });

   $(document).on('change', '.modal-mod-checkbox', function() {
      const groupSlug = $(this).data('group');
      const total = $('.modal-group-item-' + groupSlug).length;
      const checked = $('.modal-group-item-' + groupSlug + ':checked').length;
      $('#modal_grp_' + groupSlug).prop('checked', total === checked);
   });

   $('#modalSelectAll').on('click', function() {
      $('.modal-mod-checkbox, .modal-group-master-switch').prop('checked', true);
   });
   $('#modalDeselectAll').on('click', function() {
      $('.modal-mod-checkbox, .modal-group-master-switch').prop('checked', false);
   });

   $('#saveModules').on('click', function() {
      const companyId = $('#company_id').val();
      let modules = [];
      $('input[name="modal_modules[]"]:checked').each(function() {
         modules.push($(this).val());
      });

      if (modules.length === 0) {
         if (typeof showAlert === 'function') showAlert('danger', 'Please select at least one module.');
         else alert('Please select at least one module.');
         return;
      }

      const btn = $(this);
      btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span>Saving...');
      $('#nms-loader-overlay').addClass('active');

      $.ajax({
         url: "{{ route('superadmin.companies.update.modules') }}",
         type: "POST",
         data: {
            company_id: companyId,
            modules: modules,
            _token: "{{ csrf_token() }}"
         },
         success: function(response) {
            btn.prop('disabled', false).text('Save Changes');
            $('#changeCompanyModule').modal('hide');
            if (typeof showAlert === 'function') showAlert('success', response.message);
            dataTable.ajax.reload(null, false);
            $('#nms-loader-overlay').removeClass('active');
         },
         error: function() {
            btn.prop('disabled', false).text('Save Changes');
            if (typeof showAlert === 'function') showAlert('danger', 'Error updating modules.');
            else alert('Error updating modules.');
            $('#nms-loader-overlay').removeClass('active');
         }
      });
   });

   $(document).on('click', '.toggle-modal-password', function() {
      const targetId = $(this).data('target');
      const targetField = $(targetId);
      const isPassword = targetField.attr('type') === 'password';
      targetField.attr('type', isPassword ? 'text' : 'password');
      $(this).find('i').toggleClass('bi-eye bi-eye-slash');
   });
</script>
@endsection