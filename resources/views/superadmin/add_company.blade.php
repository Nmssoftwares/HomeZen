@extends('superadmin.layouts.app')

@section('content')
<div class="pagetitle d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
   <div>
      <h1 class="mb-0">Add New Company</h1>
      <div class="text-muted small mt-1">Register a new client company, configure their primary admin credentials, and assign subscribed system modules.</div>
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
               <form class="row g-3" id="addForm" enctype="multipart/form-data" novalidate>
                  @csrf
                  <!-- 1. Company Information -->
                  <div class="col-12">
                     <div class="d-flex justify-content-between align-items-center">
                        <h6 class="fw-bold text-primary mb-0"><i class="bi bi-building me-2"></i>1. Company Information</h6>
                        <span class="badge bg-primary-subtle text-primary border" style="font-size: 11px;">Tenant Details</span>
                     </div>
                     <small class="text-muted d-block mt-1">Provide the organization's official brand details and regional configuration.</small>
                     <hr class="mt-2 mb-3">
                  </div>

                  <div class="col-md-5">
                     <label for="company_name" class="form-label fw-semibold">Company Name <span class="text-danger">*</span></label>
                     <input type="text" class="form-control" id="company_name" name="company_name" placeholder="e.g. Acme Tech Solutions" required>
                     <small class="text-danger error" id="company_name_error"></small>
                  </div>

                  <div class="col-md-3">
                     <label for="company_code" class="form-label fw-semibold">Company Code <small class="text-muted">(Prefix)</small></label>
                     <input type="text" class="form-control text-uppercase" id="company_code" name="company_code" placeholder="e.g. ACME" maxlength="10">
                     <small class="text-danger error" id="company_code_error"></small>
                  </div>

                  <div class="col-md-4">
                     <label for="company_logo" class="form-label fw-semibold">Company Logo</label>
                     <input type="file" class="form-control" id="company_logo" name="company_logo" accept="image/*">
                     <small class="text-danger error" id="company_logo_error"></small>
                  </div>

                  <div class="col-md-6">
                     <label for="company_address" class="form-label fw-semibold">Company Address <span class="text-danger">*</span></label>
                     <input type="text" class="form-control" id="company_address" name="company_address" placeholder="e.g. 101 Silicon Valley, Vadodara" required>
                     <small class="text-danger error" id="company_address_error"></small>
                  </div>

                  <div class="col-md-3">
                     <label for="company_email" class="form-label fw-semibold">Official Company Email</label>
                     <input type="email" class="form-control" id="company_email" name="company_email" placeholder="contact@acme.com">
                     <small class="text-danger error" id="company_email_error"></small>
                  </div>

                  <div class="col-md-3">
                     <label for="company_phone" class="form-label fw-semibold">Official Phone</label>
                     <input type="text" class="form-control" id="company_phone" name="company_phone" placeholder="Landline / Support">
                     <small class="text-danger error" id="company_phone_error"></small>
                  </div>

                  <div class="col-md-4">
                     <label for="company_website" class="form-label fw-semibold">Website</label>
                     <input type="url" class="form-control" id="company_website" name="company_website" placeholder="https://acme.com">
                     <small class="text-danger error" id="company_website_error"></small>
                  </div>

                  <div class="col-md-4">
                     <label for="timezone" class="form-label fw-semibold">Timezone</label>
                     <select class="form-select" id="timezone" name="timezone">
                        <option value="Asia/Kolkata" selected>Asia/Kolkata (IST +5:30)</option>
                        <option value="UTC">UTC</option>
                        <option value="America/New_York">America/New_York (EST)</option>
                        <option value="Europe/London">Europe/London (GMT)</option>
                        <option value="Asia/Dubai">Asia/Dubai (GST)</option>
                     </select>
                     <small class="text-danger error" id="timezone_error"></small>
                  </div>

                  <div class="col-md-4">
                     <label for="date_format" class="form-label fw-semibold">Date Format</label>
                     <select class="form-select" id="date_format" name="date_format">
                        <option value="d-m-Y" selected>d-m-Y (e.g. 26-08-2026)</option>
                        <option value="Y-m-d">Y-m-d (e.g. 2026-08-26)</option>
                        <option value="m/d/Y">m/d/Y (e.g. 08/26/2026)</option>
                     </select>
                     <small class="text-danger error" id="date_format_error"></small>
                  </div>

                  <!-- 2. Admin Account -->
                  <div class="col-12 mt-4">
                     <div class="d-flex justify-content-between align-items-center">
                        <h6 class="fw-bold text-primary mb-0"><i class="bi bi-person-badge me-2"></i>2. Administrator Account & Employee Profile</h6>
                        <span class="badge bg-light text-muted border" style="font-size: 11px;">Primary Login</span>
                     </div>
                     <small class="text-muted d-block mt-1">This user will receive root admin rights (Role 2) to manage their organization and employees.</small>
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
                     <label for="employee_code" class="form-label fw-semibold">Employee Code</label>
                     <input type="text" class="form-control text-uppercase" id="employee_code" name="employee_code" placeholder="EMP-001">
                     <small class="text-danger error" id="employee_code_error"></small>
                  </div>

                  <div class="col-md-4">
                     <label for="email" class="form-label fw-semibold">Admin Login Email <span class="text-danger">*</span></label>
                     <input type="email" class="form-control" id="email" name="email" placeholder="admin@acme.com" required>
                     <small class="text-danger error" id="email_error"></small>
                  </div>

                  <div class="col-md-4">
                     <label for="phone" class="form-label fw-semibold">Phone Number <span class="text-danger">*</span></label>
                     <input type="text" class="form-control" id="phone" name="phone" maxlength="12" placeholder="000-000-0000" required>
                     <small class="text-danger error" id="phone_error"></small>
                  </div>

                  <div class="col-md-4">
                     <label for="password" class="form-label fw-semibold">Default Password <span class="text-danger">*</span></label>
                     <div class="input-group">
                        <input type="password" class="form-control" id="password" name="password" placeholder="Min 6 characters" required>
                        <span class="input-group-text" id="togglePassword" style="cursor:pointer;"><i class="bi bi-eye"></i></span>
                     </div>
                     <small class="text-danger error" id="password_error"></small>
                  </div>

                  <div class="col-md-3">
                     <label for="designation" class="form-label fw-semibold">Designation</label>
                     <input type="text" class="form-control" id="designation" name="designation" placeholder="e.g. Director / CEO">
                     <small class="text-danger error" id="designation_error"></small>
                  </div>

                  <div class="col-md-3">
                     <label for="department" class="form-label fw-semibold">Department</label>
                     <input type="text" class="form-control" id="department" name="department" placeholder="e.g. Executive">
                     <small class="text-danger error" id="department_error"></small>
                  </div>

                  <div class="col-md-3">
                     <label for="dob" class="form-label fw-semibold">Date of Birth</label>
                     <input type="date" class="form-control" id="dob" name="dob" onkeydown="return false" onpaste="return false" autocomplete="off">
                     <small class="text-danger error" id="dob_error"></small>
                  </div>

                  <div class="col-md-3">
                     <label for="joining_date" class="form-label fw-semibold">Joining Date</label>
                     <input type="date" class="form-control" id="joining_date" name="joining_date" value="{{ date('Y-m-d') }}" onkeydown="return false" onpaste="return false" autocomplete="off">
                     <small class="text-danger error" id="joining_date_error"></small>
                  </div>

                  <!-- 3. Subscribed Modules (Group-wise Accordion) -->
                  @if(isset($groupedModules) && $groupedModules->isNotEmpty())
                  <div class="col-12 mt-4">
                     <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div>
                           <h6 class="fw-bold text-primary mb-0"><i class="bi bi-grid me-2"></i>3. Subscribed Modules & Groups <span class="text-danger">*</span></h6>
                           <small class="text-muted d-block mt-1">Enable entire module groups or select individual modules for this tenant.</small>
                        </div>
                        <div class="d-flex gap-1">
                           <button type="button" class="btn btn-sm btn-outline-primary py-1 px-3" id="selectAllGlobal"><i class="bi bi-check-all me-1"></i>Select All</button>
                           <button type="button" class="btn btn-sm btn-outline-secondary py-1 px-3" id="deselectAllGlobal"><i class="bi bi-x-lg me-1"></i>Deselect All</button>
                        </div>
                     </div>
                     <hr class="mt-2 mb-3">
                  </div>

                  <div class="col-12">
                     <div class="row g-3">
                        @foreach($groupedModules as $groupName => $modulesList)
                        @php $groupSlug = \Illuminate\Support\Str::slug($groupName); @endphp
                        <div class="col-md-6 col-lg-4">
                           <div class="card h-100 border shadow-none" style="border-radius: 12px; background: #fdfdfe;">
                              <!-- Group Header with Master Switch -->
                              <div class="card-header bg-light border-bottom py-2 px-3 d-flex align-items-center justify-content-between">
                                 <span class="fw-bold text-dark small mb-0">{{ $groupName }}</span>
                                 <div class="form-check form-switch mb-0">
                                    <input class="form-check-input group-master-switch" type="checkbox" id="group_switch_{{ $groupSlug }}" data-group="{{ $groupSlug }}" checked title="Toggle All in this Group">
                                 </div>
                              </div>
                              <!-- Modules under Group -->
                              <div class="card-body p-2 d-flex flex-column gap-2">
                                 @foreach($modulesList as $module)
                                 <div class="p-2 border rounded bg-white d-flex align-items-center justify-content-between">
                                    <label class="form-check-label text-secondary small fw-medium mb-0" for="gridCheck{{ $module->id }}" style="cursor: pointer;">
                                       <i class="{{ $module->icon ?? 'bi bi-app' }} text-primary me-1"></i>
                                       {{ $module->name }}
                                       @if($module->short_name)
                                       <span class="badge bg-light text-muted border ms-1" style="font-size: 10px;">{{ $module->short_name }}</span>
                                       @endif
                                    </label>
                                    <div class="form-check form-switch mb-0">
                                       <input class="form-check-input module-checkbox group-item-{{ $groupSlug }}" type="checkbox" id="gridCheck{{ $module->id }}" value="{{ $module->id }}" name="modules[]" data-group="{{ $groupSlug }}" checked>
                                    </div>
                                 </div>
                                 @endforeach
                              </div>
                           </div>
                        </div>
                        @endforeach
                     </div>
                  </div>

                  <div class="col-12">
                     <small class="text-danger error" id="modules_error"></small>
                  </div>
                  @endif

                  <div class="col-12 mt-4 text-start">
                     <button type="button" class="btn btn-secondary" id="closeBtn">Cancel</button>
                     <button type="submit" class="btn btn-primary px-4" id="saveBtn">Create Company</button>
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
      // Phone Auto Formatting
      $('#phone').on('input', function() {
         let val = $(this).val().replace(/\D/g, '').substring(0, 10);
         let formatted = '';
         if (val.length > 0) formatted += val.substring(0, 3);
         if (val.length >= 4) formatted += '-' + val.substring(3, 6);
         if (val.length >= 7) formatted += '-' + val.substring(6, 10);
         $(this).val(formatted);
      });

      // Password Toggle
      $('#togglePassword').on('click', function() {
         const field = $('#password');
         const isPass = field.attr('type') === 'password';
         field.attr('type', isPass ? 'text' : 'password');
         $(this).find('i').toggleClass('bi-eye bi-eye-slash');
      });

      // Group Master Switch toggle
      $(document).on('change', '.group-master-switch', function() {
         const groupSlug = $(this).data('group');
         const isChecked = $(this).is(':checked');
         $('.group-item-' + groupSlug).prop('checked', isChecked);
      });

      // Child checkbox toggle updates Group Master Switch
      $(document).on('change', '.module-checkbox', function() {
         const groupSlug = $(this).data('group');
         const total = $('.group-item-' + groupSlug).length;
         const checkedCount = $('.group-item-' + groupSlug + ':checked').length;
         $('#group_switch_' + groupSlug).prop('checked', total === checkedCount);
      });

      // Global Select / Deselect All
      $('#selectAllGlobal').on('click', function() {
         $('.module-checkbox, .group-master-switch').prop('checked', true);
      });

      $('#deselectAllGlobal').on('click', function() {
         $('.module-checkbox, .group-master-switch').prop('checked', false);
      });

      // Clear validation errors on input
      $('input, select, textarea').on('input change', function() {
         $(this).removeClass('is-invalid');
         $('#' + $(this).attr('name') + '_error').html('');
         $('#modules_error').html(''); // Clear modules error on any change
      });

      // Helper function for Email validation
      function isValidEmail(email) {
         const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
         return regex.test(email);
      }

      // Submit & Validate
      $('#addForm').on('submit', function(e) {
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

         // Company Email (Optional but must be valid if entered)
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

         // Admin Password
         let adminPassword = $('#password').val();
         if (!adminPassword) {
            $('#password_error').text('Password is required.');
            $('#password').addClass('is-invalid');
            isValid = false;
         } else if (adminPassword.length < 6) {
            $('#password_error').text('Password must be at least 6 characters.');
            $('#password').addClass('is-invalid');
            isValid = false;
         }

         // Modules Selection
         if ($('.module-checkbox').length > 0 && $('.module-checkbox:checked').length === 0) {
            $('#modules_error').text('Please select at least one module for this company.');
            isValid = false;
         }

         // 3. Stop execution if validation fails
         if (!isValid) {
            // Screen ko smoothly waha scroll kar dega jaha pehla error aaya hai
            $('html, body').animate({
               scrollTop: $('.is-invalid').first().offset().top - 100
            }, 300);
            return false;
         }

         // 4. Proceed with AJAX Submission
         const btn = $('#saveBtn');
         btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span>Creating...');
         $('#nms-loader-overlay').addClass('active');

         const formData = new FormData(this);

         $.ajax({
            url: "{{ route('superadmin.companies.store') }}",
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
                  btn.prop('disabled', false).text('Create Company');
                  $('#nms-loader-overlay').removeClass('active');
               }
            },
            error: function(xhr) {
               btn.prop('disabled', false).text('Create Company');
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
                  alert(xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Error creating company.');
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