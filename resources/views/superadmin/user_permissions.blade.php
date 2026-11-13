@extends('superadmin.layouts.app')

@section('content')
<div class="pagetitle d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <div>
        <h1 class="mb-0">Manage Permissions</h1>
        <div class="text-muted small mt-1">Configure Add, Edit, Delete, and Export access for <strong>{{ $employee->first_name }} {{ $employee->last_name }}</strong></div>
    </div>
    <a href="{{ route('superadmin.user.index') }}" class="btn btn-outline-secondary d-flex align-items-center gap-1 shadow-sm" style="border-radius: 8px; font-weight: 600;">
        <i class="bi bi-arrow-left"></i> Back to Users
    </a>
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                <div class="card-body p-0">
                    <form id="permissionForm">
                        @csrf
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead style="background-color: #f8f9fa;">
                                    <tr>
                                        <th class="ps-4 py-3 align-middle" style="width: 25%;">Module Name</th>
                                        <!-- Header with Select All Toggles -->
                                        <th class="text-center py-2" style="width: 15%;">
                                            <div class="fw-bold mb-1 text-primary">View (Full)</div>
                                            <div class="form-check form-switch d-inline-block">
                                                <input class="form-check-input select-all-col" data-target="view" type="checkbox" title="Select All View" style="cursor:pointer; width:32px; height:16px;">
                                            </div>
                                        </th>
                                        <th class="text-center py-2" style="width: 15%;">
                                            <div class="fw-bold mb-1 text-primary">Add</div>
                                            <div class="form-check form-switch d-inline-block">
                                                <input class="form-check-input select-all-col" data-target="add" type="checkbox" title="Select All Add" style="cursor:pointer; width:32px; height:16px;">
                                            </div>
                                        </th>
                                        <th class="text-center py-2" style="width: 15%;">
                                            <div class="fw-bold mb-1 text-primary">Edit</div>
                                            <div class="form-check form-switch d-inline-block">
                                                <input class="form-check-input select-all-col" data-target="edit" type="checkbox" title="Select All Edit" style="cursor:pointer; width:32px; height:16px;">
                                            </div>
                                        </th>
                                        <th class="text-center py-2" style="width: 15%;">
                                            <div class="fw-bold mb-1 text-primary">Delete</div>
                                            <div class="form-check form-switch d-inline-block">
                                                <input class="form-check-input select-all-col" data-target="delete" type="checkbox" title="Select All Delete" style="cursor:pointer; width:32px; height:16px;">
                                            </div>
                                        </th>
                                        <!-- 🔥 NEW EXPORT COLUMN -->
                                        <th class="text-center py-2" style="width: 15%;">
                                            <div class="fw-bold mb-1 text-primary">Export</div>
                                            <div class="form-check form-switch d-inline-block">
                                                <input class="form-check-input select-all-col" data-target="export" type="checkbox" title="Select All Export" style="cursor:pointer; width:32px; height:16px;">
                                            </div>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($modulesGroups as $groupName => $modules)
                                    <!-- Group Header -->
                                    <tr>
                                        <td colspan="6" class="bg-light fw-bold text-primary ps-4 py-2" style="font-size: 13px; letter-spacing: 0.5px;">
                                            <i class="bi bi-grid me-1"></i> {{ strtoupper($groupName) }}
                                        </td>
                                    </tr>

                                    <!-- Modules -->
                                    @foreach($modules as $module)
                                    @php
                                    $perm = $permissions->get($module->id);
                                    $short = $module->short_name;
                                    @endphp
                                    <tr>
                                        <!-- Hidden input for module_id -->
                                        <input type="hidden" name="permissions[{{ $short }}][module_id]" value="{{ $module->id }}">

                                        <td class="ps-4">
                                            <div class="fw-semibold text-dark">{{ $module->name }} <span class="badge bg-secondary-subtle text-secondary ms-1" style="font-size: 10px;">{{ $short }}</span></div>
                                            <div class="small text-muted" style="font-size: 11px;">{{ $module->description }}</div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-check form-switch d-flex justify-content-center">
                                                <input class="form-check-input perm-cb perm-view" type="checkbox" name="permissions[{{ $short }}][view]" value="1" {{ ($perm && $perm->can_view) ? 'checked' : '' }} style="cursor:pointer; width:38px; height:20px;">
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-check form-switch d-flex justify-content-center">
                                                <input class="form-check-input perm-cb perm-add" type="checkbox" name="permissions[{{ $short }}][add]" value="1" {{ ($perm && $perm->can_add) ? 'checked' : '' }} style="cursor:pointer; width:38px; height:20px;">
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-check form-switch d-flex justify-content-center">
                                                <input class="form-check-input perm-cb perm-edit" type="checkbox" name="permissions[{{ $short }}][edit]" value="1" {{ ($perm && $perm->can_edit) ? 'checked' : '' }} style="cursor:pointer; width:38px; height:20px;">
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-check form-switch d-flex justify-content-center">
                                                <input class="form-check-input perm-cb perm-delete" type="checkbox" name="permissions[{{ $short }}][delete]" value="1" {{ ($perm && $perm->can_delete) ? 'checked' : '' }} style="cursor:pointer; width:38px; height:20px;">
                                            </div>
                                        </td>
                                        <!-- 🔥 NEW EXPORT CHECKBOX -->
                                        <td class="text-center">
                                            <div class="form-check form-switch d-flex justify-content-center">
                                                <input class="form-check-input perm-cb perm-export" type="checkbox" name="permissions[{{ $short }}][export]" value="1" {{ ($perm && $perm->can_export) ? 'checked' : '' }} style="cursor:pointer; width:38px; height:20px; border-color: #0d6efd;">
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer bg-white p-4 text-end" style="border-bottom-left-radius: 12px; border-bottom-right-radius: 12px;">
                            <button type="button" class="btn btn-secondary me-2" onclick="window.location.href='{{ route('superadmin.user.index') }}'">Cancel</button>
                            <button type="submit" class="btn btn-primary px-4 shadow-sm" id="saveBtn" style="border-radius: 8px; font-weight: 600;">Save Permissions</button>
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
        // --- 1. COLUMN-WISE SELECT ALL LOGIC ---
        $('.select-all-col').on('change', function() {
            let targetCol = $(this).data('target'); // view, add, edit, delete, or export
            let isChecked = $(this).is(':checked');

            // Us specific column ke saare checkboxes ko ON/OFF karna
            $('.perm-' + targetCol).prop('checked', isChecked);
        });

        // Optional UX: Agar manually sab check ho jayen, to master bhi check ho jaye
        $('.perm-cb').on('change', function() {
            let classes = $(this).attr('class').split(' ');
            let colClass = classes.find(c => c.startsWith('perm-') && c !== 'perm-cb');
            if (colClass) {
                let targetName = colClass.split('-')[1]; // view, add, etc.
                let total = $('.' + colClass).length;
                let checked = $('.' + colClass + ':checked').length;
                // Agar saare checked hain, to header wala bhi ON dikhao
                $('.select-all-col[data-target="' + targetName + '"]').prop('checked', total === checked);
            }
        });

        // Check on Page Load
        $('.select-all-col').each(function() {
            let targetName = $(this).data('target');
            let total = $('.perm-' + targetName).length;
            let checked = $('.perm-' + targetName + ':checked').length;
            if (total > 0 && total === checked) {
                $(this).prop('checked', true);
            }
        });


        // --- 2. AJAX FORM SUBMIT ---
        $('#permissionForm').on('submit', function(e) {
            e.preventDefault();

            const btn = $('#saveBtn');
            btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span>Saving...');
            if (typeof $('#nms-loader-overlay').addClass === 'function') {
                $('#nms-loader-overlay').addClass('active');
            }

            $.ajax({
                url: "{{ route('superadmin.user.permissions.update', $employee->id) }}",
                type: "POST",
                data: $(this).serialize(),
                success: function(response) {
                    if (response.status === 'success') {
                        if (typeof showAlert === 'function') showAlert('success', response.message);
                        setTimeout(() => {
                            window.location.href = "{{ route('superadmin.user.index') }}";
                        }, 800);
                    } else {
                        btn.prop('disabled', false).text('Save Permissions');
                        if (typeof showAlert === 'function') showAlert('danger', response.message);
                    }
                    if (typeof $('#nms-loader-overlay').removeClass === 'function') {
                        $('#nms-loader-overlay').removeClass('active');
                    }
                },
                error: function() {
                    btn.prop('disabled', false).text('Save Permissions');
                    if (typeof showAlert === 'function') showAlert('danger', 'Server error. Please try again.');
                    if (typeof $('#nms-loader-overlay').removeClass === 'function') {
                        $('#nms-loader-overlay').removeClass('active');
                    }
                }
            });
        });
    });
</script>
@endsection