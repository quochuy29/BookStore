<?php

namespace App\Http\Controllers;

use App\Models\RoleHasPermissions;
use App\Models\UserHasRoles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Permissions extends Controller
{
    public function index()
    {
        $role = UserHasRoles::with('roles')->where('user_id', Auth::id())->first();
        $permission = RoleHasPermissions::with('permission')->where('role_id', $role->role_id)->get();
        return response()->json(['role' => $role, 'permission' => $permission]);
    }
}
