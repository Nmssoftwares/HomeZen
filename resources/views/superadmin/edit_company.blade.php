@extends('superadmin.layouts.app')

@section('content')
<div class="pagetitle d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
   <div>
      <h1 class="mb-0">Edit Household</h1>
      <div class="text-muted small mt-1">Update household profile, settings, and primary administrator details.</div>
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
               <form class="row g-3" id="editForm" enctype="multipart/form-data" novalidate>
                  @csrf
                  <input type="hidden" name="_method" value="PUT">

                  <!-- 1. Household Information -->
                  <div class="col-12">
                     <div class="d-flex justify-content-between align-items-center">
                        <h6 class="fw-bold text-primary mb-0"><i class="bi bi-building me-2"></i>1. Household Information</h6>
                        <span class="badge bg-primary-subtle text-primary border" style="font-size: 11px;">Tenant Details</span>
                     </div>
                     <hr class="mt-2 mb-3">
                  </div>

                  <div class="col-md-5">
                     <label for="company_name" class="form-label fw-semibold">Household Name <span class="text-danger">*</span></label>
                     <input type="text" class="form-control" id="company_name" name="company_name" value="{{ old('company_name', $company->company_name) }}" required>
                     <small class="text-danger error" id="company_name_error"></small>
                  </div>

                  <div class="col-md-3">
                     <label for="company_code" class="form-label fw-semibold">Code / Prefix</label>
                     <input type="text" class="form-control text-uppercase" id="company_code" name="company_code" value="{{ old('company_code', $company->company_code) }}" maxlength="10">
                     <small class="text-danger error" id="company_code_error"></small>
                  </div>

                  <div class="col-md-2">
                     <label for="status" class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                     <select class="form-select" id="status" name="status">
                        <option value="1" {{ $company->status == 1 ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ $company->status == 0 ? 'selected' : '' }}>Inactive</option>
                     </select>
                  </div>

                  <div class="col-md-2">
                     <label for="company_logo" class="form-label fw-semibold">Update Logo</label>
                     <input type="file" class="form-control" id="company_logo" name="company_logo" accept="image/*">
                  </div>

                  <div class="col-md-6">
                     <label for="company_address" class="form-label fw-semibold">Address <span class="text-danger">*</span></label>
                     <input type="text" class="form-control" id="company_address" name="company_address" value="{{ old('company_address', $company->company_address) }}" required>
                     <small class="text-danger error" id="company_address_error"></small>
                  </div>

                  <div class="col-md-3">
                     <label for="company_email" class="form-label fw-semibold">Email</label>
                     <input type="email" class="form-control" id="company_email" name="company_email" value="{{ old('company_email', $company->company_email) }}">
                     <small class="text-danger error" id="company_email_error"></small>
                  </div>

                  <div class="col-md-3">
                     <label for="company_phone" class="form-label fw-semibold">Phone</label>
                     <input type="text" class="form-control" id="company_phone" name="company_phone" value="{{ old('company_phone', $company->company_phone) }}">
                     <small class="text-danger error" id="company_phone_error"></small>
                  </div>

                  <!-- 2. Administrator Profile -->
                  <div class="col-12 mt-4">
                     <div class="d-flex justify-content-between align-items-center">
                        <h6 class="fw-bold text-primary mb-0"><i class="bi bi-person-badge me-2"></i>2. House Head / Admin Profile</h6>
                     </div>
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
                     <label for="email" class="form-label fw-semibold">Login Email <span class="text-danger">*</span></label>
                     <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email ?? '') }}" required>
                     <small class="text-danger error" id="email_error"></small>
                  </div>

                  <div class="col-md-6">
                     <label for="phone" class="form-label fw-semibold">Phone Number <span class="text-danger">*</span></label>
                     <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone', $user->phone ?? '') }}" maxlength="12" required>
                     <small class="text-danger error" id="phone_error"></small>
                  </div>

                  <div class="col-12 mt-4 text-start">
                     <button type="button" class="btn btn-secondary me-2" id="closeBtn">Cancel</button>
                     <button type="submit" class="btn btn-primary px-4" id="updateBtn">Update Household</button>
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
         btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span>Updating...');
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
               btn.prop('disabled', false).text('Update Household');
               $('#nms-loader-overlay').removeClass('active');
               if (xhr.status === 422 && xhr.responseJSON.errors) {
                  $.each(xhr.responseJSON.errors, function(key, val) {
                     $('#' + key + '_error').html(val[0]);
                     $('#' + key).addClass('is-invalid');
                  });
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