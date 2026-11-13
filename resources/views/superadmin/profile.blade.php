@extends('superadmin.layouts.app')

@section('styles')
<link rel="stylesheet" href="{{ asset('assets/css/superadmin/profile.css') }}">
<style>
   /* Instant Avatar Edit Badge Styling */
   .profile-avatar-center {
      position: relative;
      display: inline-block;
   }

   .avatar-edit-badge {
      position: absolute;
      bottom: 4px;
      right: 4px;
      width: 34px;
      height: 34px;
      background: #4f46e5;
      color: #ffffff;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      border: 2px solid #ffffff;
      box-shadow: 0 4px 10px rgba(79, 70, 229, 0.3);
      transition: all 0.2s ease;
   }

   .avatar-edit-badge:hover {
      background: #4338ca;
      transform: scale(1.1);
   }
</style>
@endsection

@section('content')
<!-- Standalone Hidden File Input for Auto-Upload -->
<input type="file" id="profile_image_input" class="d-none" accept="image/png, image/jpeg, image/jpg, image/webp">

<div class="pagetitle d-flex justify-content-between align-items-center mb-3">
   <div>
      <h1 class="mb-0">{{ __('messages.profile') ?? 'User Profile' }}</h1>
      <div class="text-muted small mt-1">Manage your account credentials, employment details, and login security.</div>
   </div>
</div>

<section class="section profile">
   <div class="row g-3">
      <!-- LEFT HERO PROFILE CARD -->
      <div class="col-xl-4">
         <div class="profile-card-container text-center card-soft">
            <!-- Banner -->
            <div class="profile-header-banner"></div>

            <!-- Avatar with Camera Badge -->
            <div class="profile-avatar-center">
               <div id="avatarContainerInner">
                  @if(!empty($userInfo->profile_image) && file_exists(public_path($userInfo->profile_image)))
                  <img src="{{ asset($userInfo->profile_image) }}" alt="Avatar" class="profile-avatar-img" id="userAvatarPreview">
                  @else
                  <div class="profile-avatar-circle" id="userAvatarPlaceholder">
                     {{ strtoupper(substr($userInfo->first_name, 0, 1)) }}{{ strtoupper(substr($userInfo->last_name, 0, 1)) }}
                  </div>
                  @endif
               </div>

               <label for="profile_image_input" class="avatar-edit-badge" title="Upload new photo">
                  <i class="bi bi-camera-fill small"></i>
               </label>
            </div>

            <div class="p-3 pt-2">
               <h5 class="fw-bold text-dark mb-0">{{ $userInfo->first_name }} {{ $userInfo->last_name }}</h5>
               <div class="text-muted small mt-1">{{ $userInfo->designation ?: 'System User' }}</div>

               <!-- Role Pill -->
               <div class="mt-2">
                  <span class="profile-role-pill">
                     <i class="bi bi-shield-check"></i>
                     {{ $userInfo->role == 1 ? 'Super Admin' : ($userInfo->role == 2 ? 'Company Admin' : 'Employee') }}
                  </span>
               </div>

               <!-- Meta Grid -->
               <div class="profile-meta-grid">
                  <div class="profile-meta-item">
                     <div class="meta-lbl">Company</div>
                     <div class="meta-val" title="{{ $userInfo->company->company_name ?? 'NMS Office' }}">
                        {{ $userInfo->company->company_name ?? 'NMS Office' }}
                     </div>
                  </div>
                  <div class="profile-meta-item">
                     <div class="meta-lbl">Department</div>
                     <div class="meta-val" title="{{ $userInfo->department ?: 'General' }}">
                        {{ $userInfo->department ?: 'General' }}
                     </div>
                  </div>
               </div>

               <hr class="my-2" style="border-color: #edf0f7;">

               <!-- Contact Information -->
               <div class="profile-contact-list">
                  <div class="profile-contact-row">
                     <span class="profile-contact-icon"><i class="bi bi-envelope"></i></span>
                     <span class="text-dark fw-semibold text-truncate">{{ $userInfo->email }}</span>
                  </div>
                  <div class="profile-contact-row">
                     <span class="profile-contact-icon"><i class="bi bi-telephone"></i></span>
                     <span class="text-dark fw-semibold">{{ $userInfo->phone ?: '-' }}</span>
                  </div>
                  <div class="profile-contact-row">
                     <span class="profile-contact-icon"><i class="bi bi-calendar-check"></i></span>
                     <span class="text-dark">Joined: <b>{{ $userInfo->joining_date ? \Carbon\Carbon::parse($userInfo->joining_date)->format('d M, Y') : '-' }}</b></span>
                  </div>
               </div>

               <div id="removePhotoWrapper" class="mt-2 pt-2 border-top {{ empty($userInfo->profile_image) || !file_exists(public_path($userInfo->profile_image)) ? 'd-none' : '' }}">
                  <button type="button" id="removeImageBtn" class="btn btn-outline-danger btn-sm w-100 rounded-pill" style="font-size:12px;">
                     <i class="bi bi-trash me-1"></i> Remove Avatar Photo
                  </button>
               </div>
            </div>
         </div>
      </div>

      <!-- RIGHT TABBED DETAILS -->
      <div class="col-xl-8">
         <div class="card-soft h-100 p-4">
            <!-- Nav Pills -->
            <ul class="nav profile-nav-pills mb-4" role="tablist">
               <li class="nav-item">
                  <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-overview">
                     <i class="bi bi-person-lines-fill me-1"></i> {{ __('messages.overview') ?? 'Overview' }}
                  </button>
               </li>
               <li class="nav-item">
                  <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-edit">
                     <i class="bi bi-pencil-square me-1"></i> {{ __('messages.edit_profile') ?? 'Edit Profile' }}
                  </button>
               </li>
               <li class="nav-item">
                  <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-password">
                     <i class="bi bi-shield-lock me-1"></i> Security & Password
                  </button>
               </li>
            </ul>

            <div class="tab-content">
               <!-- 1. OVERVIEW TAB -->
               <div class="tab-pane fade show active" id="tab-overview">
                  <h6 class="fw-bold text-dark mb-3"><i class="bi bi-info-circle text-primary me-2"></i>Account & Employment Profile</h6>

                  <div class="row g-3">
                     <div class="col-md-6">
                        <div class="info-tile">
                           <div class="info-tile-label">First Name</div>
                           <div class="info-tile-val">{{ $userInfo->first_name }}</div>
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="info-tile">
                           <div class="info-tile-label">Last Name</div>
                           <div class="info-tile-val">{{ $userInfo->last_name }}</div>
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="info-tile">
                           <div class="info-tile-label">Email Address</div>
                           <div class="info-tile-val">{{ $userInfo->email }}</div>
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="info-tile">
                           <div class="info-tile-label">Phone Number</div>
                           <div class="info-tile-val">{{ $userInfo->phone ?: '-' }}</div>
                        </div>
                     </div>
                     <div class="col-md-4">
                        <div class="info-tile">
                           <div class="info-tile-label">Employee Code</div>
                           <div class="info-tile-val">{{ $userInfo->employee_code ?: '-' }}</div>
                        </div>
                     </div>
                     <div class="col-md-4">
                        <div class="info-tile">
                           <div class="info-tile-label">Designation</div>
                           <div class="info-tile-val">{{ $userInfo->designation ?: '-' }}</div>
                        </div>
                     </div>
                     <div class="col-md-4">
                        <div class="info-tile">
                           <div class="info-tile-label">Department</div>
                           <div class="info-tile-val">{{ $userInfo->department ?: '-' }}</div>
                        </div>
                     </div>
                     <div class="col-md-4">
                        <div class="info-tile">
                           <div class="info-tile-label">Date of Birth</div>
                           <div class="info-tile-val">{{ $userInfo->dob ? \Carbon\Carbon::parse($userInfo->dob)->format('d M, Y') : '-' }}</div>
                        </div>
                     </div>
                     <div class="col-md-4">
                        <div class="info-tile">
                           <div class="info-tile-label">Joining Date</div>
                           <div class="info-tile-val">{{ $userInfo->joining_date ? \Carbon\Carbon::parse($userInfo->joining_date)->format('d M, Y') : '-' }}</div>
                        </div>
                     </div>
                     <div class="col-md-4">
                        <div class="info-tile">
                           <div class="info-tile-label">Gender</div>
                           <div class="info-tile-val">
                              {{ $userInfo->gender == 1 ? 'Male' : ($userInfo->gender == 2 ? 'Female' : ($userInfo->gender == 3 ? 'Other' : '-')) }}
                           </div>
                        </div>
                     </div>
                  </div>
               </div>

               <!-- 2. EDIT PROFILE TAB -->
               <div class="tab-pane fade" id="tab-edit">
                  <!-- novalidate add kiya gaya hai -->
                  <form id="profileForm" class="row g-3" novalidate>
                     @csrf
                     <div class="col-md-6">
                        <label for="first_name" class="form-label fw-semibold small">First Name <span class="text-danger">*</span></label>
                        <input name="first_name" type="text" class="form-control" id="first_name" value="{{ $userInfo->first_name }}" required>
                        <small class="text-danger error" id="first_name_error"></small>
                     </div>

                     <div class="col-md-6">
                        <label for="last_name" class="form-label fw-semibold small">Last Name <span class="text-danger">*</span></label>
                        <input name="last_name" type="text" class="form-control" id="last_name" value="{{ $userInfo->last_name }}" required>
                        <small class="text-danger error" id="last_name_error"></small>
                     </div>

                     <div class="col-md-6">
                        <label for="email" class="form-label fw-semibold small">Email Address <span class="text-danger">*</span></label>
                        <input name="email" type="email" class="form-control" id="email" value="{{ $userInfo->email }}" required>
                        <small class="text-danger error" id="email_error"></small>
                     </div>

                     <div class="col-md-6">
                        <label for="phone" class="form-label fw-semibold small">Phone Number <span class="text-danger">*</span></label>
                        <input name="phone" type="text" class="form-control" id="phone" value="{{ $userInfo->phone }}" placeholder="000-000-0000" maxlength="12" required>
                        <small class="text-danger error" id="phone_error"></small>
                     </div>

                     <div class="col-md-4">
                        <label for="employee_code" class="form-label fw-semibold small">Employee Code</label>
                        <input name="employee_code" type="text" class="form-control text-uppercase" id="employee_code" value="{{ $userInfo->employee_code }}">
                     </div>

                     <div class="col-md-4">
                        <label for="designation" class="form-label fw-semibold small">Designation</label>
                        <input name="designation" type="text" class="form-control" id="designation" value="{{ $userInfo->designation }}">
                     </div>

                     <div class="col-md-4">
                        <label for="department" class="form-label fw-semibold small">Department</label>
                        <input name="department" type="text" class="form-control" id="department" value="{{ $userInfo->department }}">
                     </div>

                     <div class="col-md-4">
                        <label for="dob" class="form-label fw-semibold small">Date of Birth</label>
                        <input name="dob" type="date" class="form-control" id="dob" value="{{ $userInfo->dob }}" onkeydown="return false" onpaste="return false" autocomplete="off">
                     </div>

                     <div class="col-md-4">
                        <label for="gender" class="form-label fw-semibold small">Gender</label>
                        <select class="form-select" id="gender" name="gender">
                           <option value="">Select gender</option>
                           <option value="1" {{ $userInfo->gender == 1 ? 'selected' : '' }}>Male</option>
                           <option value="2" {{ $userInfo->gender == 2 ? 'selected' : '' }}>Female</option>
                           <option value="3" {{ $userInfo->gender == 3 ? 'selected' : '' }}>Other</option>
                        </select>
                     </div>

                     <div class="col-12 text-end mt-4">
                        <button type="submit" class="btn btn-primary px-4 shadow-sm" id="saveProfile" style="border-radius:10px; font-weight:600;">
                           Save Changes
                        </button>
                     </div>
                  </form>
               </div>

               <!-- 3. SECURITY & PASSWORD TAB -->
               <div class="tab-pane fade" id="tab-password">
                  <div class="alert alert-light border small text-muted mb-4">
                     <i class="bi bi-shield-check text-success me-2"></i> As a Super Admin, you can directly reset your password. Ensure it is at least 6 characters.
                  </div>

                  <form id="passwordForm" class="row g-3" novalidate>
                     @csrf

                     <!-- Current Password Removed from here -->

                     <div class="col-md-6">
                        <label for="password" class="form-label fw-semibold small">New Password <span class="text-danger">*</span></label>
                        <div class="input-group">
                           <input name="password" type="password" class="form-control password-input" id="password" placeholder="Minimum 6 characters" required>
                           <span class="input-group-text bg-light toggle-password" data-target="#password" style="cursor:pointer;">
                              <i class="bi bi-eye"></i>
                           </span>
                        </div>
                        <!-- Password Strength Meter -->
                        <div class="strength-meter mt-2" style="height: 4px; border-radius: 4px; background: #e2e8f0; overflow: hidden;">
                           <div class="strength-meter-fill" id="strengthBar" style="height: 100%; width: 0%; transition: width 0.3s ease, background-color 0.3s ease;"></div>
                        </div>
                        <small class="text-danger error" id="password_error"></small>
                     </div>

                     <div class="col-md-6">
                        <label for="confirm_password" class="form-label fw-semibold small">Confirm New Password <span class="text-danger">*</span></label>
                        <div class="input-group">
                           <input name="confirm_password" type="password" class="form-control password-input" id="confirm_password" placeholder="Re-type new password" required>
                           <span class="input-group-text bg-light toggle-password" data-target="#confirm_password" style="cursor:pointer;">
                              <i class="bi bi-eye"></i>
                           </span>
                        </div>
                        <small class="text-danger error" id="confirm_password_error"></small>
                     </div>

                     <div class="col-12 text-end mt-4">
                        <button type="submit" class="btn btn-primary px-4 shadow-sm" id="savePassword" style="border-radius:10px; font-weight:600;">
                           Update Password
                        </button>
                     </div>
                  </form>
               </div>
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

      // 1. Phone Number Auto-Formatter
      function formatPhoneNumber(val) {
         val = val.replace(/\D/g, '').substring(0, 10);
         let formatted = '';
         if (val.length > 0) formatted += val.substring(0, 3);
         if (val.length >= 4) formatted += '-' + val.substring(3, 6);
         if (val.length >= 7) formatted += '-' + val.substring(6, 10);
         return formatted;
      }

      let $phone = $('#phone');
      if ($phone.length) {
         $phone.val(formatPhoneNumber($phone.val()));
         $phone.on('input', function() {
            $(this).val(formatPhoneNumber($(this).val()));
         });
      }

      // Toggle Password Visibility (Eye Icon)
      $(document).on('click', '.toggle-password', function() {
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

      // Clear Validation errors on input change
      $('input, select, textarea').on('input change', function() {
         $(this).removeClass('is-invalid');
         $('#' + $(this).attr('id') + '_error').html('');
      });

      // 2. Instant Profile Image Auto-Upload & Top-Bar Sync
      $('#profile_image_input').on('change', function() {
         let file = this.files[0];
         if (!file) return;

         if (file.size > 2 * 1024 * 1024) {
            if (typeof showAlert === 'function') showAlert('danger', 'Photo size must be less than 2MB.');
            else alert('Photo size must be less than 2MB.');
            $(this).val('');
            return;
         }

         let formData = new FormData();
         formData.append('profile_image', file);
         formData.append('_token', "{{ csrf_token() }}");

         $('#nms-loader-overlay').addClass('active');

         $.ajax({
            url: "{{ route('superadmin.profile.image.upload') }}",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function(res) {
               $('#nms-loader-overlay').removeClass('active');
               if (res.status === 'success') {
                  if (typeof showAlert === 'function') showAlert('success', res.message);

                  let reader = new FileReader();
                  reader.onload = function(evt) {
                     let imgSrc = evt.target.result;
                     $('#avatarContainerInner').html(`<img src="${imgSrc}" alt="Avatar" class="profile-avatar-img" id="userAvatarPreview">`);
                     $('#headerAvatarWrap').html(`<img src="${imgSrc}" alt="Profile" class="rounded-circle header-profile-img" style="width:36px;height:36px;object-fit:cover;">`);
                  };
                  reader.readAsDataURL(file);

                  $('#removePhotoWrapper').removeClass('d-none');
               } else {
                  if (typeof showAlert === 'function') showAlert('danger', res.message || 'Something went wrong.');
               }
            },
            error: function(xhr) {
               $('#nms-loader-overlay').removeClass('active');
               if (xhr.status === 422 && xhr.responseJSON.errors && xhr.responseJSON.errors.profile_image) {
                  if (typeof showAlert === 'function') showAlert('danger', xhr.responseJSON.errors.profile_image[0]);
                  else alert(xhr.responseJSON.errors.profile_image[0]);
               } else {
                  if (typeof showAlert === 'function') showAlert('danger', 'Failed to upload photo.');
                  else alert('Failed to upload photo.');
               }
            }
         });
      });

      // 3. Profile Information Save (With JS Validation)
      $('#profileForm').on('submit', function(e) {
         e.preventDefault();

         // Reset Errors
         let isValid = true;
         $('#profileForm .error').html('');
         $('#profileForm .form-control').removeClass('is-invalid');

         // Client-Side Validation
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

         if (!isValid) return;

         const btn = $('#saveProfile');
         btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span>Saving...');
         $('#nms-loader-overlay').addClass('active');
         const formData = new FormData(this);

         $.ajax({
            url: "{{ route('superadmin.profile.update') }}",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
               btn.prop('disabled', false).text('Save Changes');
               $('#nms-loader-overlay').removeClass('active');
               if (response.status === 'success') {
                  if (typeof showAlert === 'function') showAlert('success', response.message);
                  setTimeout(() => {
                     window.location.href = "{{ route('superadmin.profile') }}";
                  }, 800);
               }
            },
            error: function(xhr) {
               btn.prop('disabled', false).text('Save Changes');
               $('#nms-loader-overlay').removeClass('active');
               if (xhr.status === 422 && xhr.responseJSON.errors) {
                  $.each(xhr.responseJSON.errors, function(key, val) {
                     $('#' + key + '_error').html(val[0]);
                     $('#' + key).addClass('is-invalid');
                  });
               } else {
                  alert(xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Error updating profile.');
               }
            }
         });
      });

      // 4. Password Strength Indicator Logic
      $('#password').on('input', function() {
         let val = $(this).val();
         let $bar = $('#strengthBar');
         if (val.length === 0) {
            $bar.css({
               'width': '0%',
               'background-color': '#e2e8f0'
            });
         } else if (val.length < 6) {
            $bar.css({
               'width': '30%',
               'background-color': '#ef4444'
            });
         } else if (val.length < 10) {
            $bar.css({
               'width': '65%',
               'background-color': '#f59e0b'
            });
         } else {
            $bar.css({
               'width': '100%',
               'background-color': '#10b981'
            });
         }
      });

      // 5. Password Update Submit
      $('#passwordForm').on('submit', function(e) {
         e.preventDefault();

         let isValid = true;
         $('#passwordForm .error').html('');
         $('#passwordForm .form-control').removeClass('is-invalid');

         let password = $('#password').val();
         let confirmPassword = $('#confirm_password').val();

         if (!password) {
            $('#password_error').html('New password is required.');
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

         const btn = $('#savePassword');
         btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span>Updating...');
         $('#nms-loader-overlay').addClass('active');

         $.ajax({
            url: "{{ route('superadmin.profile.reset.password') }}",
            type: "POST",
            data: {
               password: password,
               confirm_password: confirmPassword,
               _token: "{{ csrf_token() }}"
            },
            success: function(response) {
               btn.prop('disabled', false).text('Update Password');
               $('#nms-loader-overlay').removeClass('active');
               if (response.status === 'success') {
                  showAlert('success', response.message);
                  $('#passwordForm')[0].reset();
                  $('#strengthBar').css({
                     'width': '0%'
                  }); // Reset Strength Bar
                  $('#passwordForm .form-control').attr('type', 'password');
                  $('#passwordForm .toggle-password i').removeClass('bi-eye-slash').addClass('bi-eye');
               } else {
                  showAlert('danger', response.message || 'Error updating password.');
               }
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
                  showAlert('danger', 'Error updating password.');
               }
            }
         });
      });

      // 6. Remove Profile Photo
      $('#removeImageBtn').on('click', function(e) {
         e.preventDefault();
         if (!confirm('Are you sure you want to remove your profile photo?')) return;
         $('#nms-loader-overlay').addClass('active');

         $.ajax({
            url: "{{ route('superadmin.profile.image.remove') }}",
            type: "POST",
            data: {
               _token: "{{ csrf_token() }}"
            },
            success: function(response) {
               if (response.status === 'success') {
                  if (typeof showAlert === 'function') showAlert('success', response.message);
                  $('#nms-loader-overlay').removeClass('active');
                  setTimeout(() => {
                     window.location.reload();
                  }, 600);
               }
            },
            error: function() {
               $('#nms-loader-overlay').removeClass('active');
               if (typeof showAlert === 'function') showAlert('danger', 'Failed to remove photo.');
            }
         });
      });
   });
</script>
@endsection