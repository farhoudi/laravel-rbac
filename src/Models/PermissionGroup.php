<?php

namespace Farhoudi\Rbac\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class PermissionGroup
 * @package Farhoudi\Rbac\Models
 */
class PermissionGroup extends Model {

    public $table = 'rbac_permission_groups';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required|unique:rbac_permissions,alias',
        'description' => 'nullable|string',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'description',
    ];

    public function permissions() {
        return $this->hasMany(Permission::class, 'group_id', 'id');
    }

}
