<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserModulePermission extends Model
{
    protected $fillable = [
        'user_role_id',
        'module_permission_id',
        'can_view',
        'can_create',
        'can_edit',
        'can_delete',
    ];

    protected $casts = [
        'can_view' => 'boolean',
        'can_create' => 'boolean',
        'can_edit' => 'boolean',
        'can_delete' => 'boolean',
    ];

    public function userRole()
    {
        return $this->belongsTo(UserRole::class);
    }

    public function modulePermission()
    {
        return $this->belongsTo(ModulePermission::class);
    }

    public function module()
    {
        return $this->modulePermission->module;
    }
}
