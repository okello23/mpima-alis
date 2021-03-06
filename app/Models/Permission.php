<?php

namespace  App\Models;

use Spatie\Permission\Models\Permission as perm;

class Permission extends perm
{
    /**
     * Checks if the Permission has a Role by its name.
     *
     * @param string $name Role name.
     *
     * @return bool
     */
    public function hasRole($name)
    {
        foreach ($this->roles as $role) {
            if ($role->name == $name) {
                return true;
            }
        }

        return false;
    }
}
