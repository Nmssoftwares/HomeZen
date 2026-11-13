@extends('superadmin.layouts.app')

@section('content')
<div class="pagetitle d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
   <div>
      <h1 class="mb-0">Add New Household</h1>
      <div class="text-muted small mt-1">Register a new household unit, configure primary admin credentials, and assign modules.</div>
   </div>
   <a href="{{ route('superadmin.companies.index') }}" class="btn btn-outline-secondary d-flex align-items-center gap-1 shadow-sm" style="border-radius: 8px; font-weight: 600;">
      <i class="bi bi-eye"></i> View Households
   </a>
</div>

<section class="section">
   <div class="row">
      <div class="col-lg-12">
         <div class="card-soft">
            <div class="card-body p-4">
               <form class="row g-3" id="addForm" enctype="multipart/form-data" novalidate>
                  @csrf
                  <!-- 1. Household Information -->
                  <div class="col-12">
                     <div class="d-flex justify-content-between align-items-center">
                        <h6 class="fw-bold text-primary mb-0"><i class="bi bi-building me-2"></i>1. Household Information</h6>
                        <span class="badge bg-primary-subtle text-primary border" style="font-size: 11px;">Tenant Details</span>
                     </div>
                     <small class="text-muted d-block mt-1">Provide the official name and location details.</small>
                     <hr class="mt-2 mb-3">
                  </div>

                  <div class="col-md-5">
                     <label for="company_name" class="form-label fw-semibold">Household Name <span class="text-danger">*</span></label>
                     <input type="text" class="form-control" id="company_name" name="company_name" placeholder="e.g. Sharma Family House" required>
                     <small class="text-danger error" id="company_name_error"></small>
                  </div>

                  <div class="col-md-3">
                     <label for="company_code" class="form-label fw-semibold">Code / Prefix</label>
                     <input type="text" class="form-control text-uppercase" id="company_code" name="company_code" placeholder="e.g. SHARMA" maxlength="10">
                     <small class="text-danger error" id="company_code_error"></small>
                  </div>

                  <div class="col-md-4">
                     <label for="company_logo" class="form-label fw-semibold">Household Logo</label>
                     <input type="file" class="form-control" id="company_logo" name="company_logo" accept="image/*">
                     <small class="text-danger error" id="company_logo_error"></small>
                  </div>

                  <div class="col-md-6">
                     <label for="company_address" class="form-label fw-semibold">Address <span class="text-danger">*</span></label>
                     <input type="text" class="form-control" id="company_address" name="company_address" placeholder="e.g. Society Name, City" required>
                     <small class="text-danger error" id="company_address_error"></small>
                  </div>

                  <div class="col-md-3">
                     <label for="company_email" class="form-label fw-semibold">Email</label>
                     <input type="email" class="form-control" id="company_email" name="company_email" placeholder="contact@family.com">
                     <small class="text-danger error" id="company_email_error"></small>
                  </div>

                  <div class="col-md-3">
                     <label for="company_phone" class="form-label fw-semibold">Phone</label>
                     <input type="text" class="form-control" id="company_phone" name="company_phone" placeholder="Support Phone">
                     <small class="text-danger error" id="company_phone_error"></small>
                  </div>

                  <!-- 2. Administrator Profile -->
                  <div class="col-12 mt-4">
                     <div class="d-flex justify-content-between align-items-center">
                        <h6 class="fw-bold text-primary mb-0"><i class="bi bi-person-badge me-2"></i>2. House Head / Admin Profile</h6>
                        <span class="badge bg-light text-muted border" style="font-size: 11px;">Primary Login</span>
                     </div>
                     <small class="text-muted d-block mt-1">This user will manage household expenses, milk tracking, and members.</small>
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
                     <label for="email" class="form-label fw-semibold">Login Email <span class="text-danger">*</span></label>
                     <input type="email" class="form-control" id="email" name="email" placeholder="admin@homezen.com" required>
                     <small class="text-danger error" id="email_error"></small>
                  </div>

                  <div class="col-md-6">
                     <label for="phone" class="form-label fw-semibold">Phone Number <span class="text-danger">*</span></label>
                     <input type="text" class="form-control" id="phone" name="phone" maxlength="12" placeholder="000-000-0000" required>
                     <small class="text-danger error" id="phone_error"></small>
                  </div>

                  <div class="col-md-6">
                     <label for="password" class="form-label fw-semibold">Default Password <span class="text-danger">*</span></label>
                     <div class="input-group">
                        <input type="password" class="form-control" id="password" name="password" placeholder="Min 6 characters" required>
                        <span class="input-group-text" id="togglePassword" style="cursor:pointer;"><i class="bi bi-eye"></i></span>
                     </div>
                     <small class="text-danger error" id="password_error"></small>
                  </div>

                  <!-- 3. Subscribed Modules -->
                  @if(isset($groupedModules) && $groupedModules->isNotEmpty())
                  <div class="col-12 mt-4">
                     <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div>
                           <h6 class="fw-bold text-primary mb-0"><i class="bi bi-grid me-2"></i>3. Subscribed Modules <span class="text-danger">*</span></h6>
                           <small class="text-muted d-block mt-1">Enable functional modules for this household.</small>
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
                              <div class="card-header bg-light border-bottom py-2 px-3 d-flex align-items-center justify-content-between">
                                 <span class="fw-bold text-dark small mb-0">{{ $groupName }}</span>
                                 <div class="form-check form-switch mb-0">
                                    <input class="form-check-input group-master-switch" type="checkbox" id="group_switch_{{ $groupSlug }}" data-group="{{ $groupSlug }}" checked>
                                 </div>
                              </div>
                              <div class="card-body p-2 d-flex flex-column gap-2">
                                 @foreach($modulesList as $module)
                                 <div class="p-2 border rounded bg-white d-flex align-items-center justify-content-between">
                                    <label class="form-check-label text-secondary small fw-medium mb-0" for="gridCheck{{ $module->id }}" style="cursor: pointer;">
                                       <i class="{{ $module->icon ?? 'bi bi-app' }} text-primary me-1"></i>
                                       {{ $module->name }}
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
                  <div class="col-12"><small class="text-danger error" id="modules_error"></small></div>
                  @endif

                  <div class="col-12 mt-4 text-start">
                     <button type="button" class="btn btn-secondary" id="closeBtn">Cancel</button>
                     <button type="submit" class="btn btn-primary px-4" id="saveBtn">Create Household</button>
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
      $('#phone').on('input', function() {
         let val = $(this).val().replace(/\D/g, '').substring(0, 10);
         let formatted = '';
         if (val.length > 0) formatted += val.substring(0, 3);
         if (val.length >= 4) formatted += '-' + val.substring(3, 6);
         if (val.length >= 7) formatted += '-' + val.substring(6, 10);
         $(this).val(formatted);
      });

      $('#togglePassword').on('click', function() {
         const field = $('#password');
         const isPass = field.attr('type') === 'password';
         field.attr('type', isPass ? 'text' : 'password');
         $(this).find('i').toggleClass('bi-eye bi-eye-slash');
      });

      $(document).on('change', '.group-master-switch', function() {
         const groupSlug = $(this).data('group');
         $('.group-item-' + groupSlug).prop('checked', $(this).is(':checked'));
      });

      $(document).on('change', '.module-checkbox', function() {
         const groupSlug = $(this).data('group');
         const total = $('.group-item-' + groupSlug).length;
         const checkedCount = $('.group-item-' + groupSlug + ':checked').length;
         $('#group_switch_' + groupSlug).prop('checked', total === checkedCount);
      });

      $('#selectAllGlobal').on('click', () => $('.module-checkbox, .group-master-switch').prop('checked', true));
      $('#deselectAllGlobal').on('click', () => $('.module-checkbox, .group-master-switch').prop('checked', false));

      $('input, select, textarea').on('input change', function() {
         $(this).removeClass('is-invalid');
         $('#' + $(this).attr('name') + '_error').html('');
         $('#modules_error').html('');
      });

      $('#addForm').on('submit', function(e) {
         e.preventDefault();
         let isValid = true;
         $('.error').html('');
         $('.form-control').removeClass('is-invalid');

         if (!$('#company_name').val().trim()) {
            $('#company_name_error').text('Household name is required.');
            $('#company_name').addClass('is-invalid');
            isValid = false;
         }
         if (!$('#company_address').val().trim()) {
            $('#company_address_error').text('Address is required.');
            $('#company_address').addClass('is-invalid');
            isValid = false;
         }
         if (!$('#first_name').val().trim()) {
            $('#first_name_error').text('First name is required.');
            $('#first_name').addClass('is-invalid');
            isValid = false;
         }
         if (!$('#last_name').val().trim()) {
            $('#last_name_error').text('Last name is required.');
            $('#last_name').addClass('is-invalid');
            isValid = false;
         }
         if (!$('#email').val().trim()) {
            $('#email_error').text('Email is required.');
            $('#email').addClass('is-invalid');
            isValid = false;
         }
         if (!$('#phone').val().replace(/\D/g, '')) {
            $('#phone_error').text('Phone is required.');
            $('#phone').addClass('is-invalid');
            isValid = false;
         }
         if (!$('#password').val() || $('#password').val().length < 6) {
            $('#password_error').text('Password must be at least 6 characters.');
            $('#password').addClass('is-invalid');
            isValid = false;
         }
         if ($('.module-checkbox').length > 0 && $('.module-checkbox:checked').length === 0) {
            $('#modules_error').text('Please select at least one module.');
            isValid = false;
         }

         if (!isValid) {
            $('html, body').animate({
               scrollTop: $('.is-invalid').first().offset().top - 100
            }, 300);
            return false;
         }

         const btn = $('#saveBtn');
         btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span>Creating...');
         $('#nms-loader-overlay').addClass('active');

         $.ajax({
            url: "{{ route('superadmin.companies.store') }}",
            type: "POST",
            data: new FormData(this),
            processData: false,
            contentType: false,
            success: function(response) {
               if (response.status === 'success') {
                  if (typeof showAlert === 'function') showAlert('success', response.message);
                  setTimeout(() => window.location.href = "{{ route('superadmin.companies.index') }}", 1000);
               }
            },
            error: function(xhr) {
               btn.prop('disabled', false).text('Create Household');
               $('#nms-loader-overlay').removeClass('active');
               if (xhr.status === 422 && xhr.responseJSON.errors) {
                  $.each(xhr.responseJSON.errors, function(key, val) {
                     $('#' + key + '_error').html(val[0]);
                     $('#' + key).addClass('is-invalid');
                  });
               } else {
                  alert(xhr.responseJSON?.message || 'Error creating household.');
               }
            }
         });
      });

      $('#closeBtn').on('click', () => window.location.href = "{{ route('superadmin.companies.index') }}");
   });
</script>
@endsection