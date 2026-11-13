@extends('superadmin.layouts.app')

@section('styles')
<style>
   /* Profile Card & Custom Aesthetics */
   .profile-card-container {
      background: #fff;
      border-radius: 16px;
      overflow: hidden;
      border: 1px solid #eef2f7;
      box-shadow: 0 4px 20px rgba(1, 41, 112, 0.05);
   }

   .profile-header-banner {
      height: 90px;
      background: linear-gradient(135deg, #2c6f9b, #1e5578);
   }

   .profile-avatar-center {
      position: relative;
      margin-top: -45px;
      display: inline-block;
   }

   .profile-avatar-img,
   .profile-avatar-circle {
      width: 90px;
      height: 90px;
      border-radius: 50%;
      object-fit: cover;
      border: 4px solid #ffffff;
      box-shadow: 0 6px 16px rgba(0, 0, 0, 0.12);
   }

   .profile-avatar-circle {
      background: #2c6f9b;
      color: #fff;
      font-size: 30px;
      font-weight: 700;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto;
   }

   .avatar-edit-badge {
      position: absolute;
      bottom: 2px;
      right: 2px;
      width: 32px;
      height: 32px;
      background: #2c6f9b;
      color: #ffffff;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      border: 2px solid #ffffff;
      box-shadow: 0 4px 10px rgba(44, 111, 155, 0.3);
      transition: all 0.2s ease;
   }

   .avatar-edit-badge:hover {
      background: #1e5578;
      transform: scale(1.1);
   }

   .profile-role-pill {
      background: #e0f2fe;
      color: #0284c7;
      font-size: 11px;
      font-weight: 600;
      padding: 4px 12px;
      border-radius: 20px;
      display: inline-flex;
      align-items: center;
      gap: 5px;
   }

   .profile-meta-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 10px;
      margin-top: 16px;
      padding: 0 12px;
   }

   .profile-meta-item {
      background: #f8fafc;
      padding: 10px;
      border-radius: 10px;
      text-align: center;
      border: 1px solid #f1f5f9;
   }

   .meta-lbl {
      font-size: 10px;
      text-transform: uppercase;
      color: #64748b;
      font-weight: 600;
   }

   .meta-val {
      font-size: 13px;
      font-weight: 700;
      color: #1e293b;
      margin-top: 2px;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
   }

   .profile-contact-list {
      padding: 0 12px;
      text-align: left;
   }

   .profile-contact-row {
      display: flex;
      align-items: center;
      gap: 10px;
      font-size: 13px;
      padding: 6px 0;
      color: #475569;
   }

   .profile-contact-icon {
      width: 28px;
      height: 28px;
      background: #f1f5f9;
      color: #2c6f9b;
      border-radius: 8px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 14px;
   }

   /* Nav Pills & Info Tiles */
   .profile-nav-pills .nav-link {
      background: #f8fafc;
      color: #64748b;
      font-weight: 600;
      font-size: 13px;
      padding: 10px 18px;
      border-radius: 10px;
      border: 1px solid #e2e8f0;
      margin-right: 8px;
      transition: all 0.2s;
   }

   .profile-nav-pills .nav-link.active {
      background: #2c6f9b;
      color: #fff;
      border-color: #2c6f9b;
      box-shadow: 0 4px 12px rgba(44, 111, 155, 0.25);
   }

   .info-tile {
      background: #f8fafc;
      border: 1px solid #e2e8f0;
      border-radius: 12px;
      padding: 14px 16px;
   }

   .info-tile-label {
      font-size: 11px;
      font-weight: 600;
      color: #64748b;
      text-transform: uppercase;
      letter-spacing: 0.5px;
   }

   .info-tile-val {
      font-size: 14px;
      font-weight: 600;
      color: #1e293b;
      margin-top: 4px;
   }
</style>
@endsection

@section('content')
<!-- Standalone Hidden File Input for Auto-Upload -->
<input type="file" id="profile_image_input" class="d-none" accept="image/png, image/jpeg, image/jpg, image/webp">

<div class="pagetitle d-flex justify-content-between align-items-center mb-3">
   <div>
      <h1 class="mb-0 fw-bold text-dark">Super Admin Profile</h1>
      <div class="text-muted small mt-1">Manage your account credentials, employment details, and security settings.</div>
   </div>
</div>

<section class="section profile">
   <div class="row g-4">
      <!-- LEFT HERO PROFILE CARD -->
      <div class="col-xl-4">
         <div class="profile-card-container text-center pb-3">
            <div class="profile-header-banner"></div>

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

            <div class="px-3 pt-2">
               <h5 class="fw-bold text-dark mb-0">{{ $userInfo->first_name }} {{ $userInfo->last_name }}</h5>
               <div class="text-muted small mt-1">{{ $userInfo->designation ?: 'System Administrator' }}</div>

               <div class="mt-2">
                  <span class="profile-role-pill">
                     <i class="bi bi-shield-check"></i> Super Admin
                  </span>
               </div>

               <div class="profile-meta-grid">
                  <div class="profile-meta-item">
                     <div class="meta-lbl">System</div>
                     <div class="meta-val" title="HomeZen">HomeZen</div>
                  </div>
                  <div class="profile-meta-item">
                     <div class="meta-lbl">Department</div>
                     <div class="meta-val" title="{{ $userInfo->department ?: 'Management' }}">{{ $userInfo->department ?: 'Management' }}</div>
                  </div>
               </div>

               <hr class="my-3" style="border-color: #edf0f7;">

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

               <div id="removePhotoWrapper" class="mt-3 px-2 {{ empty($userInfo->profile_image) || !file_exists(public_path($userInfo->profile_image)) ? 'd-none' : '' }}">
                  <button type="button" id="removeImageBtn" class="btn btn-outline-danger btn-sm w-100 rounded-pill" style="font-size:12px; font-weight:600;">
                     <i class="bi bi-trash me-1"></i> Remove Avatar Photo
                  </button>
               </div>
            </div>
         </div>
      </div>

      <!-- RIGHT TABBED DETAILS -->
      <div class="col-xl-8">
         <div class="card-soft h-100 p-4">
            <ul class="nav profile-nav-pills mb-4" role="tablist">
               <li class="nav-item">
                  <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-overview">
                     <i class="bi bi-person-lines-fill me-1"></i> Overview
                  </button>
               </li>
               <li class="nav-item">
                  <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-edit">
                     <i class="bi bi-pencil-square me-1"></i> Edit Profile
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
                  <h6 class="fw-bold text-dark mb-3"><i class="bi bi-info-circle text-primary me-2"></i>Account & Personal Information</h6>

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
                        <input name="dob" type="date" class="form-control" id="dob" value="{{ $userInfo->dob }}" autocomplete="off">
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
                     <i class="bi bi-shield-check text-success me-2"></i> Update your account security password. Ensure it is at least 6 characters.
                  </div>

                  <form id="passwordForm" class="row g-3" novalidate>
                     @csrf
                     <div class="col-md-6">
                        <label for="password" class="form-label fw-semibold small">New Password <span class="text-danger">*</span></label>
                        <div class="input-group">
                           <input name="password" type="password" class="form-control password-input" id="password" placeholder="Minimum 6 characters" required>
                           <span class="input-group-text bg-light toggle-password" data-target="#password" style="cursor:pointer;">
                              <i class="bi bi-eye"></i>
                           </span>
                        </div>
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
      function isValidEmail(email) {
         return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
      }

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

      $('input, select, textarea').on('input change', function() {
         $(this).removeClass('is-invalid');
         $('#' + $(this).attr('id') + '_error').html('');
      });

      $('#profile_image_input').on('change', function() {
         let file = this.files[0];
         if (!file) return;

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
               let msg = xhr.responseJSON?.errors?.profile_image?.[0] || 'Failed to upload photo.';
               if (typeof showAlert === 'function') showAlert('danger', msg);
               else alert(msg);
            }
         });
      });

      $('#profileForm').on('submit', function(e) {
         e.preventDefault();
         let isValid = true;
         $('#profileForm .error').html('');
         $('#profileForm .form-control').removeClass('is-invalid');

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
         if (!email || !isValidEmail(email)) {
            $('#email_error').text('Please enter a valid email address.');
            $('#email').addClass('is-invalid');
            isValid = false;
         }
         let phone = $('#phone').val().replace(/\D/g, '');
         if (!phone || phone.length < 10) {
            $('#phone_error').text('Please enter a valid 10-digit phone number.');
            $('#phone').addClass('is-invalid');
            isValid = false;
         }

         if (!isValid) return;

         const btn = $('#saveProfile');
         btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span>Saving...');
         $('#nms-loader-overlay').addClass('active');

         $.ajax({
            url: "{{ route('superadmin.profile.update') }}",
            type: "POST",
            data: new FormData(this),
            processData: false,
            contentType: false,
            success: function(response) {
               btn.prop('disabled', false).text('Save Changes');
               $('#nms-loader-overlay').removeClass('active');
               if (response.status === 'success') {
                  if (typeof showAlert === 'function') showAlert('success', response.message);
                  setTimeout(() => window.location.href = "{{ route('superadmin.profile') }}", 800);
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
                  alert(xhr.responseJSON?.message || 'Error updating profile.');
               }
            }
         });
      });

      $('#password').on('input', function() {
         let val = $(this).val();
         let $bar = $('#strengthBar');
         if (val.length === 0) $bar.css({
            'width': '0%',
            'background-color': '#e2e8f0'
         });
         else if (val.length < 6) $bar.css({
            'width': '30%',
            'background-color': '#ef4444'
         });
         else if (val.length < 10) $bar.css({
            'width': '65%',
            'background-color': '#f59e0b'
         });
         else $bar.css({
            'width': '100%',
            'background-color': '#10b981'
         });
      });

      $('#passwordForm').on('submit', function(e) {
         e.preventDefault();
         let isValid = true;
         $('#passwordForm .error').html('');
         $('#passwordForm .form-control').removeClass('is-invalid');

         let password = $('#password').val();
         let confirmPassword = $('#confirm_password').val();

         if (!password || password.length < 6) {
            $('#password_error').html('Password must be at least 6 characters.');
            $('#password').addClass('is-invalid');
            isValid = false;
         }
         if (!confirmPassword || password !== confirmPassword) {
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
                  if (typeof showAlert === 'function') showAlert('success', response.message);
                  $('#passwordForm')[0].reset();
                  $('#strengthBar').css({
                     'width': '0%'
                  });
               } else {
                  if (typeof showAlert === 'function') showAlert('danger', response.message);
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
                  if (typeof showAlert === 'function') showAlert('danger', 'Error updating password.');
               }
            }
         });
      });

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
                  setTimeout(() => window.location.reload(), 600);
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