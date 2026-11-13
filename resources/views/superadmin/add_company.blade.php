@extends('superadmin.layouts.app')

@section('content')
<section class="section">
   <div class="row">
      <div class="col-lg-12">
         <div class="card-soft p-4 bg-white shadow-sm">
            <form id="addForm" enctype="multipart/form-data" novalidate>
               @csrf

               <!-- Form Grid Inputs -->
               <div class="section-title mt-0">1. Household Information</div>

               <div class="row g-4">
                  <div class="col-md-4">
                     <div class="input-floating-wrap">
                        <input type="text" class="form-control" id="company_name" name="company_name" placeholder=" " required>
                        <label class="form-label-joint">Household Name*</label>
                     </div>
                     <small class="text-danger error mt-1 d-block" id="company_name_error"></small>
                  </div>

                  <div class="col-md-4">
                     <div class="input-floating-wrap">
                        <input type="text" class="form-control text-uppercase" id="company_code" name="company_code" placeholder=" " maxlength="10">
                        <label class="form-label-joint">Company Code / Prefix</label>
                     </div>
                     <small class="text-danger error mt-1 d-block" id="company_code_error"></small>
                  </div>

                  <div class="col-md-4">
                     <div class="input-floating-wrap">
                        <input type="file" class="form-control" id="company_logo" name="company_logo" accept="image/*">
                        <label class="form-label-joint">Household Logo</label>
                     </div>
                     <small class="text-danger error mt-1 d-block" id="company_logo_error"></small>
                  </div>

                  <div class="col-md-6">
                     <div class="input-floating-wrap">
                        <input type="text" class="form-control" id="company_address" name="company_address" placeholder=" " required>
                        <label class="form-label-joint">Address*</label>
                     </div>
                     <small class="text-danger error mt-1 d-block" id="company_address_error"></small>
                  </div>

                  <div class="col-md-3">
                     <div class="input-floating-wrap">
                        <input type="email" class="form-control" id="company_email" name="company_email" placeholder=" ">
                        <label class="form-label-joint">Official Email</label>
                     </div>
                     <small class="text-danger error mt-1 d-block" id="company_email_error"></small>
                  </div>

                  <div class="col-md-3">
                     <div class="input-floating-wrap">
                        <input type="text" class="form-control" id="company_phone" name="company_phone" placeholder=" ">
                        <label class="form-label-joint">Official Phone</label>
                     </div>
                     <small class="text-danger error mt-1 d-block" id="company_phone_error"></small>
                  </div>
               </div>

                  <!-- 2. Administrator Profile -->
               <div class="section-title">2. House Head / Admin Profile</div>
               <div class="row g-4">
                  <div class="col-md-4">
                     <div class="input-floating-wrap">
                        <input type="text" class="form-control" id="first_name" name="first_name" placeholder=" " required>
                        <label class="form-label-joint">First Name*</label>
                     </div>
                     <small class="text-danger error mt-1 d-block" id="first_name_error"></small>
                  </div>

                  <div class="col-md-4">
                     <div class="input-floating-wrap">
                        <input type="text" class="form-control" id="last_name" name="last_name" placeholder=" " required>
                        <label class="form-label-joint">Last Name*</label>
                     </div>
                     <small class="text-danger error mt-1 d-block" id="last_name_error"></small>
                  </div>

                  <div class="col-md-4">
                     <div class="input-floating-wrap">
                        <input type="email" class="form-control" id="email" name="email" placeholder=" " required>
                        <label class="form-label-joint">Email Id (Login Id)*</label>
                     </div>
                     <small class="text-danger error mt-1 d-block" id="email_error"></small>
                  </div>

                  <div class="col-md-4">
                     <div class="input-floating-wrap">
                        <input type="text" class="form-control" id="phone" name="phone" maxlength="12" placeholder=" " required>
                        <label class="form-label-joint">Contact Number*</label>
                     </div>
                     <small class="text-danger error mt-1 d-block" id="phone_error"></small>
                  </div>

                  <div class="col-md-4">
                     <div class="input-floating-wrap">
                        <input type="password" class="form-control" id="password" name="password" placeholder=" " required>
                        <label class="form-label-joint">Password*</label>
                     </div>
                     <small class="text-danger error mt-1 d-block" id="password_error"></small>
                  </div>

                  <div class="col-md-4">
                     <div class="input-floating-wrap">
                        <input type="text" class="form-control text-uppercase" id="employee_code" name="employee_code" placeholder=" ">
                        <label class="form-label-joint">Employee Code / ID</label>
                     </div>
                     <small class="text-danger error mt-1 d-block" id="employee_code_error"></small>
                  </div>
               </div>

               <!-- Assign Modules Section -->
               @if(isset($groupedModules) && $groupedModules->isNotEmpty())
               <div class="section-title mt-4">3. Assign Modules</div>

               <div class="row g-4">
                  @foreach($groupedModules as $groupName => $modulesList)
                  <div class="col-md-4">
                     <div class="module-group-box">
                        <div class="module-group-header fw-semibold mb-2 text-dark">
                           <span>{{ $loop->iteration }}. {{ $groupName }}</span>
                        </div>
                        <div class="module-item-list d-flex flex-column gap-2">
                           @foreach($modulesList as $module)
                           <div class="form-check">
                              <input class="form-check-input module-checkbox" type="checkbox" id="gridCheck{{ $module->id }}" value="{{ $module->id }}" name="modules[]" checked>
                              <label class="form-check-label" for="gridCheck{{ $module->id }}">
                                 {{ $module->name }}
                              </label>
                           </div>
                           @endforeach
                        </div>
                     </div>
                  </div>
                  @endforeach
               </div>
               <div class="col-12 mt-2"><small class="text-danger error" id="modules_error"></small></div>
               @endif

               <!-- Form Actions (Clear & Save) inside card -->
               <div class="d-flex justify-content-between align-items-center mt-2 pt-3">
                  <button type="button" class="btn btn-danger px-4 py-2" id="closeBtn">CLEAR</button>
                  <button type="submit" class="btn px-5 py-2" id="saveBtn" style="background-color: #334e68; color: #fff; border: none; border-radius: 6px; font-weight: 600; font-size: 13px;">SAVE</button>
               </div>
            </form>
         </div>
      </div>
   </div>
</section>

<!-- Floating Bottom-Right View Button (Reference Image style)[cite: 6] -->
<a href="{{ route('superadmin.companies.index') }}" title="View Households" style="position: fixed; bottom: 25px; right: 25px; width: 55px; height: 55px; background-color: #ff9800; color: #fff; border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 15px rgba(255, 152, 0, 0.4); z-index: 9999; font-size: 24px; transition: transform 0.2s;">
   <i class="bi bi-eye-fill"></i>
</a>
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
         btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span>SAVING...');
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
               btn.prop('disabled', false).text('SAVE');
               $('#nms-loader-overlay').removeClass('active');
               if (xhr.status === 422 && xhr.responseJSON.errors) {
                  $.each(xhr.responseJSON.errors, function(key, val) {
                     $('#' + key + '_error').html(val[0]);
                     $('#' + key).addClass('is-invalid');
                  });
                  $('html, body').animate({
                     scrollTop: $('.is-invalid').first().offset().top - 100
                  }, 300);
               } else {
                  alert(xhr.responseJSON?.message || 'Error creating household.');
               }
            }
         });
      });

      $('#closeBtn').on('click', () => $('#addForm')[0].reset());
   });
</script>
@endsection