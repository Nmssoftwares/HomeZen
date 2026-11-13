@extends('superadmin.layouts.app')

@section('content')
<div class="pagetitle d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
   <div>
      <h1 class="mb-0">Edit Company</h1>
      <div class="text-muted small mt-1">Update company profile, regional settings, and primary administrator details.</div>
   </div>
   <a href="{{ route('superadmin.companies.index') }}" class="btn btn-outline-secondary d-flex align-items-center gap-1 shadow-sm" style="border-radius: 8px; font-weight: 600;">
      <i class="bi bi-eye"></i> View Companies
   </a>
</div>

<section class="section">
   <div class="row">
      <div class="col-lg-12">
         <div class="card-soft">
            <div class="card-body p-4">
               <!-- novalidate add kiya gaya hai browser errors rokne ke liye -->
               <form class="row g-3" id="editForm" enctype="multipart/form-data" novalidate>
                  @csrf
                  <input type="hidden" name="_method" value="PUT">

                  <!-- 1. Company Information -->
                  <div class="col-12">
                     <div class="d-flex justify-content-between align-items-center">
                        <h6 class="fw-bold text-primary mb-0"><i class="bi bi-building me-2"></i>1. Company Information</h6>
                        <span class="badge bg-primary-subtle text-primary border" style="font-size: 11px;">Tenant Details</span>
                     </div>
                     <small class="text-muted d-block mt-1">Modify organization details, contact info, logo, and time preferences.</small>
                     <hr class="mt-2 mb-3">
                  </div>

                  <div class="col-md-5">
                     <label for="company_name" class="form-label fw-semibold">Company Name <span class="text-danger">*</span></label>
                     <input type="text" class="form-control" id="company_name" name="company_name" value="{{ old('company_name', $company->company_name) }}" required>
                     <small class="text-danger error" id="company_name_error"></small>
                  </div>

                  <div class="col-md-3">
                     <label for="company_code" class="form-label fw-semibold">Company Code <small class="text-muted">(Prefix)</small></label>
                     <input type="text" class="form-control text-uppercase" id="company_code" name="company_code" value="{{ old('company_code', $company->company_code) }}" maxlength="10">
                     <small class="text-danger error" id="company_code_error"></small>
                  </div>

                  <div class="col-md-2">
                     <label for="status" class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                     <select class="form-select" id="status" name="status">
                        <option value="1" {{ $company->status == 1 ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ $company->status == 0 ? 'selected' : '' }}>Inactive</option>
                     </select>
                     <small class="text-danger error" id="status_error"></small>
                  </div>

                  <div class="col-md-2">
                     <label for="company_logo" class="form-label fw-semibold">Update Logo</label>
                     <input type="file" class="form-control" id="company_logo" name="company_logo" accept="image/*">
                     <small class="text-danger error" id="company_logo_error"></small>
                  </div>

                  <div class="col-md-6">
                     <label for="company_address" class="form-label fw-semibold">Company Address <span class="text-danger">*</span></label>
                     <input type="text" class="form-control" id="company_address" name="company_address" value="{{ old('company_address', $company->company_address) }}" required>
                     <small class="text-danger error" id="company_address_error"></small>
                  </div>

                  <div class="col-md-3">
                     <label for="company_email" class="form-label fw-semibold">Official Company Email</label>
                     <input type="email" class="form-control" id="company_email" name="company_email" value="{{ old('company_email', $company->company_email) }}">
                     <small class="text-danger error" id="company_email_error"></small>
                  </div>

                  <div class="col-md-3">
                     <label for="company_phone" class="form-label fw-semibold">Official Phone</label>
                     <input type="text" class="form-control" id="company_phone" name="company_phone" value="{{ old('company_phone', $company->company_phone) }}">
                     <small class="text-danger error" id="company_phone_error"></small>
                  </div>

                  <div class="col-md-4">
                     <label for="company_website" class="form-label fw-semibold">Website</label>
                     <input type="url" class="form-control" id="company_website" name="company_website" value="{{ old('company_website', $company->company_website) }}">
                     <small class="text-danger error" id="company_website_error"></small>
                  </div>

                  <div class="col-md-4">
                     <label for="timezone" class="form-label fw-semibold">Timezone</label>
                     <select class="form-select" id="timezone" name="timezone">
                        <option value="Asia/Kolkata" {{ $company->timezone == 'Asia/Kolkata' ? 'selected' : '' }}>Asia/Kolkata (IST +5:30)</option>
                        <option value="UTC" {{ $company->timezone == 'UTC' ? 'selected' : '' }}>UTC</option>
                        <option value="America/New_York" {{ $company->timezone == 'America/New_York' ? 'selected' : '' }}>America/New_York (EST)</option>
                        <option value="Europe/London" {{ $company->timezone == 'Europe/London' ? 'selected' : '' }}>Europe/London (GMT)</option>
                        <option value="Asia/Dubai" {{ $company->timezone == 'Asia/Dubai' ? 'selected' : '' }}>Asia/Dubai (GST)</option>
                     </select>
                     <small class="text-danger error" id="timezone_error"></small>
                  </div>

                  <div class="col-md-4">
                     <label for="date_format" class="form-label fw-semibold">Date Format</label>
                     <select class="form-select" id="date_format" name="date_format">
                        <option value="d-m-Y" {{ $company->date_format == 'd-m-Y' ? 'selected' : '' }}>d-m-Y (e.g. 26-08-2026)</option>
                        <option value="Y-m-d" {{ $company->date_format == 'Y-m-d' ? 'selected' : '' }}>Y-m-d (e.g. 2026-08-26)</option>
                        <option value="m/d/Y" {{ $company->date_format == 'm/d/Y' ? 'selected' : '' }}>m/d/Y (e.g. 08/26/2026)</option>
                     </select>
                     <small class="text-danger error" id="date_format_error"></small>
                  </div>

                  <!-- 2. Admin Account -->
                  <div class="col-12 mt-4">
                     <div class="d-flex justify-content-between align-items-center">
                        <h6 class="fw-bold text-primary mb-0"><i class="bi bi-person-badge me-2"></i>2. Administrator Profile</h6>
                        <span class="badge bg-light text-muted border" style="font-size: 11px;">Primary Login</span>
                     </div>
                     <small class="text-muted d-block mt-1">Manage personal and account credentials for the assigned root administrator.</small>
                     <hr class="mt-2 mb-3">
                  </div>

                  <div class="col-md-4">
                     <label for="first_name" class="form-label fw-semibold">First Name <span class="text-danger">*</span></label>
                     <input type="text" class="form-control" id="first_name" name="first_name" value="{{ old('first_name', $user->first_name ?? '') }}" required>
                     <small class="text-danger error" id="first_name_error"></small>
                  </div>

                  <div class="col-md-4">
                     <label for="last_name" class="form-label fw-semibold">Last Name <span class="text-danger">*</span></label>
                     <input type="text" class="form-control" id="last_name" name="last_name" value="{{ old('last_name', $user->last_name ?? '') }}" required>
                     <small class="text-danger error" id="last_name_error"></small>
                  </div>

                  <div class="col-md-4">
                     <label for="employee_code" class="form-label fw-semibold">Employee Code</label>
                     <input type="text" class="form-control text-uppercase" id="employee_code" name="employee_code" value="{{ old('employee_code', $user->employee_code ?? '') }}">
                     <small class="text-danger error" id="employee_code_error"></small>
                  </div>

                  <div class="col-md-6">
                     <label for="email" class="form-label fw-semibold">Admin Login Email <span class="text-danger">*</span></label>
                     <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email ?? '') }}" required>
                     <small class="text-danger error" id="email_error"></small>
                  </div>

                  <div class="col-md-6">
                     <label for="phone" class="form-label fw-semibold">Phone Number <span class="text-danger">*</span></label>
                     <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone', $user->phone ?? '') }}" placeholder="000-000-0000" maxlength="12" required>
                     <small class="text-danger error" id="phone_error"></small>
                  </div>

                  <div class="col-md-3">
                     <label for="designation" class="form-label fw-semibold">Designation</label>
                     <input type="text" class="form-control" id="designation" name="designation" value="{{ old('designation', $user->designation ?? '') }}">
                     <small class="text-danger error" id="designation_error"></small>
                  </div>

                  <div class="col-md-3">
                     <label for="department" class="form-label fw-semibold">Department</label>
                     <input type="text" class="form-control" id="department" name="department" value="{{ old('department', $user->department ?? '') }}">
                     <small class="text-danger error" id="department_error"></small>
                  </div>

                  <div class="col-md-3">
                     <label for="dob" class="form-label fw-semibold">Date of Birth</label>
                     <input type="date" class="form-control" id="dob" name="dob" value="{{ old('dob', $user->dob ?? '') }}" onkeydown="return false" onpaste="return false" autocomplete="off">
                     <small class="text-danger error" id="dob_error"></small>
                  </div>

                  <div class="col-md-3">
                     <label for="joining_date" class="form-label fw-semibold">Joining Date</label>
                     <input type="date" class="form-control" id="joining_date" name="joining_date" value="{{ old('joining_date', $user->joining_date ?? '') }}" onkeydown="return false" onpaste="return false" autocomplete="off">
                     <small class="text-danger error" id="joining_date_error"></small>
                  </div>

                  <div class="col-12 mt-4 text-start">
                     <button type="button" class="btn btn-secondary me-2" id="closeBtn">Cancel</button>
                     <button type="submit" class="btn btn-primary px-4" id="updateBtn">Update Company</button>
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
      // Helper function for Phone formatting
      function formatPhone(val) {
         val = val.replace(/\D/g, '').substring(0, 10);
         let formatted = '';
         if (val.length > 0) formatted += val.substring(0, 3);
         if (val.length >= 4) formatted += '-' + val.substring(3, 6);
         if (val.length >= 7) formatted += '-' + val.substring(6, 10);
         return formatted;
      }

      // Initial formatting on load
      $('#phone').val(formatPhone($('#phone').val()));

      // Phone Auto Formatting on input
      $('#phone').on('input', function() {
         $(this).val(formatPhone($(this).val()));
      });

      // Clear validation errors on input/change
      $('input, select, textarea').on('input change', function() {
         $(this).removeClass('is-invalid');
         $('#' + $(this).attr('name') + '_error').html('');
      });

      // Helper function for Email validation
      function isValidEmail(email) {
         const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
         return regex.test(email);
      }

      // Submit & Validate
      $('#editForm').on('submit', function(e) {
         e.preventDefault();

         // 1. Initialize validation state
         let isValid = true;
         $('.error').html('');
         $('.form-control').removeClass('is-invalid');

         // 2. Perform Client-Side Validations

         // Company Name
         if (!$('#company_name').val().trim()) {
            $('#company_name_error').text('Company name is required.');
            $('#company_name').addClass('is-invalid');
            isValid = false;
         }

         // Company Address
         if (!$('#company_address').val().trim()) {
            $('#company_address_error').text('Company address is required.');
            $('#company_address').addClass('is-invalid');
            isValid = false;
         }

         // Company Email (Optional, but checked if present)
         let compEmail = $('#company_email').val().trim();
         if (compEmail && !isValidEmail(compEmail)) {
            $('#company_email_error').text('Please enter a valid email format.');
            $('#company_email').addClass('is-invalid');
            isValid = false;
         }

         // Admin First Name
         if (!$('#first_name').val().trim()) {
            $('#first_name_error').text('First name is required.');
            $('#first_name').addClass('is-invalid');
            isValid = false;
         }

         // Admin Last Name
         if (!$('#last_name').val().trim()) {
            $('#last_name_error').text('Last name is required.');
            $('#last_name').addClass('is-invalid');
            isValid = false;
         }

         // Admin Login Email
         let adminEmail = $('#email').val().trim();
         if (!adminEmail) {
            $('#email_error').text('Admin login email is required.');
            $('#email').addClass('is-invalid');
            isValid = false;
         } else if (!isValidEmail(adminEmail)) {
            $('#email_error').text('Please enter a valid email address.');
            $('#email').addClass('is-invalid');
            isValid = false;
         }

         // Admin Phone Number
         let adminPhone = $('#phone').val().replace(/\D/g, '');
         if (!adminPhone) {
            $('#phone_error').text('Phone number is required.');
            $('#phone').addClass('is-invalid');
            isValid = false;
         } else if (adminPhone.length < 10) {
            $('#phone_error').text('Please enter a valid 10-digit phone number.');
            $('#phone').addClass('is-invalid');
            isValid = false;
         }

         // 3. Stop execution if validation fails
         if (!isValid) {
            // Scroll to the first error smoothly
            $('html, body').animate({
               scrollTop: $('.is-invalid').first().offset().top - 100
            }, 300);
            return false;
         }

         // 4. Proceed with AJAX Submission
         const btn = $('#updateBtn');
         btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span>Updating...');
         $('#nms-loader-overlay').addClass('active');

         const formData = new FormData(this);

         $.ajax({
            url: "{{ route('superadmin.companies.update', $company->id) }}",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
               if (response.status === 'success') {
                  if (typeof showAlert === 'function') showAlert('success', response.message);
                  $('#nms-loader-overlay').removeClass('active');
                  setTimeout(() => {
                     window.location.href = "{{ route('superadmin.companies.index') }}";
                  }, 1000);
               } else {
                  btn.prop('disabled', false).text('Update Company');
                  $('#nms-loader-overlay').removeClass('active');
               }
            },
            error: function(xhr) {
               btn.prop('disabled', false).text('Update Company');
               $('#nms-loader-overlay').removeClass('active');

               // Server-side errors ko show karega
               if (xhr.status === 422 && xhr.responseJSON.errors) {
                  $.each(xhr.responseJSON.errors, function(key, val) {
                     $('#' + key + '_error').html(val[0]);
                     $('#' + key).addClass('is-invalid');
                  });
                  // Error hone par waha scroll karega
                  $('html, body').animate({
                     scrollTop: $('.is-invalid').first().offset().top - 100
                  }, 300);
               } else {
                  alert(xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Error updating company.');
               }
            }
         });
      });

      $('#closeBtn').on('click', function() {
         window.location.href = "{{ route('superadmin.companies.index') }}";
      });
   });
</script>
@endsection