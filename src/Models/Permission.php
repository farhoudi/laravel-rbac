<?php

namespace Farhoudi\Rbac\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Permission
 * @package Farhoudi\Rbac\Models
 */
class Permission extends Model {

    public $table = 'rbac_permissions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'alias',
        'description',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'name' => 'string',
        'alias' => 'string',
        'description' => 'string',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required|string',
        'alias' => 'nullable|string|unique:rbac_permissions,alias',
        'description' => 'nullable|string',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'description',
    ];

    public function roles() {
        return $this->belongsToMany(Role::class, 'rbac_permission_role', 'permission_id', 'role_id');
    }

}
