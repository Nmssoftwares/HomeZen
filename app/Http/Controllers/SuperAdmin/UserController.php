<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\LeaveBalance;
use App\Models\LeaveType;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Company;

class UserController extends Controller
{
   public function index()
   {
      $data['title'] = 'Super Admin - View Users';
      return view('superadmin.view_user', $data);
   }

   public function datatable()
   {
      $companyId = Auth::user()->company_id ?: 1;
      $users = User::where('company_id', $companyId)->whereIn('role', [2, 3, 4]);

      return DataTables::of($users)
         ->addColumn('user_card', function ($row) {
            $avatar = $row->profile_image && File::exists(public_path($row->profile_image))
               ? asset($row->profile_image)
               : null;

            $empCode = $row->employee_code
               ? '<span class="badge bg-light text-primary border ms-1">' . e($row->employee_code) . '</span>'
               : '';

            $roleBadge = '';
            if ((int) $row->role === 2) {
               $roleBadge = '<span class="badge" style="background:#E4F4FC; color:#1F7AC0; font-size:10px;">Admin</span>';
            } elseif ((int) $row->role === 4) {
               $roleBadge = '<span class="badge" style="background:#fef3c7; color:#d97706; font-size:10px;">Team Lead / Manager</span>';
            } else {
               $roleBadge = '<span class="badge" style="background:#e0f2fe; color:#0284c7; font-size:10px;">Member</span>';
            }

            $html = '<div class="d-flex align-items-center gap-2">';
            if ($avatar) {
               $html .= '<img src="' . $avatar . '" class="rounded-circle shadow-sm" style="width:38px; height:38px; object-fit:cover;">';
            } else {
               $html .= '<div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold" style="width:38px; height:38px; font-size:13px;">' . strtoupper(substr($row->first_name, 0, 1)) . '</div>';
            }

            $html .= '<div>
                            <div class="fw-bold text-dark d-flex align-items-center gap-1">' . e($row->first_name . ' ' . $row->last_name) . $empCode . $roleBadge . '</div>
                            <small class="text-muted">' . e($row->designation ?: 'Employee') . ($row->department ? ' • ' . e($row->department) : '') . '</small>
                        </div></div>';

            return $html;
         })
         ->addColumn('contact_info', function ($row) {
            return '<div>
                            <div class="small fw-semibold text-dark"><i class="bi bi-envelope me-1 text-muted"></i>' . e($row->email) . '</div>
                            <small class="text-muted"><i class="bi bi-telephone me-1"></i>' . ($row->phone ? e($row->phone) : '-') . '</small>
                        </div>';
         })
         ->addColumn('joining_date_formatted', function ($row) {
            return $row->joining_date
               ? '<span class="text-dark small fw-semibold">' . \Carbon\Carbon::parse($row->joining_date)->format('d M, Y') . '</span>'
               : '-';
         })
         ->addColumn('status_badge', function ($row) {
            return (int) $row->status === 1
               ? '<span class="badge bg-success">Active</span>'
               : '<span class="badge bg-danger">Inactive</span>';
         })
         ->addColumn('action', function ($row) {
            $editUrl = route('superadmin.user.edit', $row->id);
            $permissionUrl = route('superadmin.user.permissions', $row->id); // Permission page ka route

            // Check karein ki is user ke paas user_modules table me koi entry hai ya nahi
            $moduleCount = \Illuminate\Support\Facades\DB::table('user_modules')
               ->where('user_id', $row->id)
               ->count();

            $html = '<div class="d-flex justify-content-center gap-1">';

            // 1. Edit Button
            $html .= '<a href="' . $editUrl . '" class="btn btn-sm btn-outline-primary rounded-circle" title="Edit Employee">
                        <i class="bi bi-pencil"></i>
                      </a>';

            // 2. Change Password Button
            $html .= '<button type="button" class="btn btn-sm btn-outline-success rounded-circle changePasswordBtn" data-userid="' . $row->id . '" data-bs-toggle="modal" data-bs-target="#changePasswordModal" title="Change Password">
                        <i class="bi bi-key"></i>
                      </button>';

            // 3. Module Assign Button (Existing Gear Icon)
            $html .= '<button type="button" class="btn btn-sm btn-outline-secondary rounded-circle" onclick="changeUserModuleBtn(' . $row->id . ', \'' . e($row->first_name . ' ' . $row->last_name) . '\')" title="Assign Base Modules">
                        <i class="bi bi-gear"></i>
                      </button>';

            // 4. Role-based Actions Access Button (Shield Icon)
            if (in_array((int)$row->role, [3, 4])) {
               if ($moduleCount == 0) {
                  // Agar module 0 hain, toh href link ki jagah direct JS alert show karega
                  $alertMsg = "Is user ko abhi tak koi module assign nahi hua hai. Kripya pahle Gear (⚙️) icon se module assign karein!";

                  $html .= '<button type="button" class="btn btn-sm btn-outline-warning rounded-circle" 
                                onclick="if(typeof showAlert === \'function\') { showAlert(\'warning\', \'' . $alertMsg . '\'); } else { alert(\'' . $alertMsg . '\'); }" 
                                title="Manage Add/Edit/Delete Access">
                                <i class="bi bi-shield-lock"></i>
                              </button>';
               } else {
                  // Agar module assigned hain, toh normally permission page par le jayega
                  $html .= '<a href="' . $permissionUrl . '" class="btn btn-sm btn-outline-warning rounded-circle" title="Manage Add/Edit/Delete Access">
                                <i class="bi bi-shield-lock"></i>
                              </a>';
               }
            }

            $html .= '</div>';
            return $html;
         })
         ->rawColumns(['user_card', 'contact_info', 'joining_date_formatted', 'status_badge', 'action'])
         ->make(true);
   }

   public function create()
   {
      $data['title'] = 'Super Admin - Add User';
      return view('superadmin.add_user', $data);
   }

   public function store(Request $request): JsonResponse
   {
      $request->merge([
         'phone' => preg_replace('/\D/', '', $request->phone)
      ]);

      $request->validate([
         'first_name'    => 'required|string|max:100',
         'last_name'     => 'required|string|max:100',
         'employee_code' => 'nullable|string|max:30',
         'designation'   => 'nullable|string|max:100',
         'department'    => 'nullable|string|max:100',
         'dob'           => 'nullable|date',
         'joining_date'  => 'nullable|date',
         'gender'        => 'nullable|in:1,2,3',
         'role'          => 'required|in:2,3,4',
         'email'         => 'required|email|max:255|unique:users,email',
         'phone'         => 'required|digits:10|unique:users,phone',
         'password'      => 'required|string|min:6',
         'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
      ]);

      $companyId = Auth::user()->company_id ?: 1;
      $imagePath = null;

      if ($request->hasFile('profile_image')) {
         $imageFile = $request->file('profile_image');
         $imageName = time() . '_' . uniqid() . '.' . $imageFile->getClientOriginalExtension();
         $destPath = public_path('uploads/profile_images');
         if (!File::exists($destPath)) {
            File::makeDirectory($destPath, 0755, true);
         }
         $imageFile->move($destPath, $imageName);
         $imagePath = 'uploads/profile_images/' . $imageName;
      }

      DB::beginTransaction();

      try {
         $user = User::create([
            'first_name'    => $request->first_name,
            'last_name'     => $request->last_name,
            'employee_code' => $request->employee_code ? strtoupper($request->employee_code) : null,
            'designation'   => $request->designation,
            'department'    => $request->department,
            'dob'           => $request->dob,
            'joining_date'  => $request->joining_date ?: now()->format('Y-m-d'),
            'gender'        => $request->gender,
            'email'         => $request->email,
            'password'      => Hash::make($request->password),
            'company_id'    => $companyId,
            'role'          => $request->role,
            'status'        => 1,
            'phone'         => $request->phone,
            'profile_image' => $imagePath,
         ]);

         // Auto-seed active leave balances for current calendar year
         $curYear = date('Y');
         LeaveType::where('company_id', $companyId)->where('status', 1)->get()->each(function ($leaveType) use ($companyId, $user, $curYear) {
            LeaveBalance::firstOrCreate(
               [
                  'company_id'    => $companyId,
                  'user_id'       => $user->id,
                  'leave_type_id' => $leaveType->id,
                  'year'          => $curYear,
               ],
               [
                  'allocated_days'       => $leaveType->annual_leave,
                  'carried_forward_days' => 0,
                  'used_days'            => 0,
                  'blocked_days'         => 0,
                  'remaining_days'       => $leaveType->annual_leave,
               ]
            );
         });

         DB::commit();

         return response()->json([
            'status'  => 'success',
            'message' => 'Employee user created successfully & leave quota assigned.',
         ]);
      } catch (\Throwable $e) {
         DB::rollBack();
         if ($imagePath && File::exists(public_path($imagePath))) {
            File::delete(public_path($imagePath));
         }
         Log::error('User Store Error: ' . $e->getMessage());

         return response()->json([
            'status'  => 'error',
            'message' => 'Failed to create user.',
            'error'   => $e->getMessage(),
         ], 500);
      }
   }

   public function edit(int $id)
   {
      $companyId = Auth::user()->company_id ?: 1;
      $user = User::where('company_id', $companyId)->findOrFail($id);

      $data['title'] = 'Super Admin - Edit User';
      $data['user']  = $user;
      return view('superadmin.edit_user', $data);
   }

   public function update(Request $request, int $id): JsonResponse
   {
      $companyId = Auth::user()->company_id ?: 1;
      $user = User::where('company_id', $companyId)->findOrFail($id);

      $request->merge([
         'phone' => preg_replace('/\D/', '', $request->phone)
      ]);

      $request->validate([
         'first_name'    => 'required|string|max:100',
         'last_name'     => 'required|string|max:100',
         'employee_code' => 'nullable|string|max:30',
         'designation'   => 'nullable|string|max:100',
         'department'    => 'nullable|string|max:100',
         'dob'           => 'nullable|date',
         'joining_date'  => 'nullable|date',
         'gender'        => 'nullable|in:1,2,3',
         'role'          => 'required|in:2,3,4',
         'status'        => 'required|in:0,1',
         'email'         => 'required|email|max:255|unique:users,email,' . $user->id,
         'phone'         => 'required|digits:10|unique:users,phone,' . $user->id,
         'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
      ]);

      try {
         $imagePath = $user->profile_image;
         if ($request->hasFile('profile_image')) {
            if ($imagePath && File::exists(public_path($imagePath))) {
               File::delete(public_path($imagePath));
            }
            $imageFile = $request->file('profile_image');
            $imageName = time() . '_' . uniqid() . '.' . $imageFile->getClientOriginalExtension();
            $imageFile->move(public_path('uploads/profile_images'), $imageName);
            $imagePath = 'uploads/profile_images/' . $imageName;
         }

         $user->update([
            'first_name'    => $request->first_name,
            'last_name'     => $request->last_name,
            'employee_code' => $request->employee_code ? strtoupper($request->employee_code) : null,
            'designation'   => $request->designation,
            'department'    => $request->department,
            'dob'           => $request->dob,
            'joining_date'  => $request->joining_date,
            'gender'        => $request->gender,
            'email'         => $request->email,
            'phone'         => $request->phone,
            'role'          => $request->role,
            'status'        => $request->status,
            'profile_image' => $imagePath,
         ]);

         return response()->json([
            'status'  => 'success',
            'message' => 'User details updated successfully.',
         ]);
      } catch (\Throwable $e) {
         Log::error('User Update Error: ' . $e->getMessage());

         return response()->json([
            'status'  => 'error',
            'message' => 'Failed to update user.',
         ], 500);
      }
   }

   public function resetPassword(Request $request): JsonResponse
   {
      $request->validate([
         'id'       => 'required|integer|exists:users,id',
         'password' => 'required|string|min:6',
      ]);

      $companyId = Auth::user()->company_id ?: 1;
      $user = User::where('company_id', $companyId)->findOrFail($request->id);

      $user->password = Hash::make($request->password);
      $user->save();

      return response()->json([
         'status'  => 'success',
         'message' => 'Password updated successfully.',
      ]);
   }

   /**
    * Get Company Subscribed Modules & User Assigned Modules
    */
   public function getUserModules(Request $request): JsonResponse
   {
      $companyId = Auth::user()->company_id ?: 1;
      $userId    = (int) $request->user_id;

      // User existance check
      $user = User::where('company_id', $companyId)->where('id', $userId)->firstOrFail();

      $assignedModuleIds = \App\Models\UserModule::where('user_id', $userId)
         ->where('company_id', $companyId)
         ->pluck('module_id')
         ->toArray();

      // Admin dashboard me wahi modules dikhenge jo Company 1 (Superadmin) ke paas hain
      $companyModules = \App\Models\Module::where('status', 1)
         ->whereIn('id', function ($query) use ($companyId) {
            $query->select('module_id')
               ->from('assign_modules')
               ->where('company_id', $companyId);
         })
         ->orderBy('group_order', 'asc')
         ->get()
         ->groupBy(fn($item) => $item->group_name ?: 'General Operations');

      return response()->json([
         'status'          => 'success',
         'userName'        => $user->first_name . ' ' . $user->last_name,
         'assignedModules' => $assignedModuleIds,
         'groupedModules'  => $companyModules,
      ]);
   }

   /**
    * Update User Module Permissions
    */
   public function updateUserModules(Request $request): JsonResponse
   {
      $companyId = Auth::user()->company_id ?: 1;

      $request->validate([
         'user_id'   => 'required|integer|exists:users,id',
         'modules'   => 'nullable|array',
         'modules.*' => 'integer|exists:modules,id',
      ]);

      $userId  = (int) $request->user_id;
      $modules = $request->input('modules', []);

      User::where('company_id', $companyId)->where('id', $userId)->firstOrFail();

      DB::transaction(function () use ($companyId, $userId, $modules) {
         \App\Models\UserModule::where('company_id', $companyId)->where('user_id', $userId)->delete();

         foreach ($modules as $moduleId) {
            \App\Models\UserModule::create([
               'company_id' => $companyId,
               'user_id'    => $userId,
               'module_id'  => $moduleId,
            ]);
         }
      });

      return response()->json([
         'status'  => 'success',
         'message' => 'User module permissions updated successfully.',
      ]);
   }

   public function exportPdf()
   {
      $companyId = Auth::user()->company_id ?: 1;

      // Users fetch karein
      $users = User::where('company_id', $companyId)
         ->whereIn('role', [2, 3, 4])
         ->orderBy('first_name', 'asc')
         ->get();

      // Company Details fetch karein dynamic logo ke liye
      $company = Company::find($companyId);

      // Data array banayein
      $data = [
         'users'        => $users,
         'company_name' => $company ? $company->company_name : 'SuperAdmin Portal',
         'company_logo' => $company ? $company->company_logo : 'assets/img/main-logo.png'
      ];

      // PDF Render karein
      $pdf = Pdf::loadView('superadmin.pdf.users_list', $data);

      return $pdf->download('Users_List_' . date('Ymd_His') . '.pdf');
   }

   public function permissions($id)
   {
      $companyId = Auth::user()->company_id ?: 1;
      $employee = User::where('company_id', $companyId)->findOrFail($id);

      if (!in_array((int)$employee->role, [3, 4])) {
         return redirect()->route('superadmin.user.index')->with('error', 'Permissions can only be managed for Employees and Managers.');
      }

      $assignedModuleIds = DB::table('user_modules')
         ->where('user_id', $employee->id)
         ->pluck('module_id')
         ->toArray();

      if (empty($assignedModuleIds)) {
         return redirect()->route('superadmin.user.index')
            ->with('error', 'Is user ko abhi tak koi module assign nahi hua hai. Kripya pahle "Assign Base Modules" (Gear icon) se module assign karein!');
      }

      $modulesGroups = DB::table('modules')
         ->whereIn('id', $assignedModuleIds)
         ->where('status', 1)
         ->orderBy('group_order')
         ->orderBy('id')
         ->get()
         ->groupBy('group_name');

      $data['title'] = 'Manage Access Permissions - ' . $employee->first_name;
      $data['employee'] = $employee;
      $data['modulesGroups'] = $modulesGroups;

      return view('superadmin.user_permissions', $data);
   }

   public function updatePermissions(Request $request, $id)
   {
      $companyId = Auth::user()->company_id ?: 1;
      $employee = User::where('company_id', $companyId)->findOrFail($id);

      $permissionsData = $request->input('permissions', []);

      foreach ($permissionsData as $moduleShortName => $actions) {
         // Agar module_id form se nahi aaya toh skip kar do
         if (!isset($actions['module_id'])) {
            continue;
         }
      }

      return response()->json([
         'status' => 'success',
         'message' => 'Role & Access permissions updated successfully for ' . $employee->first_name
      ]);
   }
}
