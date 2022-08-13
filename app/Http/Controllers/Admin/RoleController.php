<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Service\RoleHasService;
use App\Models\RoleHasPermissions;
use App\Models\Roles;
use App\Models\User;
use App\Models\UserHasRoles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class RoleController extends Controller
{
    public $roleHasService;

    public function __construct(RoleHasService $roleHasService)
    {
        $this->roleHasService = $roleHasService;
    }
    public function addForm(Request $request)
    {
        $model = new Roles();
        $message = [
            'name.required' => "Tên vai trò không được trống",
            'name.unique' => "Tên vai trò không được trùng",
            'permission.*.required' => "Hãy chọn quyền"
        ];
        $validator = Validator::make(
            $request->all(),
            [
                'name' => [
                    'required',
                    Rule::unique('roles')
                ],
                'permission.*' => [
                    'required'
                ]
            ],
            $message
        );
        if ($validator->fails()) {
            return response()->json(['status' => 422, 'error' => $validator->errors(), 'tu' => $request->all()], 422);
        }
        $model->fill($request->all());

        $model->save();

        if ($request->permission) {
            $per = array_map('intval', explode(',', $request->permission));
            $this->roleHasService->createRole($per, $model->id);
        }

        return response()->json(['status' => "đã thành công"]);
    }
    public function editForm($id)
    {
        return Roles::with('hasPermission')->find($id);
    }

    public function update($id, Request $request)
    {
        $model = Roles::find($id);
        $message = [
            'name.required' => "Tên vai trò không được trống",
            'name.unique' => "Tên vai trò không được trùng",
            'permission.*.required' => "Hãy chọn quyền"
        ];
        $validator = Validator::make(
            $request->all(),
            [
                'name' => [
                    'required',
                    Rule::unique('roles')->ignore($id)
                ],
                'permission.*' => [
                    'required'
                ]
            ],
            $message
        );
        if ($validator->fails()) {
            return response()->json(['status' => 422, 'error' => $validator->errors(), 'tu' => $request->all()], 422);
        }

        $rolePermission = RoleHasPermissions::where('role_id', $id)->pluck('permission_id')->toArray();
        $permission = array_map('intval', explode(',', $request->permission));
        $model->fill($request->all());

        $model->save();

        if ($request->permission) {
            $this->roleHasService->EditPermission($permission, $rolePermission, $id);
        }

        return response()->json(['status' => "đã thành công"]);
    }

    public function delete($id)
    {
        return UserHasRoles::find($id)->delete();
    }

    public function getRoleUser()
    {
        $user = array_unique(UserHasRoles::pluck('user_id')->toArray());

        $users = User::whereNotIn('id', $user)->get();
        $roles = Roles::get();
        return response()->json(['role' => $roles, 'user' => $users]);
    }

    public function giveRole(Request $request)
    {
        $model = new UserHasRoles();

        $model->fill($request->all());
        $model->save();

        return response()->json(['status' => "Đã thành công"]);
    }

    public function editRole($id)
    {
        $model = UserHasRoles::select('users.name as nameUser', 'users.id as idUser', 'roles.name as nameRole', 'roles.id as idRole', 'userhasroles.*')
            ->join('users', 'users.id', '=', 'userhasroles.user_id')
            ->join('roles', 'roles.id', '=', 'userhasroles.role_id')
            ->where('userhasroles.id', $id)
            ->first();
        return $model;
    }

    public function updateUserRole($id, Request $request)
    {
        $model = UserHasRoles::find($id);

        $model->fill($request->all());
        $model->save();

        return response()->json(['status' => "Đã thành công"]);
    }

    public function deleteRole($id)
    {
        $model = Roles::find($id);
        $model->hasUser()->delete();
        $model->hasPermission()->delete();
        $model->delete();
        return response()->json(['status' => "Xoá thành công"]);
    }
}
