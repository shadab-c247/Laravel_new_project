<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModulePermission extends Model
{
    protected $fillable = [
        'module_id',
        'name',
        'slug',
        'action',
    ];

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function userModulePermissions()
    {
        return $this->hasMany(UserModulePermission::class);
    }
}
