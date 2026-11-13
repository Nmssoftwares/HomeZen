<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class CompanySettingController extends Controller
{
    /**
     * Display the company profile settings form.
     */
    public function index()
    {
        $data['title'] = 'Company Profile & Settings';

        // Superadmin ki logged-in company fetch karna
        $companyId = Auth::user()->company_id;
        $data['company'] = Company::findOrFail($companyId);

        return view('superadmin.view_company_setting', $data);
    }

    /**
     * Update the company profile details & logo.
     */
    public function update(Request $request): JsonResponse
    {
        $companyId = Auth::user()->company_id;
        $company   = Company::findOrFail($companyId);

        $validated = $request->validate([
            'company_name'    => 'required|string|max:255',
            'company_code'    => 'nullable|string|max:10',
            'company_address' => 'required|string',
            'gst_number'      => 'nullable|string|max:50',
            'pan_number'      => 'nullable|string|max:50',
            'company_email'   => 'nullable|email|max:150',
            'company_phone'   => 'nullable|string|max:30',
            'company_website' => 'nullable|string|max:150',
            'timezone'        => 'required|string|max:50',
            'date_format'     => 'required|string|max:20',
            'company_logo'    => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:2048',
        ]);

        try {
            // Handle Logo Upload if present
            if ($request->hasFile('company_logo')) {
                $file = $request->file('company_logo');
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $destinationPath = public_path('uploads/') .  $company->id . '/logo';

                if (!File::isDirectory($destinationPath)) {
                    File::makeDirectory($destinationPath, 0777, true, true);
                }

                // Purana logo delete karna agar exist karta ho
                if (!empty($company->company_logo) && File::exists(public_path($company->company_logo))) {
                    File::delete(public_path($company->company_logo));
                }

                $file->move($destinationPath, $filename);
                $validated['company_logo'] = 'uploads/'.  $company->id . '/logo/' . $filename;
            }

            $company->update($validated);

            return response()->json([
                'status'  => 'success',
                'message' => 'Company profile & settings updated successfully.',
                'logo_url' => !empty($company->company_logo) ? asset($company->company_logo) : null,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Failed to update company settings: ' . $e->getMessage(),
            ], 500);
        }
    }
}
