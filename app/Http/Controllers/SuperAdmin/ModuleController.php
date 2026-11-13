<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\Company;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;


class ModuleController extends Controller
{
    public function index()
    {
        $data['title'] = 'SuperAdmin - Modules Management';
        $data['groups'] = Module::whereNotNull('group_name')->groupBy('group_name')->orderByRaw('MIN(group_order) ASC')->pluck('group_name');
        return view('superadmin.view_modules', $data);
    }

    public function datatable(Request $request)
    {
        $modules = Module::query();

        // Filter by Functional Group if selected
        if ($request->filled('group_name')) {
            $modules->where('group_name', $request->group_name);
        }

        return DataTables::of($modules)
            ->addColumn('module_card', function ($row) {
                $iconClass = $row->icon ?: 'bi bi-grid';
                return '<div class="d-flex align-items-center gap-3">
                    <div class="rounded bg-primary-subtle text-primary d-flex align-items-center justify-content-center" style="width:38px; height:38px; font-size:18px; flex-shrink:0;">
                        <i class="' . e($iconClass) . '"></i>
                    </div>
                    <div>
                        <div class="fw-bold text-dark">' . e($row->name) . '</div>
                        <small class="text-muted">' . e($row->description ?: 'No description provided') . '</small>
                    </div>
                </div>';
            })
            ->addColumn('group_badge', function ($row) {
                return '<span class="badge bg-light text-dark border fw-medium">' . e($row->group_name ?: 'General') . '</span>';
            })
            ->addColumn('short_name_badge', function ($row) {
                return '<span class="badge bg-light text-primary border font-monospace fw-semibold">' . e($row->short_name) . '</span>';
            })
            ->addColumn('dashboard_url_link', function ($row) {
                if (empty($row->dashboard_url)) {
                    return '<span class="text-muted small">-</span>';
                }
                return '<a href="' . url($row->dashboard_url) . '" target="_blank" class="small text-primary text-decoration-none fw-semibold">
                    <i class="bi bi-box-arrow-up-right me-1"></i>/' . e(ltrim($row->dashboard_url, '/')) . '
                </a>';
            })
            ->addColumn('status_badge', function ($row) {
                return (int) $row->status === 1
                    ? '<span class="badge bg-success">Active</span>'
                    : '<span class="badge bg-danger">Inactive</span>';
            })
            ->addColumn('action', function ($row) {
                $checked = (int) $row->status === 1 ? 'checked' : '';
                return '<div class="form-check form-switch d-flex justify-content-center mb-0">
                    <input class="form-check-input status-toggle" type="checkbox" data-id="' . $row->id . '" ' . $checked . ' style="cursor:pointer; width:38px; height:20px;">
                </div>';
            })
            ->rawColumns(['module_card', 'group_badge', 'short_name_badge', 'dashboard_url_link', 'status_badge', 'action'])
            ->make(true);
    }

    public function exportPdf(Request $request)
    {
        $modulesQuery = Module::query();

        // Agar user ne dropdown se koi group select kiya hai toh filter lagayenge
        if ($request->filled('group_name')) {
            $modulesQuery->where('group_name', $request->group_name);
        }

        $modules = $modulesQuery->orderBy('group_order', 'asc')->get();

        // Logged-in admin ki company fetch karein (Dynamic Logo & Name ke liye)
        $company = null;
        if (Auth::check() && Auth::user()->company_id) {
            $company = Company::find(Auth::user()->company_id);
        }

        // Data set karein (Agar company nahi milti toh default value use hogi)
        $data = [
            'modules'      => $modules,
            'company_name' => $company ? $company->company_name : 'SuperAdmin Portal',
            'company_logo' => $company ? $company->company_logo : 'assets/img/main-logo.png'
        ];

        $pdf = Pdf::loadView('superadmin.pdf.modules_list', $data);

        return $pdf->download('Modules_List_' . date('Ymd_His') . '.pdf');
    }

    public function updateStatus(Request $request): JsonResponse
    {
        $request->validate([
            'id'     => 'required|integer|exists:modules,id',
            'status' => 'required|in:0,1',
        ]);

        $module = Module::findOrFail($request->id);
        $module->status = (int) $request->status;
        $module->save();

        return response()->json([
            'status'  => 'success',
            'message' => "Module '{$module->name}' status updated successfully.",
        ]);
    }
}
