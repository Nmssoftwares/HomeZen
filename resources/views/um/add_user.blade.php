@extends('um.layouts.app')

@section('content')
<div class="pagetitle d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <div>
        <h1 class="mb-0 fs-4 fw-bold text-dark">Add New Account</h1>
        <div class="text-muted small mt-1">Register a new House Admin or Family Member under your household.</div>
    </div>
    <div>
        <a href="{{ route('um.users.index') }}" class="btn btn-secondary btn-sm shadow-sm" style="border-radius:8px; font-weight:600; padding:6px 14px;">
            <i class="bi bi-arrow-left me-1"></i> Back
        </a>
    </div>
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-12">

            <div class="card-soft p-4 bg-white shadow-sm position-relative">

                <form id="addUserForm" enctype="multipart/form-data" novalidate>
                    @csrf

                    <!-- 1. Role Selection -->
                    <div class="section-title mt-0">Account Role</div>
                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <div class="input-floating-wrap">
                                <select name="role" id="role" class="form-select" required>
                                    <option value="3" {{ old('role', $role ?? 3) == 3 ? 'selected' : '' }}>Family Member / User (Role 3)</option>
                                    <option value="2" {{ old('role', $role ?? 3) == 2 ? 'selected' : '' }}>House Admin (Role 2)</option>
                                </select>
                                <label class="form-label-joint">User Role*</label>
                            </div>
                            <small class="text-danger error mt-1 d-block" id="role_error"></small>
                        </div>
                    </div>

                    <!-- 2. Personal Information -->
                    <div class="section-title">Personal Profile Details</div>
                    <div class="row g-4 mb-4">

                        <!-- First Name -->
                        <div class="col-md-4">
                            <div class="input-floating-wrap">
                                <input type="text" class="form-control" id="first_name" name="first_name" placeholder=" " required>
                                <label class="form-label-joint">First Name*</label>
                            </div>
                            <small class="text-danger error mt-1 d-block" id="first_name_error"></small>
                        </div>

                        <!-- Last Name -->
                        <div class="col-md-4">
                            <div class="input-floating-wrap">
                                <input type="text" class="form-control" id="last_name" name="last_name" placeholder=" ">
                                <label class="form-label-joint">Last Name</label>
                            </div>
                            <small class="text-danger error mt-1 d-block" id="last_name_error"></small>
                        </div>

                        <!-- Email Address -->
                        <div class="col-md-4">
                            <div class="input-floating-wrap">
                                <input type="email" class="form-control" id="email" name="email" placeholder=" " required>
                                <label class="form-label-joint">Email ID (Login ID)*</label>
                            </div>
                            <small class="text-danger error mt-1 d-block" id="email_error"></small>
                        </div>

                        <!-- Contact Number -->
                        <div class="col-md-4">
                            <div class="input-floating-wrap">
                                <input type="text" class="form-control" id="phone" name="phone" maxlength="12" placeholder=" ">
                                <label class="form-label-joint">Contact Number</label>
                            </div>
                            <small class="text-danger error mt-1 d-block" id="phone_error"></small>
                        </div>

                        <!-- Gender -->
                        <div class="col-md-4">
                            <div class="input-floating-wrap">
                                <select name="gender" id="gender" class="form-select">
                                    <option value="">Select Gender</option>
                                    <option value="1">Male</option>
                                    <option value="2">Female</option>
                                    <option value="3">Other</option>
                                </select>
                                <label class="form-label-joint">Gender</label>
                            </div>
                            <small class="text-danger error mt-1 d-block" id="gender_error"></small>
                        </div>

                        <!-- Password -->
                        <div class="col-md-4">
                            <div class="input-floating-wrap">
                                <input type="password" class="form-control" id="password" name="password" placeholder=" " required>
                                <label class="form-label-joint">Password (Min. 6 chars)*</label>
                            </div>
                            <small class="text-danger error mt-1 d-block" id="password_error"></small>
                        </div>

                    </div>

                    <!-- Form Actions (Clear & Save) -->
                    <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                        <button type="button" class="btn btn-danger px-4 py-2" id="clearBtn" style="border-radius:6px; font-weight:600; font-size:13px;">CLEAR</button>
                        <button type="submit" class="btn px-5 py-2" id="saveBtn" style="background-color: #334e68; color: #fff; border: none; border-radius: 6px; font-weight: 600; font-size: 13px;">SAVE</button>
                    </div>

                </form>

            </div>

        </div>
    </div>
</section>

<!-- Floating Bottom-Right View Button -->
<a href="{{ route('um.users.index') }}" class="floating-action-btn" title="View Users">
   <i class="bi bi-eye-fill"></i>
</a>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Phone formatter
        $('#phone').on('input', function() {
            let val = $(this).val().replace(/\D/g, '').substring(0, 10);
            let formatted = '';
            if (val.length > 0) formatted += val.substring(0, 3);
            if (val.length >= 4) formatted += '-' + val.substring(3, 6);
            if (val.length >= 7) formatted += '-' + val.substring(6, 10);
            $(this).val(formatted);
        });

        // Clear error on input change
        $('input, select').on('input change', function() {
            $(this).removeClass('is-invalid');
            $('#' + $(this).attr('name') + '_error').html('');
        });

        // Form Submit via AJAX
        $('#addUserForm').on('submit', function(e) {
            e.preventDefault();
            let isValid = true;
            $('.error').html('');
            $('.form-control, .form-select').removeClass('is-invalid');

            // Client-side validations
            if (!$('#role').val()) {
                $('#role_error').text('Please select a role.');
                $('#role').addClass('is-invalid');
                isValid = false;
            }
            if (!$('#first_name').val().trim()) {
                $('#first_name_error').text('First name is required.');
                $('#first_name').addClass('is-invalid');
                isValid = false;
            }
            if (!$('#email').val().trim()) {
                $('#email_error').text('Email is required.');
                $('#email').addClass('is-invalid');
                isValid = false;
            }
            if (!$('#password').val() || $('#password').val().length < 6) {
                $('#password_error').text('Password must be at least 6 characters.');
                $('#password').addClass('is-invalid');
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
                url: "{{ route('um.users.store') }}",
                type: "POST",
                data: new FormData(this),
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (typeof showAlert === 'function') {
                        showAlert('success', response.message);
                    }
                    setTimeout(() => {
                        window.location.href = "{{ route('um.users.index') }}";
                    }, 1000);
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
                        alert(xhr.responseJSON?.message || 'Error creating user account.');
                    }
                }
            });
        });

        // Clear Form Button
        $('#clearBtn').on('click', () => $('#addUserForm')[0].reset());
    });
</script>
@endsection