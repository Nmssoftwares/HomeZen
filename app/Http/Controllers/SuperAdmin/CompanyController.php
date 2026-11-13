<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\AssignModule;
use App\Models\Company;
use App\Models\Module;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use App\Services\TodoMasterDataService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class CompanyController extends Controller
{
    public function index()
    {
        $data['title'] = 'SuperAdmin - Households';
        return view('superadmin.view_company', $data);
    }

    public function datatable()
    {
        $companies = Company::with(['users' => fn($q) => $q->where('role', 2), 'assignModules.module']);

        return DataTables::of($companies)
            ->addColumn('company_info', function ($row) {
                $logo = $row->company_logo && File::exists(public_path($row->company_logo))
                    ? asset($row->company_logo)
                    : null;

                $codeBadge = $row->company_code
                    ? '<span class="badge bg-light text-primary border ms-1">' . e($row->company_code) . '</span>'
                    : '';

                $html = '<div class="d-flex align-items-center gap-2">';
                if ($logo) {
                    $html .= '<img src="' . $logo . '" class="rounded shadow-sm" style="width:36px; height:36px; object-fit:cover;">';
                } else {
                    $html .= '<div class="rounded bg-primary-subtle text-primary d-flex align-items-center justify-content-center fw-bold" style="width:36px; height:36px; font-size:13px;">' . strtoupper(substr($row->company_name, 0, 2)) . '</div>';
                }

                $html .= '<div>
                    <div class="fw-bold text-dark">' . e($row->company_name) . $codeBadge . '</div>
                    <small class="text-muted"><i class="bi bi-geo-alt me-1"></i>' . e($row->company_address) . '</small>
                </div></div>';

                return $html;
            })
            ->addColumn('admin_name', fn($row) => optional($row->users->first())->first_name ? e($row->users->first()->first_name . ' ' . $row->users->first()->last_name) : '<span class="text-muted small">No Admin</span>')
            ->addColumn('admin_email', fn($row) => optional($row->users->first())->email ? e($row->users->first()->email) : '-')
            ->addColumn('admin_phone', fn($row) => optional($row->users->first())->phone ? e($row->users->first()->phone) : '-')
            ->addColumn('status_badge', fn($row) => (int)$row->status === 1 ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Inactive</span>')
            ->addColumn('action', function ($row) {
                $adminId = optional($row->users->first())->id;

                $html = '<div class="d-flex justify-content-center gap-1">';
                $html .= '<a href="' . route('superadmin.companies.edit', $row->id) . '" class="btn btn-sm btn-outline-primary rounded-circle" title="Edit Household"><i class="bi bi-pencil"></i></a>';

                if ($adminId) {
                    $html .= ' <button type="button" class="btn btn-sm btn-outline-success rounded-circle changePasswordBtn" data-userid="' . $adminId . '" data-bs-toggle="modal" data-bs-target="#changePasswordModal" title="Reset Admin Password"><i class="bi bi-key"></i></button>';
                }

                $html .= ' <button type="button" class="btn btn-sm btn-outline-secondary rounded-circle" onclick="changeCompanyModuleBtn(' . $row->id . ')" title="Manage Modules"><i class="bi bi-gear"></i></button>';
                $html .= '</div>';
                return $html;
            })
            ->rawColumns(['company_info', 'admin_name', 'status_badge', 'action'])
            ->make(true);
    }

    public function exportPdf()
    {
        $companies = Company::with(['users' => fn($q) => $q->where('role', 2)])->get();
        $currentCompany = Auth::check() && Auth::user()->company_id ? Company::find(Auth::user()->company_id) : null;

        $data = [
            'companies'    => $companies,
            'company_name' => $currentCompany ? $currentCompany->company_name : 'HomeZen SuperAdmin',
            'company_logo' => $currentCompany ? $currentCompany->company_logo : 'assets/img/main-logo.png'
        ];

        return Pdf::loadView('superadmin.pdf.companies_list', $data)
            ->download('Households_List_' . date('Ymd_His') . '.pdf');
    }

    public function create()
    {
        $data['title'] = 'SuperAdmin - Add Household';
        $data['groupedModules'] = Module::where('status', 1)
            ->orderBy('group_order', 'asc')
            ->get()
            ->groupBy(fn($item) => $item->group_name ?: 'General Modules');

        return view('superadmin.add_company', $data);
    }

    public function store(Request $request, TodoMasterDataService $todoMasterDataService): JsonResponse
    {
        $request->merge(['phone' => preg_replace('/\D/', '', $request->phone)]);

        $request->validate([
            'company_name'    => 'required|string|max:255',
            'company_code'    => 'nullable|string|max:10',
            'company_address' => 'required|string|max:500',
            'company_email'   => 'nullable|email|max:255|unique:companies,company_email',
            'company_phone'   => 'nullable|string|max:20|unique:companies,company_phone',
            'company_website' => 'nullable|url|max:255',
            'timezone'        => 'nullable|string|max:50',
            'date_format'     => 'nullable|string|max:20',
            'company_logo'    => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:2048',

            'first_name'      => 'required|string|max:100',
            'last_name'       => 'required|string|max:100',
            'email'           => 'required|email|max:255|unique:users,email',
            'phone'           => 'required|digits:10|unique:users,phone',
            'password'        => 'required|string|min:6',
            'modules'         => 'required|array|min:1',
            'modules.*'       => 'integer|exists:modules,id',
        ]);

        $logoPath = null;
        if ($request->hasFile('company_logo')) {
            $logoFile = $request->file('company_logo');
            $logoName = time() . '_' . uniqid() . '.' . $logoFile->getClientOriginalExtension();
            $destPath = public_path('uploads/company_logos');
            if (!File::exists($destPath)) {
                File::makeDirectory($destPath, 0755, true);
            }
            $logoFile->move($destPath, $logoName);
            $logoPath = 'uploads/company_logos/' . $logoName;
        }

        DB::beginTransaction();

        try {
            $company = Company::create([
                'company_name'    => $request->company_name,
                'company_code'    => $request->company_code ? strtoupper($request->company_code) : null,
                'company_address' => $request->company_address,
                'company_logo'    => $logoPath,
                'company_email'   => $request->company_email,
                'company_phone'   => $request->company_phone,
                'company_website' => $request->company_website,
                'timezone'        => $request->input('timezone', 'Asia/Kolkata'),
                'date_format'     => $request->input('date_format', 'd-m-Y'),
                'status'          => 1,
            ]);

            User::create([
                'first_name'    => $request->first_name,
                'last_name'     => $request->last_name,
                'employee_code' => $request->employee_code,
                'designation'   => $request->designation ?: 'House Admin',
                'department'    => $request->department ?: 'Management',
                'dob'           => $request->dob,
                'joining_date'  => $request->joining_date ?: now()->format('Y-m-d'),
                'gender'        => $request->gender,
                'email'         => $request->email,
                'password'      => Hash::make($request->password),
                'company_id'    => $company->id,
                'role'          => 2,
                'status'        => 1,
                'phone'         => $request->phone,
            ]);

            foreach ($request->modules as $moduleId) {
                AssignModule::create([
                    'company_id' => $company->id,
                    'module_id'  => $moduleId,
                ]);
            }

            self::generateDefaultCompanyRoles($company->id, $request->modules);
            $todoMasterDataService->createForCompany($company->id);

            DB::commit();

            return response()->json(['status' => 'success', 'message' => 'Household and admin account created successfully.']);
        } catch (\Throwable $e) {
            DB::rollBack();
            if ($logoPath && File::exists(public_path($logoPath))) {
                File::delete(public_path($logoPath));
            }
            Log::error('Company Store Error: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Failed to create household.', 'error' => $e->getMessage()], 500);
        }
    }

    public function edit(int $id)
    {
        $data['title']   = 'SuperAdmin - Edit Household';
        $data['company'] = Company::findOrFail($id);
        $data['user']    = User::where(['company_id' => $id, 'role' => 2])->first();

        return view('superadmin.edit_company', $data);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $company = Company::findOrFail($id);
        $user    = User::where(['company_id' => $id, 'role' => 2])->first();

        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'Admin user record not found.'], 404);
        }

        $request->merge(['phone' => preg_replace('/\D/', '', $request->phone)]);

        $request->validate([
            'company_name'    => 'required|string|max:255',
            'company_code'    => 'nullable|string|max:10',
            'company_address' => 'required|string|max:500',
            'company_email'   => 'nullable|email|max:255|unique:companies,company_email,' . $id,
            'company_phone'   => 'nullable|string|max:20|unique:companies,company_phone,' . $id,
            'company_website' => 'nullable|url|max:255',
            'timezone'        => 'nullable|string|max:50',
            'date_format'     => 'nullable|string|max:20',
            'status'          => 'required|in:0,1',
            'company_logo'    => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:2048',

            'first_name'      => 'required|string|max:100',
            'last_name'       => 'required|string|max:100',
            'email'           => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone'           => 'required|digits:10|unique:users,phone,' . $user->id,
        ]);

        DB::beginTransaction();

        try {
            $logoPath = $company->company_logo;
            if ($request->hasFile('company_logo')) {
                if ($logoPath && File::exists(public_path($logoPath))) {
                    File::delete(public_path($logoPath));
                }
                $logoFile = $request->file('company_logo');
                $logoName = time() . '_' . uniqid() . '.' . $logoFile->getClientOriginalExtension();
                $logoFile->move(public_path('uploads/company_logos'), $logoName);
                $logoPath = 'uploads/company_logos/' . $logoName;
            }

            $company->update([
                'company_name'    => $request->company_name,
                'company_code'    => $request->company_code ? strtoupper($request->company_code) : null,
                'company_address' => $request->company_address,
                'company_logo'    => $logoPath,
                'company_email'   => $request->company_email,
                'company_phone'   => $request->company_phone,
                'company_website' => $request->company_website,
                'timezone'        => $request->input('timezone', 'Asia/Kolkata'),
                'date_format'     => $request->input('date_format', 'd-m-Y'),
                'status'          => $request->status,
            ]);

            $user->update([
                'first_name'    => $request->first_name,
                'last_name'     => $request->last_name,
                'employee_code' => $request->employee_code,
                'designation'   => $request->designation,
                'department'    => $request->department,
                'dob'           => $request->dob,
                'joining_date'  => $request->joining_date,
                'gender'        => $request->gender,
                'email'         => $request->email,
                'phone'         => $request->phone,
                'status'        => $request->status,
            ]);

            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'Household profile updated successfully.']);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Company Update Error: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Failed to update household.'], 500);
        }
    }

    public function resetPassword(Request $request): JsonResponse
    {
        $request->validate([
            'id'       => 'required|integer|exists:users,id',
            'password' => 'required|string|min:6',
        ]);

        $user = User::findOrFail($request->id);
        $user->update(['password' => Hash::make($request->password)]);

        return response()->json(['status' => 'success', 'message' => 'Admin password updated successfully.']);
    }

    public function getCompanyModules(Request $request): JsonResponse
    {
        $companyId       = (int)$request->company_id;
        $assignedModules = AssignModule::where('company_id', $companyId)->pluck('module_id')->toArray();

        $modules = Module::where('status', 1)
            ->orderBy('group_order', 'asc')
            ->get()
            ->groupBy(fn($item) => $item->group_name ?: 'General Modules');

        return response()->json([
            'status'          => 'success',
            'assignedModules' => $assignedModules,
            'groupedModules'  => $modules,
        ]);
    }

    public function updateCompanyModules(Request $request): JsonResponse
    {
        $request->validate([
            'company_id' => 'required|integer|exists:companies,id',
            'modules'    => 'required|array|min:1',
            'modules.*'  => 'integer|exists:modules,id',
        ]);

        $companyId = (int)$request->company_id;
        $modules   = $request->modules;

        DB::transaction(function () use ($companyId, $modules) {
            AssignModule::where('company_id', $companyId)->delete();

            foreach ($modules as $moduleId) {
                AssignModule::create(['company_id' => $companyId, 'module_id' => $moduleId]);
            }

            if ($companyId == 1) {
                Role::where('company_id', $companyId)->delete();
                self::generateDefaultCompanyRoles($companyId, $modules);
            }
        });

        return response()->json(['status' => 'success', 'message' => 'Household modules updated successfully.']);
    }

    private static function generateDefaultCompanyRoles(int $companyId, array $moduleIds)
    {
        $modules = Module::whereIn('id', $moduleIds)->where('status', 1)->get();
        if ($modules->isEmpty()) return;

        $shortNames = $modules->pluck('short_name')->filter()->toArray();
        if (empty($shortNames)) return;

        $viewPermIds    = Permission::whereIn('module_short_name', $shortNames)->where('name', 'LIKE', '%.view')->pluck('id')->toArray();
        $addEditPermIds = Permission::whereIn('module_short_name', $shortNames)->where('name', 'LIKE', '%.add_edit')->pluck('id')->toArray();
        $deletePermIds  = Permission::whereIn('module_short_name', $shortNames)->where('name', 'LIKE', '%.delete')->pluck('id')->toArray();
        $exportPermIds  = Permission::whereIn('module_short_name', $shortNames)->where('name', 'LIKE', '%.export')->pluck('id')->toArray();
        $adminPermIds   = Permission::whereIn('module_short_name', $shortNames)->where('name', 'LIKE', '%.admin_access')->pluck('id')->toArray();

        if (!empty($viewPermIds)) {
            Role::firstOrCreate(['company_id' => $companyId, 'name' => 'Viewer'], ['description' => 'Read-only access.'])
                ->permissions()->syncWithoutDetaching($viewPermIds);
        }

        $contributorIds = array_merge($viewPermIds, $addEditPermIds);
        if (!empty($contributorIds)) {
            Role::firstOrCreate(['company_id' => $companyId, 'name' => 'Contributor'], ['description' => 'Can view, create, and update records.'])
                ->permissions()->syncWithoutDetaching($contributorIds);
        }

        $managerIds = array_merge($viewPermIds, $addEditPermIds, $deletePermIds, $exportPermIds);
        if (!empty($managerIds)) {
            Role::firstOrCreate(['company_id' => $companyId, 'name' => 'Manager'], ['description' => 'Full operational access.'])
                ->permissions()->syncWithoutDetaching($managerIds);
        }

        $adminAllIds = array_merge($viewPermIds, $addEditPermIds, $deletePermIds, $exportPermIds, $adminPermIds);
        if (!empty($adminAllIds)) {
            Role::firstOrCreate(['company_id' => $companyId, 'name' => 'Administrator'], ['description' => 'Complete administrative privileges.'])
                ->permissions()->syncWithoutDetaching($adminAllIds);
        }
    }
}
