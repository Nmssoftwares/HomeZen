@extends('superadmin.layouts.app')

@section('styles')
<style>
    .settings-card {
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        background: #ffffff;
        padding: 2rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
    }

    .logo-preview-box {
        width: 140px;
        height: 140px;
        border: 2px dashed #cbd5e1;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        background: #f8fafc;
        position: relative;
    }

    .logo-preview-box img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }
</style>
@endsection

@section('content')
<div class="pagetitle mb-4">
    <h1 class="fw-bold mb-1">Company Profile & Branding</h1>
    <p class="text-muted small mb-0">Update your company details, logo, address, and legal tax identifiers (GST & PAN) for formal documents.</p>
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="settings-card">
                <!-- novalidate add kiya gaya hai -->
                <form id="companySettingForm" enctype="multipart/form-data" novalidate>
                    @csrf

                    <!-- Section: Basic Identity & Logo -->
                    <div class="row g-4 mb-4 align-items-center">
                        <div class="col-md-3 text-center">
                            <label class="form-label fw-semibold fs-13 d-block text-muted mb-2">Company Official Logo</label>
                            <div class="logo-preview-box mx-auto mb-2" id="logoPreviewContainer">
                                @php
                                $hasLogo = !empty($company->company_logo) && file_exists(public_path($company->company_logo));
                                @endphp

                                <!-- No Logo Text (Agar logo hai toh ise hide rakhenge) -->
                                <span class="text-muted small" id="noLogoText" style="display: {{ $hasLogo ? 'none' : 'block' }};">
                                    <i class="bi bi-image fs-3 d-block mb-1"></i>No Logo
                                </span>

                                <!-- Logo Image (Agar logo nahi hai toh ise hide rakhenge) -->
                                <img src="{{ $hasLogo ? asset($company->company_logo) : '' }}" id="logoPreview" alt="Logo" style="display: {{ $hasLogo ? 'block' : 'none' }};">
                            </div>
                            <input type="file" class="form-control form-control-sm mt-2" id="company_logo" name="company_logo" accept="image/*">
                            <small class="text-muted d-block mt-1 fs-11">Max size: 2MB (PNG, JPG, SVG)</small>
                            <small class="text-danger error" id="company_logo_error"></small>
                        </div>

                        <div class="col-md-9">
                            <div class="row g-3">
                                <div class="col-md-8">
                                    <label for="company_name" class="form-label fw-semibold fs-13">Company Legal Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="company_name" name="company_name" value="{{ $company->company_name }}" required>
                                    <small class="text-danger error" id="company_name_error"></small>
                                </div>
                                <div class="col-md-4">
                                    <label for="company_code" class="form-label fw-semibold fs-13">Company Code</label>
                                    <input type="text" class="form-control font-monospace text-uppercase" id="company_code" name="company_code" value="{{ $company->company_code }}" maxlength="10" placeholder="e.g. COMP01">
                                    <small class="text-danger error" id="company_code_error"></small>
                                </div>
                                <div class="col-md-6">
                                    <label for="company_email" class="form-label fw-semibold fs-13">Official Contact Email</label>
                                    <input type="email" class="form-control" id="company_email" name="company_email" value="{{ $company->company_email }}" placeholder="billing@company.com">
                                    <small class="text-danger error" id="company_email_error"></small>
                                </div>
                                <div class="col-md-6">
                                    <label for="company_phone" class="form-label fw-semibold fs-13">Official Contact Phone</label>
                                    <input type="text" class="form-control font-monospace" id="company_phone" name="company_phone" value="{{ $company->company_phone }}" placeholder="+91 9876543210">
                                    <small class="text-danger error" id="company_phone_error"></small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4 text-muted">

                    <!-- Section: Legal Tax Information -->
                    <h6 class="fw-bold text-dark mb-3"><i class="bi bi-shield-check text-primary me-2"></i>Legal Tax Identifiers (For Purchase Orders & Billing)</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="gst_number" class="form-label fw-semibold fs-13">GST Number (GSTIN)</label>
                            <input type="text" class="form-control font-monospace text-uppercase" id="gst_number" name="gst_number" value="{{ $company->gst_number }}" maxlength="50" placeholder="e.g. 24AAAAA0000A1Z5">
                            <small class="text-danger error" id="gst_number_error"></small>
                        </div>
                        <div class="col-md-6">
                            <label for="pan_number" class="form-label fw-semibold fs-13">Permanent Account Number (PAN)</label>
                            <input type="text" class="form-control font-monospace text-uppercase" id="pan_number" name="pan_number" value="{{ $company->pan_number }}" maxlength="50" placeholder="e.g. AAAAA0000A">
                            <small class="text-danger error" id="pan_number_error"></small>
                        </div>
                    </div>

                    <hr class="my-4 text-muted">

                    <!-- Section: Address & Localization -->
                    <h6 class="fw-bold text-dark mb-3"><i class="bi bi-geo-alt text-primary me-2"></i>Registered Location & System Formatting</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-12">
                            <label for="company_address" class="form-label fw-semibold fs-13">Registered Office Address <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="company_address" name="company_address" rows="3" required placeholder="Full physical postal address...">{{ $company->company_address }}</textarea>
                            <small class="text-danger error" id="company_address_error"></small>
                        </div>
                        <div class="col-md-4">
                            <label for="company_website" class="form-label fw-semibold fs-13">Official Website</label>
                            <input type="url" class="form-control" id="company_website" name="company_website" value="{{ $company->company_website }}" placeholder="https://company.com">
                            <small class="text-danger error" id="company_website_error"></small>
                        </div>
                        <div class="col-md-4">
                            <label for="timezone" class="form-label fw-semibold fs-13">Timezone <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="timezone" name="timezone" value="{{ $company->timezone ?? 'Asia/Kolkata' }}" required>
                            <small class="text-danger error" id="timezone_error"></small>
                        </div>
                        <div class="col-md-4">
                            <label for="date_format" class="form-label fw-semibold fs-13">Date Format <span class="text-danger">*</span></label>
                            <select class="form-select" id="date_format" name="date_format" required>
                                <option value="d-m-Y" {{ ($company->date_format ?? 'd-m-Y') == 'd-m-Y' ? 'selected' : '' }}>DD-MM-YYYY (e.g. 15-09-2026)</option>
                                <option value="Y-m-d" {{ ($company->date_format ?? '') == 'Y-m-d' ? 'selected' : '' }}>YYYY-MM-DD (e.g. 2026-09-15)</option>
                                <option value="d M Y" {{ ($company->date_format ?? '') == 'd M Y' ? 'selected' : '' }}>DD Mon YYYY (e.g. 15 Sep 2026)</option>
                            </select>
                            <small class="text-danger error" id="date_format_error"></small>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                        <button type="submit" class="btn btn-primary px-4 py-2 fw-semibold" id="saveCompanySettingBtn">
                            <i class="bi bi-check2-circle me-1"></i> Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
    $(function() {
        // Instant Logo File Preview
        $('#company_logo').on('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#logoPreview').attr('src', e.target.result).show();
                    $('#noLogoText').hide();
                };
                reader.readAsDataURL(file);
            }
        });

        // Helper function for Email validation
        function isValidEmail(email) {
            const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return regex.test(email);
        }

        // Clear validation errors on input
        $('input, select, textarea').on('input change', function() {
            $(this).removeClass('is-invalid');
            $('#' + $(this).attr('id') + '_error').text('');
        });

        // Form Submit Handler
        $('#companySettingForm').on('submit', function(e) {
            e.preventDefault();

            // 1. Reset Errors
            let isValid = true;
            $('.error').text('');
            $('.form-control, .form-select').removeClass('is-invalid');

            // 2. Client Side Validations

            if (!$('#company_name').val().trim()) {
                $('#company_name_error').text('Company name is required.');
                $('#company_name').addClass('is-invalid');
                isValid = false;
            }

            if (!$('#company_address').val().trim()) {
                $('#company_address_error').text('Company address is required.');
                $('#company_address').addClass('is-invalid');
                isValid = false;
            }

            let email = $('#company_email').val().trim();
            if (email && !isValidEmail(email)) {
                $('#company_email_error').text('Please enter a valid email format.');
                $('#company_email').addClass('is-invalid');
                isValid = false;
            }

            if (!$('#timezone').val().trim()) {
                $('#timezone_error').text('Timezone is required.');
                $('#timezone').addClass('is-invalid');
                isValid = false;
            }

            if (!$('#date_format').val().trim()) {
                $('#date_format_error').text('Date format is required.');
                $('#date_format').addClass('is-invalid');
                isValid = false;
            }

            // Agar validation fail hui to first error pe scroll karenge
            if (!isValid) {
                $('html, body').animate({
                    scrollTop: $('.is-invalid').first().offset().top - 100
                }, 300);
                return false;
            }

            // 3. AJAX Submit
            let form = this;
            let formData = new FormData(form);
            let btn = $('#saveCompanySettingBtn');

            btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span>Saving...');
            if (typeof $('#nms-loader-overlay') !== 'undefined') {
                $('#nms-loader-overlay').addClass('active');
            }

            $.ajax({
                url: "{{ route('superadmin.company_settings.update') }}",
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(res) {
                    if (typeof $('#nms-loader-overlay') !== 'undefined') {
                        $('#nms-loader-overlay').removeClass('active');
                    }
                    btn.prop('disabled', false).html('<i class="bi bi-check2-circle me-1"></i> Save Changes');

                    if (res.status === 'success') {
                        if (typeof showAlert === 'function') {
                            showAlert('success', res.message);
                        } else {
                            alert(res.message);
                        }
                        // Update logo from response if present
                        if (res.logo_url) {
                            $('#logoPreview').attr('src', res.logo_url).show();
                            $('#noLogoText').hide();
                        }
                    } else {
                        if (typeof showAlert === 'function') {
                            showAlert('danger', res.message || 'Update failed.');
                        } else {
                            alert(res.message || 'Update failed.');
                        }
                    }
                },
                error: function(xhr) {
                    if (typeof $('#nms-loader-overlay') !== 'undefined') {
                        $('#nms-loader-overlay').removeClass('active');
                    }
                    btn.prop('disabled', false).html('<i class="bi bi-check2-circle me-1"></i> Save Changes');

                    if (xhr.status === 422 && xhr.responseJSON.errors) {
                        $.each(xhr.responseJSON.errors, function(field, messages) {
                            $('#' + field + '_error').text(messages[0]);
                            $('#' + field).addClass('is-invalid');
                        });
                        $('html, body').animate({
                            scrollTop: $('.is-invalid').first().offset().top - 100
                        }, 300);
                    } else {
                        let msg = xhr.responseJSON?.message || 'Server error occurred.';
                        if (typeof showAlert === 'function') {
                            showAlert('danger', msg);
                        } else {
                            alert(msg);
                        }
                    }
                }
            });
        });
    });
</script>
@endsection