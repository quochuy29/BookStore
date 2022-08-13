<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Service\PermissionService;
use App\Models\Permissions;
use App\Models\RoleHasPermissions;
use App\Models\Roles;
use App\Models\User;
use App\Models\UserHasRoles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PermissionController extends Controller
{
    protected $permission;
    public function __construct(PermissionService $permission)
    {
        $this->permission = $permission;
    }
    
    public function index(Roles $roles)
    {
        $role = UserHasRoles::select('userhasroles.id as id','users.name as nameUser', 'roles.name as nameRole')
            ->join('users', 'users.id', '=', 'userhasroles.user_id')
            ->join('roles', 'roles.id', '=', 'userhasroles.role_id')
            ->get();
        $permission = $roles->with('permission')->get();
        return response()->json(['role' => $role, 'permission' => $permission]);
    }

    public function getPermission(Permissions $permission)
    {
        return $permission->get();
    }

    public function addForm(Request $request)
    {
        $model = new Permissions();
        $message = [
            'name.required' => "Tên quyền không được trống",
            'name.unique' => "Tên quyền không được trùng",
        ];
        $validator = Validator::make(
            $request->all(),
            [
                'name' => [
                    'required',
                    Rule::unique('permissions')
                ],
            ],
            $message
        );
        if ($validator->fails()) {
            return response()->json(['status' => 422, 'error' => $validator->errors(), 'tu' => $request->all()], 422);
        }
        $model->fill($request->all());

        $model->save();

        return response()->json(['status' => "đã thành công"]);
    }

    public function editForm($id, Request $request)
    {
        $model = Permissions::find($id);
        $message = [
            'name.required' => "Tên quyền không được trống",
            'name.unique' => "Tên quyền không được trùng",
        ];
        $validator = Validator::make(
            $request->all(),
            [
                'name' => [
                    'required',
                    Rule::unique('permissions')->ignore($id)
                ],
            ],
            $message
        );
        if ($validator->fails()) {
            return response()->json(['status' => 422, 'error' => $validator->errors(), 'tu' => $request->all()], 422);
        }
        $model->fill($request->all());

        $model->save();

        return response()->json(['status' => "đã thành công"]);
    }

    public function delete($id)
    {
        $model = Permissions::find($id);
        $model->roles()->detach();
        return $model->delete();
    }
}
