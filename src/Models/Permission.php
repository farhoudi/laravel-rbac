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
        'group_id',
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
        'group_id' => 'integer',
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
        'group_id' => 'nullable|integer|exists:rbac_permission_groups,id',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'description',
    ];

    public function roles() {
        return $this->belongsToMany(Role::class, 'rbac_permission_role', 'permission_id', 'role_id');
    }

    public function group() {
        return $this->belongsTo(PermissionGroup::class, 'group_id', 'role_id');
    }

}
