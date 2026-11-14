<?php

namespace App\Http\Controllers\Um;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
   /**
    * 1. Family Members List (Role = 3)
    */
   public function index(Request $request)
   {
      $data['title'] = 'UM - Family Members Management';
      $data['role'] = 3;

      return view('um.view_user', $data);
   }

   /**
    * 2. House Admins List (Role = 2)
    */
   public function admin(Request $request)
   {
      $data['title'] = 'UM - House Admins Management';
      $data['role'] = 2;

      return view('um.view_user', $data);
   }

   /**
    * Unified Datatable for both roles (Company-wise relation included)
    */
   public function datatable(Request $request)
   {
      $role = $request->get('role', 3);

      // Company relation ke sath data fetch kar rahe hain (future-proof company-wise display)
      $users = User::with('company')->where('role', $role);

      return DataTables::of($users)
         ->addColumn('user_details', function ($row) {
            $name = trim(($row->first_name ?? '') . ' ' . ($row->last_name ?? '')) ?: ($row->name ?? 'N/A');
            return '<div class="d-flex align-items-center gap-3">
               <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center fw-bold" style="width:38px; height:38px; font-size:15px; flex-shrink:0;">
                  ' . strtoupper(substr($name, 0, 1)) . '
               </div>
               <div>
                  <div class="fw-bold text-dark">' . e($name) . '</div>
                  <small class="text-muted">' . e($row->email) . '</small>
               </div>
            </div>';
         })
         ->addColumn('company_name', function ($row) {
            return $row->company ? '<span class="fw-semibold text-dark"><i class="bi bi-building me-1 text-primary"></i>' . e($row->company->company_name) . '</span>' : '<span class="text-muted">N/A</span>';
         })
         ->addColumn('contact_info', function ($row) {
            return '<span class="small text-muted">' . e($row->contact_no ?? $row->phone ?? 'No Contact') . '</span>';
         })
         ->addColumn('status_badge', function ($row) {
            return (int) $row->status === 1
               ? '<span class="badge bg-success">Active</span>'
               : '<span class="badge bg-danger">Inactive</span>';
         })
         ->addColumn('action', function ($row) {
            $checked = (int) $row->status === 1 ? 'checked' : '';
            return '<div class="d-flex align-items-center justify-content-center gap-2">
               <a href="' . route('um.users.edit', $row->id) . '" class="btn btn-sm btn-outline-primary" title="Edit"><i class="bi bi-pencil-square"></i></a>
               <div class="form-check form-switch mb-0">
                  <input class="form-check-input status-toggle" type="checkbox" data-id="' . $row->id . '" ' . $checked . ' style="cursor:pointer; width:38px; height:20px;">
               </div>
            </div>';
         })
         ->rawColumns(['user_details', 'company_name', 'contact_info', 'status_badge', 'action'])
         ->make(true);
   }

   /**
    * Show Create Form
    */
   public function create(Request $request)
   {
      $data['title'] = 'UM - Add New User/Admin';
      $data['companies'] = \App\Models\Company::where('status', 1)->orderBy('company_name')->get();
      $data['role'] = $request->get('role', 3);

      return view('um.add_user', $data);
   }

   /**
    * Store New User/Admin
    */
   public function store(Request $request)
   {
      $request->validate([
         'first_name' => 'required|string|max:255',
         'email'      => 'required|email|unique:users,email',
         'password'   => 'required|min:6',
         'company_id' => 'required|exists:companies,id',
         'role'       => 'required|in:2,3',
      ]);

      User::create([
         'first_name' => $request->first_name,
         'last_name'  => $request->last_name,
         'name'       => $request->first_name . ' ' . $request->last_name,
         'email'      => $request->email,
         'password'   => Hash::make($request->password),
         'company_id' => $request->company_id,
         'role'       => $request->role,
         'contact_no' => $request->contact_no,
         'status'     => 1,
      ]);

      $redirectRoute = $request->role == 2 ? 'um.admins.index' : 'um.users.index';

      return redirect()->route($redirectRoute)->with('success', 'User created successfully.');
   }

   /**
    * Show Edit Form
    */
   public function edit($id)
   {
      $data['title'] = 'UM - Edit User/Admin';
      $data['user'] = User::findOrFail($id);
      $data['companies'] = \App\Models\Company::where('status', 1)->orderBy('company_name')->get();

      return view('um.edit_user', $data);
   }

   /**
    * Update User/Admin
    */
   public function update(Request $request, $id)
   {
      $user = User::findOrFail($id);

      $request->validate([
         'first_name' => 'required|string|max:255',
         'email'      => 'required|email|unique:users,email,' . $id,
         'company_id' => 'required|exists:companies,id',
         'role'       => 'required|in:2,3',
      ]);

      $user->first_name = $request->first_name;
      $user->last_name = $request->last_name;
      $user->name = $request->first_name . ' ' . $request->last_name;
      $user->email = $request->email;
      if ($request->filled('password')) {
         $user->password = Hash::make($request->password);
      }
      $user->company_id = $request->company_id;
      $user->role = $request->role;
      $user->contact_no = $request->contact_no;
      $user->save();

      $redirectRoute = $user->role == 2 ? 'um.admins.index' : 'um.users.index';

      return redirect()->route($redirectRoute)->with('success', 'User updated successfully.');
   }

   /**
    * Update Status AJAX
    */
   public function updateStatus(Request $request): JsonResponse
   {
      $request->validate([
         'id'     => 'required|integer|exists:users,id',
         'status' => 'required|in:0,1',
      ]);

      $user = User::findOrFail($request->id);
      $user->status = (int) $request->status;
      $user->save();

      return response()->json([
         'status'  => 'success',
         'message' => 'Status updated successfully.',
      ]);
   }
}
