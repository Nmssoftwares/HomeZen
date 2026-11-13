<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssignModule extends Model
{
    protected $fillable = [
        'company_id',
        'module_id',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function module()
    {
        return $this->belongsTo(Module::class, 'module_id');
    }
}
