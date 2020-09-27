<?php

namespace Farhoudi\Rbac;

use Illuminate\Database\Eloquent\Collection;
use Farhoudi\Rbac\Models\Role;

/**
 * Trait Rbac
 */
trait Rbac {

    private $rbacIsLoaded = false;

    private $roles;

    private $permissions;

    /**
     * @param string|array $role
     * @return bool
     */
    public function hasRole($role) {
        if (!$this->rbacIsLoaded) {
            $this->loadPermissions();
            $this->rbacIsLoaded = true;
        }

        if (is_array($role)) {
            foreach ($role as $r) {
                if (!$this->hasRole($r)) {
                    return false;
                }
            }
            return true;
        }

        $roles = explode('|', $role);
        foreach ($roles as $role) {
            if (!empty($this->roles->where('alias', $role)->first())) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param string|array $permission
     * @return bool
     */
    public function hasPermission($permission) {
        if (!$this->rbacIsLoaded) {
            $this->loadPermissions();
            $this->rbacIsLoaded = true;
        }

        if (is_array($permission)) {
            foreach ($permission as $p) {
                if (!$this->hasPermission($p)) {
                    return false;
                }
            }
            return true;
        }

        $permissions = explode('|', $permission);
        foreach ($permissions as $permission) {
            if (!empty($this->permissions->where('alias', $permission)->first())) {
                return true;
            }
        }
        return false;
    }

    /**
     * Assign Role to User
     *
     * @param string|Role $role
     */
    public function assignRole($role) {
        if (is_array($role)) {
            foreach ($role as $item) {
                $this->assignRole($item);
            }
        }

        if ($role instanceof Role) {
            if (!$this->hasRole($role->alias)) {
                $this->roles()->save($role);
            }
        } else if (is_string($role)) {
            $role = Role::where('alias', $role)->first();
            if (!empty($role)) {
                if (!$this->hasRole($role->alias)) {
                    $this->roles()->save($role);
                }
            }
        }

        if ($this->rbacIsLoaded) {
            $this->loadPermissions();
        }
    }

    public function loadPermissions() {
        $this->roles = $this->roles()->with(['permissions'])->get();
        $this->permissions = new Collection();
        foreach ($this->roles as $key => $role) {
            foreach ($role->permissions as $permission) {
                if (empty($this->permissions->where('name', $permission->name)->first()) && empty($this->permissions->where('alias', $permission->alias)->first())) {
                    $this->permissions->add($permission);
                }
                unset($role->permissions);
            }
        }
        return $this->permissions;
    }

    public function roles() {
        return $this->belongsToMany(Role::class, 'rbac_role_user', 'user_id', 'role_id');
    }

}