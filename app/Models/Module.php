<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    protected $fillable = [
        'name',
        'short_name',
        'description',
        'icon',
        'dashboard_url',
        'status',
    ];

    public function assignModules()
    {
        return $this->hasMany(AssignModule::class, 'module_id');
    }
}
