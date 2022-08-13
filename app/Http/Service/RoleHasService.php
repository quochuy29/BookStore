<?php

namespace App\Http\Service;

use App\Models\RoleHasPermissions;
use App\Models\Roles;
use App\Models\UserHasRoles;

class RoleHasService
{
    public function createRole($arr = [], $id = null)
    {
        foreach ($arr as $ar) {
            $RolePermission = new RoleHasPermissions();
            $RolePermission->role_id = $id;
            $RolePermission->permission_id = $ar;
            $RolePermission->save();
        }
    }

    public function EditPermission($permisison = [], $arrayPerPro = [], $id)
    {

        if (!empty(array_diff($permisison, $arrayPerPro))) {
            if (count($permisison) == count($arrayPerPro)) {

                $perMerge = array_merge($arrayPerPro, $permisison);
                $perUnique = array_unique($perMerge);
                $perDiffKey = array_diff_key($perMerge, $perUnique);
                $perDiff = array_values(array_diff($perUnique, $perDiffKey));

                $perDelete = RoleHasPermissions::where('role_id', $id)
                    ->where('permission_id', $perDiff[0])
                    ->delete();

                $rolePermission = new RoleHasPermissions();
                $rolePermission->role_id = $id;
                $rolePermission->permission_id = $perDiff[1];
                $rolePermission->save();
            } else {
                foreach (array_diff($permisison, $arrayPerPro) as $item) {
                    $rolePermission = new RoleHasPermissions();
                    $rolePermission->role_id = $id;
                    $rolePermission->permission_id = $item;
                    $rolePermission->save();
                }
            }
        } else {

            foreach (array_diff($arrayPerPro, $permisison) as $item) {
                $arrayPerPro = RoleHasPermissions::where('role_id', $id)
                    ->where('permission_id', $item)
                    ->delete();
            }
        }
    }

    public function addRoleUser($user_id, $role_id)
    {
        if ($user_id) {
            $model = new UserHasRoles();
            if ($role_id) {
                $model->user_id = $user_id;
                $model->role_id = $role_id;
                $model->save();
            } else {
                $model->user_id = $user_id;
                $model->role_id = 2;
                $model->save();
            }
        }
    }
}
