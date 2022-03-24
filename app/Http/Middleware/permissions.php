<?php

namespace App\Http\Middleware;

use App\Models\Roles;
use App\Models\UserHasRoles;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class permissions
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $roles)
    {
        $roles = UserHasRoles::pluck('user_id')->toArray();
        if(in_array(Auth::id(),$roles)){
            return $next($request);
        }else{
            return response()->json(['status'=>"đéo có tuổi"]);
        }
    }
}
