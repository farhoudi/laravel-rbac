<?php

namespace Farhoudi\Rbac\Models;

use Illuminate\Database\Eloquent\Model;
use Farhoudi\User\Models\User;

/**
 * Class Role
 * @package Farhoudi\Rbac\Models
 */
class Role extends Model {

    public $table = 'rbac_roles';

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
        'alias' => 'nullable|string|unique:rbac_roles,alias',
        'description' => 'nullable|string',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'description',
    ];

    public function permissions() {
        return $this->belongsToMany(Permission::class, 'rbac_permission_role', 'role_id', 'permission_id');
    }

    public function users() {
        return $this->belongsToMany(User::class, 'rbac_role_user', 'role_id', 'user_id');
    }

    public function attachPermission($permission) {
        if (is_array($permission)) {
            foreach ($permission as $item) {
                $this->attachPermission($item);
            }
        }

        if ($permission instanceof Permission) {
            $this->permissions()->save($permission);
        } else if (is_string($permission)) {
            $permission = Permission::where('alias', $permission)->first();
            if (!empty($permission)) {
                $this->permissions()->save($permission);
            }
        }
    }

}
