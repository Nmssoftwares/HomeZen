@extends('superadmin.layouts.app')

@section('content')
<div class="pagetitle d-flex justify-content-between align-items-center mb-3">
   <div>
      <h1 class="mb-0">Users & Employees</h1>
      <div class="text-muted small mt-1">Manage team members, roles, contact credentials, and statuses.</div>
   </div>
   <a href="{{ route('superadmin.user.create') }}" class="btn btn-primary" style="border-radius: 8px; font-weight: 600;">
      <i class="bi bi-person-plus me-1"></i> Add User
   </a>
</div>

<section class="section">
   <div class="row">
      <div class="col-lg-12">
         <div class="card-soft">
            <div class="p-3">
               <table class="table table-bordered align-middle" id="usersTable" width="100%">
                  <thead>
                     <tr>
                        <th>Employee Details</th>
                        <th>Contact Credentials</th>
                        <th class="text-center">Joining Date</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Action</th>
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

<!-- Change Password Modal (With Eye Icon) -->
<div class="modal fade" id="changePasswordModal" tabindex="-1" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
         <div class="modal-header border-0 pb-0">
            <h5 class="modal-title fw-bold text-dark"><i class="bi bi-key text-primary me-2"></i>Reset User Password</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
         </div>
         <div class="modal-body pt-3">
            <input type="hidden" id="userId">
            <div class="mb-3">
               <label for="password" class="form-label fw-semibold small">New Password <span class="text-danger">*</span></label>
               <div class="input-group">
                  <input type="password" class="form-control" id="password" placeholder="Minimum 6 characters">
                  <span class="input-group-text toggle-modal-password bg-light" data-target="#password" style="cursor: pointer;">
                     <i class="bi bi-eye"></i>
                  </span>
               </div>
               <small class="text-danger error" id="password_error"></small>
            </div>
            <div class="mb-3">
               <label for="confirm_password" class="form-label fw-semibold small">Confirm Password <span class="text-danger">*</span></label>
               <div class="input-group">
                  <input type="password" class="form-control" id="confirm_password" placeholder="Re-enter password">
                  <span class="input-group-text toggle-modal-password bg-light" data-target="#confirm_password" style="cursor: pointer;">
                     <i class="bi bi-eye"></i>
                  </span>
               </div>
               <small class="text-danger error" id="confirm_password_error"></small>
            </div>
         </div>
         <div class="modal-footer border-0 pt-0">
            <button class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
            <button class="btn btn-primary px-3" id="updatePassword">Update Password</button>
         </div>
      </div>
   </div>
</div>

<!-- Manage User Module Permissions Modal -->
<div class="modal fade" id="userModuleModal" tabindex="-1" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
         <div class="modal-header border-bottom py-3 px-4">
            <div class="d-flex align-items-center gap-2">
               <div class="rounded bg-primary-subtle text-primary p-2 d-flex align-items-center justify-content-center">
                  <i class="bi bi-shield-check fs-5"></i>
               </div>
               <div>
                  <h5 class="modal-title fw-bold text-dark mb-0">Manage Module Permissions</h5>
                  <small class="text-muted" id="userModalSubtitle">Assign accessible workspaces to employee</small>
               </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            <input type="hidden" id="perm_user_id">
         </div>
         <div class="modal-body p-4" style="max-height: 70vh; overflow-y: auto;">
            <div class="d-flex justify-content-between align-items-center mb-3">
               <span class="text-muted small">Toggle individual modules or entire business suites:</span>
               <div class="d-flex gap-1">
                  <button type="button" class="btn btn-sm btn-outline-primary py-0 px-2" id="modalPermSelectAll">Select All</button>
                  <button type="button" class="btn btn-sm btn-outline-secondary py-0 px-2" id="modalPermDeselectAll">Deselect All</button>
               </div>
            </div>
            <div id="userModuleContainer"></div>
            <small class="text-danger error d-block mt-2" id="user_modules_error"></small>
         </div>
         <div class="modal-footer border-top py-2 px-4">
            <button class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
            <button class="btn btn-primary px-4" id="saveUserModulesBtn">Save Permissions</button>
         </div>
      </div>
   </div>
</div>
@endsection

@section('scripts')
<script>
   let dataTable;

   $(document).ready(function() {
      dataTable = $('#usersTable').DataTable({
         processing: true,
         serverSide: true,
         ajax: "{{ route('superadmin.user.datatable') }}",
         dom: 'Bfrtip',
         buttons: [{
            extend: 'excel',
            className: 'd-none',
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
            targets: [2, 3, 4],
            className: 'text-center'
         }],
         columns: [{
               data: 'user_card',
               name: 'first_name'
            },
            {
               data: 'contact_info',
               name: 'email'
            },
            {
               data: 'joining_date_formatted',
               name: 'joining_date'
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

      // Floating Export
      $('#exportMainBtn').on('click', function(e) {
         e.preventDefault();
         $('.floating-export-wrapper').toggleClass('active');
      });
      $('#exportExcel').on('click', function() {
         dataTable.button('.buttons-excel').trigger();
         $('.floating-export-wrapper').removeClass('active');
      });
      $('#exportPdf').on('click', function() {
         // Custom Backend PDF Route call
         window.location.href = "{{ route('superadmin.user.export.pdf') }}";
         $('.floating-export-wrapper').removeClass('active');
      });

      // Toggle Password Eye Icon
      $(document).on('click', '.toggle-modal-password', function() {
         let target = $($(this).data('target'));
         let icon = $(this).find('i');
         if (target.attr('type') === 'password') {
            target.attr('type', 'text');
            icon.removeClass('bi-eye').addClass('bi-eye-slash text-primary');
         } else {
            target.attr('type', 'password');
            icon.removeClass('bi-eye-slash text-primary').addClass('bi-eye');
         }
      });

      // Remove errors on input change
      $('#password, #confirm_password').on('input', function() {
         $(this).removeClass('is-invalid');
         $('#' + $(this).attr('id') + '_error').html('');
      });

      // Password Reset Modal Handlers
      $(document).on('click', '.changePasswordBtn', function() {
         $('#userId').val($(this).data('userid'));
         $('#password, #confirm_password').val('').removeClass('is-invalid');
         $('.error').html('');
         // Reset eye icon and type
         $('#password, #confirm_password').attr('type', 'password');
         $('.toggle-modal-password i').removeClass('bi-eye-slash text-primary').addClass('bi-eye');
      });

      $('#updatePassword').on('click', function() {
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

         const btn = $(this);
         btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span>Updating...');
         $('#nms-loader-overlay').addClass('active');

         $.ajax({
            url: "{{ route('superadmin.user.reset.password') }}",
            type: "POST",
            data: {
               id: $('#userId').val(),
               password: password,
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
                  if (typeof showAlert === 'function') showAlert('danger', 'Server error occurred.');
               }
            }
         });
      });

      // ==========================================
      // USER MODULE PERMISSIONS LOGIC
      // ==========================================

      // Select All / Deselect All
      $('#modalPermSelectAll').on('click', function() {
         $('.user-mod-checkbox, .user-group-master-switch').prop('checked', true);
      });
      $('#modalPermDeselectAll').on('click', function() {
         $('.user-mod-checkbox, .user-group-master-switch').prop('checked', false);
      });

      // Master Toggle per Group in Modal
      $(document).on('change', '.user-group-master-switch', function() {
         const groupSlug = $(this).data('group');
         const isChecked = $(this).is(':checked');
         $('.user-group-item-' + groupSlug).prop('checked', isChecked);
      });

      // Individual Toggle Check in Modal
      $(document).on('change', '.user-mod-checkbox', function() {
         const groupSlug = $(this).data('group');
         const total = $('.user-group-item-' + groupSlug).length;
         const checked = $('.user-group-item-' + groupSlug + ':checked').length;
         $('#user_grp_' + groupSlug).prop('checked', total === checked);
      });

      // Save User Permissions
      $('#saveUserModulesBtn').on('click', function() {
         const userId = $('#perm_user_id').val();
         let modules = [];
         $('input[name="user_modules[]"]:checked').each(function() {
            modules.push($(this).val());
         });

         // Naya Empty Validation logic with showAlert
         if (modules.length === 0) {
            if (typeof showAlert === 'function') {
               showAlert('danger', 'Please select at least one module for this user.');
            } else {
               alert('Please select at least one module for this user.');
            }
            return;
         }

         const btn = $(this);
         btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span>Saving...');
         $('#nms-loader-overlay').addClass('active');

         $.ajax({
            url: "{{ route('superadmin.user.update.modules') }}",
            type: "POST",
            data: {
               user_id: userId,
               modules: modules,
               _token: "{{ csrf_token() }}"
            },
            success: function(response) {
               btn.prop('disabled', false).text('Save Permissions');
               $('#userModuleModal').modal('hide');
               if (typeof showAlert === 'function') showAlert('success', response.message);
               $('#nms-loader-overlay').removeClass('active');
            },
            error: function() {
               btn.prop('disabled', false).text('Save Permissions');
               if (typeof showAlert === 'function') {
                  showAlert('danger', 'Error updating user module permissions.');
               } else {
                  alert('Error updating user module permissions.');
               }
               $('#nms-loader-overlay').removeClass('active');
            }
         });
      });
   });

   // Open Manage User Permissions Modal function
   function changeUserModuleBtn(userId, userName) {
      $('#perm_user_id').val(userId);
      $('#userModalSubtitle').text('Configure module access permissions for ' + userName);
      $('#user_modules_error').html('');
      $('#nms-loader-overlay').addClass('active');
      $('#userModuleContainer').html('<div class="text-center py-4 text-muted small"><span class="spinner-border spinner-border-sm me-1"></span>Loading accessible modules...</div>');
      $('#userModuleModal').modal('show');

      $.ajax({
         url: "{{ route('superadmin.user.get.modules') }}",
         type: "POST",
         data: {
            user_id: userId,
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
                     <div class="p-2 border rounded d-flex align-items-center justify-content-between bg-white mb-1 shadow-2xs">
                        <label class="form-check-label small fw-medium text-dark mb-0" for="user_mod_${m.id}" style="cursor: pointer;">
                           <i class="${m.icon || 'bi bi-app'} text-primary me-1"></i> ${m.name}
                        </label>
                        <div class="form-check form-switch mb-0">
                           <input class="form-check-input user-mod-checkbox user-group-item-${groupSlug}" type="checkbox" name="user_modules[]" value="${m.id}" id="user_mod_${m.id}" data-group="${groupSlug}" ${isChecked ? 'checked' : ''}>
                        </div>
                     </div>
                  `;
               });

               const isAllGroupChecked = (totalGroupItems === checkedGroupItems && totalGroupItems > 0);

               html += `
                  <div class="col-md-6">
                     <div class="card border shadow-none h-100" style="border-radius: 10px; background: #fafbfe;">
                        <div class="card-header bg-light py-2 px-3 d-flex align-items-center justify-content-between border-bottom">
                           <span class="fw-bold small text-dark mb-0">${groupName}</span>
                           <div class="form-check form-switch mb-0">
                              <input class="form-check-input user-group-master-switch" type="checkbox" id="user_grp_${groupSlug}" data-group="${groupSlug}" ${isAllGroupChecked ? 'checked' : ''} title="Toggle Entire Suite">
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
            $('#userModuleContainer').html(html);
            $('#nms-loader-overlay').removeClass('active');
         },
         error: function() {
            $('#nms-loader-overlay').removeClass('active');
            $('#userModuleContainer').html('<div class="text-danger text-center py-3">Failed to load subscribed modules.</div>');
         }
      });
   }
</script>
@endsection