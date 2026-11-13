<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = [
        'company_name',
        'company_code',
        'company_address',
        'gst_number',
        'pan_number',
        'company_logo',
        'company_email',
        'company_phone',
        'company_website',
        'timezone',
        'date_format',
        'status'
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'company_id');
    }

    public function assignModules()
    {
        return $this->hasMany(AssignModule::class, 'company_id');
    }
        /**
     * Logo URL for the logged-in user's company.
     * Falls back to the default Offnexa logo (e.g. Super Admin or no logo uploaded).
     */
    public static function currentLogoUrl(): string
    {
        static $url = null;
        if ($url !== null) {
            return $url;
        }

        $default = asset('assets/img/offnexa-logo-v2.png');
        $user    = auth()->user();

        // Super Admin (role 1) and guests always see the Offnexa logo
        if (!$user || (int) $user->role === 1 || !$user->company_id) {
            return $url = $default;
        }

        $path = static::whereKey($user->company_id)->value('company_logo');

        if ($path && file_exists(public_path($path))) {
            // ?v=... makes the browser show a new logo immediately after it changes
            return $url = asset($path) . '?v=' . filemtime(public_path($path));
        }

        return $url = $default;
    }
        /**
     * Browser-tab icon: the company logo if one is uploaded, otherwise the default favicon.
     */
    public static function currentFaviconUrl(): string
    {
        $logo = static::currentLogoUrl();

        return $logo === asset('assets/img/offnexa-logo-v2.png')
            ? '/favicon.ico?v=5'
            : $logo;
    }
}
