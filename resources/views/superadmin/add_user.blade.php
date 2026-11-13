@extends('superadmin.layouts.app')

@section('content')
<div class="pagetitle d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
   <div>
      <h1 class="mb-0">Add New User</h1>
      <div class="text-muted small mt-1">Register a new team member, assign their role, and configure employment credentials.</div>
   </div>
   <a href="{{ route('superadmin.user.index') }}" class="btn btn-outline-secondary d-flex align-items-center gap-1 shadow-sm" style="border-radius: 8px; font-weight: 600;">
      <i class="bi bi-eye"></i> View Users
   </a>
</div>

<section class="section">
   <div class="row">
      <div class="col-lg-12">
         <div class="card-soft">
            <div class="card-body p-4">
               <form class="row g-3" id="addForm" enctype="multipart/form-data" novalidate>
                  @csrf

                  <!-- 1. Basic Information -->
                  <div class="col-12">
                     <div class="d-flex justify-content-between align-items-center">
                        <h6 class="fw-bold text-primary mb-0"><i class="bi bi-person-lines-fill me-2"></i>1. Personal & Account Details</h6>
                        <span class="badge bg-primary-subtle text-primary border" style="font-size: 11px;">Identity</span>
                     </div>
                     <small class="text-muted d-block mt-1">Provide employee personal information and login credentials.</small>
                     <hr class="mt-2 mb-3">
                  </div>

                  <div class="col-md-4">
                     <label for="first_name" class="form-label fw-semibold">First Name <span class="text-danger">*</span></label>
                     <input type="text" class="form-control" id="first_name" name="first_name" placeholder="John" required>
                     <small class="text-danger error" id="first_name_error"></small>
                  </div>

                  <div class="col-md-4">
                     <label for="last_name" class="form-label fw-semibold">Last Name <span class="text-danger">*</span></label>
                     <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Doe" required>
                     <small class="text-danger error" id="last_name_error"></small>
                  </div>

                  <div class="col-md-4">
                     <label for="gender" class="form-label fw-semibold">Gender</label>
                     <select class="form-select" id="gender" name="gender">
                        <option value="" selected>Select gender</option>
                        <option value="1">Male</option>
                        <option value="2">Female</option>
                        <option value="3">Other</option>
                     </select>
                     <small class="text-danger error" id="gender_error"></small>
                  </div>

                  <div class="col-md-4">
                     <label for="email" class="form-label fw-semibold">Email Address <span class="text-danger">*</span></label>
                     <input type="email" class="form-control" id="email" name="email" placeholder="john.doe@company.com" required>
                     <small class="text-danger error" id="email_error"></small>
                  </div>

                  <div class="col-md-4">
                     <label for="phone" class="form-label fw-semibold">Phone Number <span class="text-danger">*</span></label>
                     <input type="text" class="form-control" id="phone" name="phone" maxlength="12" placeholder="000-000-0000" required>
                     <small class="text-danger error" id="phone_error"></small>
                  </div>

                  <div class="col-md-4">
                     <label for="password" class="form-label fw-semibold">Password <span class="text-danger">*</span></label>
                     <div class="input-group">
                        <input type="password" class="form-control" id="password" name="password" placeholder="Minimum 6 characters" required>
                        <span class="input-group-text" id="togglePassword" style="cursor:pointer;"><i class="bi bi-eye"></i></span>
                     </div>
                     <small class="text-danger error" id="password_error"></small>
                  </div>

                  <!-- 2. Employment & Role -->
                  <div class="col-12 mt-4">
                     <div class="d-flex justify-content-between align-items-center">
                        <h6 class="fw-bold text-primary mb-0"><i class="bi bi-briefcase me-2"></i>2. Employment & Role Allocation</h6>
                        <span class="badge bg-light text-muted border" style="font-size: 11px;">Designation</span>
                     </div>
                     <small class="text-muted d-block mt-1">Configure company employee code, team department, and operational role access.</small>
                     <hr class="mt-2 mb-3">
                  </div>

                  <div class="col-md-3">
                     <label for="employee_code" class="form-label fw-semibold">Employee Code</label>
                     <input type="text" class="form-control text-uppercase" id="employee_code" name="employee_code" placeholder="e.g. EMP-101">
                     <small class="text-danger error" id="employee_code_error"></small>
                  </div>

                  <div class="col-md-3">
                     <label for="role" class="form-label fw-semibold">System Role <span class="text-danger">*</span></label>
                     <select class="form-select" id="role" name="role" required>
                        <option value="3" selected>User / Employee (Standard)</option>
                        <option value="4">Team Lead / Manager (Approver)</option>
                        <option value="2">Company Admin (Full Management)</option>
                     </select>
                     <small class="text-danger error" id="role_error"></small>
                  </div>

                  <div class="col-md-3">
                     <label for="designation" class="form-label fw-semibold">Designation</label>
                     <input type="text" class="form-control" id="designation" name="designation" placeholder="e.g. Fullstack Developer">
                     <small class="text-danger error" id="designation_error"></small>
                  </div>

                  <div class="col-md-3">
                     <label for="department" class="form-label fw-semibold">Department</label>
                     <input type="text" class="form-control" id="department" name="department" placeholder="e.g. Engineering">
                     <small class="text-danger error" id="department_error"></small>
                  </div>

                  <div class="col-md-4">
                     <label for="dob" class="form-label fw-semibold">Date of Birth</label>
                     <input type="date" class="form-control" id="dob" name="dob" onkeydown="return false" onpaste="return false" autocomplete="off">
                     <small class="text-danger error" id="dob_error"></small>
                  </div>

                  <div class="col-md-4">
                     <label for="joining_date" class="form-label fw-semibold">Joining Date</label>
                     <input type="date" class="form-control" id="joining_date" name="joining_date" value="{{ date('Y-m-d') }}" onkeydown="return false" onpaste="return false" autocomplete="off">
                     <small class="text-danger error" id="joining_date_error"></small>
                  </div>

                  <div class="col-md-4">
                     <label for="profile_image" class="form-label fw-semibold">Profile Photo</label>
                     <input type="file" class="form-control" id="profile_image" name="profile_image" accept="image/*">
                     <small class="text-danger error" id="profile_image_error"></small>
                  </div>

                  <div class="col-12 mt-4 text-start">
                     <button type="button" class="btn btn-secondary me-2" id="closeBtn">Cancel</button>
                     <button type="submit" class="btn btn-primary px-4" id="saveBtn">Save User</button>
                  </div>
               </form>
            </div>
         </div>
      </div>
   </div>
</section>
@endsection

@section('scripts')
<script>
   $(document).ready(function() {
      // Helper function for Email validation
      function isValidEmail(email) {
         const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
         return regex.test(email);
      }

      // Phone Auto Formatting
      $('#phone').on('input', function() {
         let val = $(this).val().replace(/\D/g, '').substring(0, 10);
         let formatted = '';
         if (val.length > 0) formatted += val.substring(0, 3);
         if (val.length >= 4) formatted += '-' + val.substring(3, 6);
         if (val.length >= 7) formatted += '-' + val.substring(6, 10);
         $(this).val(formatted);
      });

      // Toggle Password Visibility
      $('#togglePassword').on('click', function() {
         const field = $('#password');
         field.attr('type', field.attr('type') === 'password' ? 'text' : 'password');
         $(this).find('i').toggleClass('bi-eye bi-eye-slash');
      });

      // Real-time Clear Validation Error
      $('input, select, textarea').on('input change', function() {
         $(this).removeClass('is-invalid');
         $('#' + $(this).attr('name') + '_error').html('');
      });

      // Form Submit & JS Validation
      $('#addForm').on('submit', function(e) {
         e.preventDefault();

         // 1. Reset Errors
         let isValid = true;
         $('.error').html('');
         $('.form-control, .form-select').removeClass('is-invalid');

         // 2. Perform Validation

         // First Name
         if (!$('#first_name').val().trim()) {
            $('#first_name_error').text('First name is required.');
            $('#first_name').addClass('is-invalid');
            isValid = false;
         }

         // Last Name
         if (!$('#last_name').val().trim()) {
            $('#last_name_error').text('Last name is required.');
            $('#last_name').addClass('is-invalid');
            isValid = false;
         }

         // Email
         let email = $('#email').val().trim();
         if (!email) {
            $('#email_error').text('Email address is required.');
            $('#email').addClass('is-invalid');
            isValid = false;
         } else if (!isValidEmail(email)) {
            $('#email_error').text('Please enter a valid email address.');
            $('#email').addClass('is-invalid');
            isValid = false;
         }

         // Phone
         let phone = $('#phone').val().replace(/\D/g, '');
         if (!phone) {
            $('#phone_error').text('Phone number is required.');
            $('#phone').addClass('is-invalid');
            isValid = false;
         } else if (phone.length < 10) {
            $('#phone_error').text('Please enter a valid 10-digit phone number.');
            $('#phone').addClass('is-invalid');
            isValid = false;
         }

         // Password
         let password = $('#password').val();
         if (!password) {
            $('#password_error').text('Password is required.');
            $('#password').addClass('is-invalid');
            isValid = false;
         } else if (password.length < 6) {
            $('#password_error').text('Password must be at least 6 characters.');
            $('#password').addClass('is-invalid');
            isValid = false;
         }

         // Role
         if (!$('#role').val()) {
            $('#role_error').text('Please select a system role.');
            $('#role').addClass('is-invalid');
            isValid = false;
         }

         // 3. Stop execution and scroll if validation fails
         if (!isValid) {
            $('html, body').animate({
               scrollTop: $('.is-invalid').first().offset().top - 100
            }, 300);
            return false;
         }

         // 4. AJAX Submission
         const btn = $('#saveBtn');
         btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span>Saving...');
         $('#nms-loader-overlay').addClass('active');

         const formData = new FormData(this);

         $.ajax({
            url: "{{ route('superadmin.user.store') }}",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
               if (response.status === 'success') {
                  if (typeof showAlert === 'function') showAlert('success', response.message);
                  $('#nms-loader-overlay').removeClass('active');
                  setTimeout(() => {
                     window.location.href = "{{ route('superadmin.user.index') }}";
                  }, 1000);
               } else {
                  btn.prop('disabled', false).text('Save User');
                  $('#nms-loader-overlay').removeClass('active');
                  if (typeof showAlert === 'function') showAlert('danger', response.message);
               }
            },
            error: function(xhr) {
               btn.prop('disabled', false).text('Save User');
               $('#nms-loader-overlay').removeClass('active');

               // Handle server-side validation errors
               if (xhr.status === 422 && xhr.responseJSON.errors) {
                  $.each(xhr.responseJSON.errors, function(key, val) {
                     $('#' + key + '_error').html(val[0]);
                     $('[name="' + key + '"]').addClass('is-invalid');
                  });
                  // Scroll to first server-side error
                  $('html, body').animate({
                     scrollTop: $('.is-invalid').first().offset().top - 100
                  }, 300);
               } else {
                  alert(xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Error saving user.');
               }
            }
         });
      });

      $('#closeBtn').on('click', function() {
         window.location.href = "{{ route('superadmin.user.index') }}";
      });
   });
</script>
@endsection