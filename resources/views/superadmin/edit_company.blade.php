@extends('superadmin.layouts.app')

@section('content')
<section class="section">
   <div class="row">
      <div class="col-lg-12">
         <div class="card-soft p-4 bg-white shadow-sm">
            <form id="editForm" enctype="multipart/form-data" novalidate>
               @csrf
               <input type="hidden" name="_method" value="PUT">

               <!-- 1. Household Information -->
               <div class="section-title mt-0">1. Household Information</div>

               <div class="row g-4">
                  <div class="col-md-5">
                     <div class="input-floating-wrap">
                        <input type="text" class="form-control" id="company_name" name="company_name" placeholder=" " value="{{ old('company_name', $company->company_name) }}" required>
                        <label class="form-label-joint">Household Name*</label>
                     </div>
                     <small class="text-danger error mt-1 d-block" id="company_name_error"></small>
                  </div>

                  <div class="col-md-3">
                     <div class="input-floating-wrap">
                        <input type="text" class="form-control text-uppercase" id="company_code" name="company_code" placeholder=" " value="{{ old('company_code', $company->company_code) }}" maxlength="10">
                        <label class="form-label-joint">Code / Prefix</label>
                     </div>
                     <small class="text-danger error mt-1 d-block" id="company_code_error"></small>
                  </div>

                  <div class="col-md-2">
                     <div class="input-floating-wrap">
                        <select class="form-select" id="status" name="status">
                           <option value="1" {{ $company->status == 1 ? 'selected' : '' }}>Active</option>
                           <option value="0" {{ $company->status == 0 ? 'selected' : '' }}>Inactive</option>
                        </select>
                        <label class="form-label-joint">Status*</label>
                     </div>
                  </div>

                  <div class="col-md-2">
                     <div class="input-floating-wrap">
                        <input type="file" class="form-control" id="company_logo" name="company_logo" accept="image/*">
                        <label class="form-label-joint">Update Logo</label>
                     </div>
                     <small class="text-danger error mt-1 d-block" id="company_logo_error"></small>
                  </div>

                  <div class="col-md-6">
                     <div class="input-floating-wrap">
                        <input type="text" class="form-control" id="company_address" name="company_address" placeholder=" " value="{{ old('company_address', $company->company_address) }}" required>
                        <label class="form-label-joint">Address*</label>
                     </div>
                     <small class="text-danger error mt-1 d-block" id="company_address_error"></small>
                  </div>

                  <div class="col-md-3">
                     <div class="input-floating-wrap">
                        <input type="email" class="form-control" id="company_email" name="company_email" placeholder=" " value="{{ old('company_email', $company->company_email) }}">
                        <label class="form-label-joint">Email</label>
                     </div>
                     <small class="text-danger error mt-1 d-block" id="company_email_error"></small>
                  </div>

                  <div class="col-md-3">
                     <div class="input-floating-wrap">
                        <input type="text" class="form-control" id="company_phone" name="company_phone" placeholder=" " value="{{ old('company_phone', $company->company_phone) }}">
                        <label class="form-label-joint">Phone</label>
                     </div>
                     <small class="text-danger error mt-1 d-block" id="company_phone_error"></small>
                  </div>
               </div>

               <!-- 2. Administrator Profile -->
               <div class="section-title">2. House Head / Admin Profile</div>

               <div class="row g-4">
                  <div class="col-md-4">
                     <div class="input-floating-wrap">
                        <input type="text" class="form-control" id="first_name" name="first_name" placeholder=" " value="{{ old('first_name', $user->first_name ?? '') }}" required>
                        <label class="form-label-joint">First Name*</label>
                     </div>
                     <small class="text-danger error mt-1 d-block" id="first_name_error"></small>
                  </div>

                  <div class="col-md-4">
                     <div class="input-floating-wrap">
                        <input type="text" class="form-control" id="last_name" name="last_name" placeholder=" " value="{{ old('last_name', $user->last_name ?? '') }}" required>
                        <label class="form-label-joint">Last Name*</label>
                     </div>
                     <small class="text-danger error mt-1 d-block" id="last_name_error"></small>
                  </div>

                  <div class="col-md-4">
                     <div class="input-floating-wrap">
                        <input type="email" class="form-control" id="email" name="email" placeholder=" " value="{{ old('email', $user->email ?? '') }}" required>
                        <label class="form-label-joint">Login Email*</label>
                     </div>
                     <small class="text-danger error mt-1 d-block" id="email_error"></small>
                  </div>

                  <div class="col-md-6">
                     <div class="input-floating-wrap">
                        <input type="text" class="form-control" id="phone" name="phone" placeholder=" " value="{{ old('phone', $user->phone ?? '') }}" maxlength="12" required>
                        <label class="form-label-joint">Phone Number*</label>
                     </div>
                     <small class="text-danger error mt-1 d-block" id="phone_error"></small>
                  </div>
               </div>

               <!-- Form Actions inside card -->
               <div class="d-flex justify-content-between align-items-center mt-2 pt-3">
                  <button type="button" class="btn btn-danger px-4 py-2" id="closeBtn" style="border-radius: 6px; font-weight: 600; font-size: 13px;">CANCEL</button>
                  <button type="submit" class="btn px-5 py-2" id="updateBtn" style="background-color: #334e68; color: #fff; border: none; border-radius: 6px; font-weight: 600; font-size: 13px;">UPDATE</button>
               </div>
            </form>
         </div>
      </div>
   </div>
</section>

<!-- Floating Bottom-Right View Button[cite: 6] -->
<a href="{{ route('superadmin.companies.index') }}" title="View Households" style="position: fixed; bottom: 25px; right: 25px; width: 55px; height: 55px; background-color: #ff9800; color: #fff; border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 15px rgba(255, 152, 0, 0.4); z-index: 9999; font-size: 24px; transition: transform 0.2s;">
   <i class="bi bi-eye-fill"></i>
</a>
@endsection

@section('scripts')
<script>
   $(document).ready(function() {
      function formatPhone(val) {
         val = val.replace(/\D/g, '').substring(0, 10);
         let formatted = '';
         if (val.length > 0) formatted += val.substring(0, 3);
         if (val.length >= 4) formatted += '-' + val.substring(3, 6);
         if (val.length >= 7) formatted += '-' + val.substring(6, 10);
         return formatted;
      }
      $('#phone').val(formatPhone($('#phone').val()));
      $('#phone').on('input', function() {
         $(this).val(formatPhone($(this).val()));
      });

      $('input, select, textarea').on('input change', function() {
         $(this).removeClass('is-invalid');
         $('#' + $(this).attr('name') + '_error').html('');
      });

      $('#editForm').on('submit', function(e) {
         e.preventDefault();
         let isValid = true;
         $('.error').html('');
         $('.form-control').removeClass('is-invalid');

         if (!$('#company_name').val().trim()) {
            $('#company_name_error').text('Name is required.');
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

         if (!isValid) {
            $('html, body').animate({
               scrollTop: $('.is-invalid').first().offset().top - 100
            }, 300);
            return false;
         }

         const btn = $('#updateBtn');
         btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span>UPDATING...');
         $('#nms-loader-overlay').addClass('active');

         $.ajax({
            url: "{{ route('superadmin.companies.update', $company->id) }}",
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
               btn.prop('disabled', false).text('UPDATE');
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
                  alert(xhr.responseJSON?.message || 'Error updating household.');
               }
            }
         });
      });

      $('#closeBtn').on('click', () => window.location.href = "{{ route('superadmin.companies.index') }}");
   });
</script>
@endsection