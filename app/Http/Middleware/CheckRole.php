<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Models\Role;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  mixed  ...$roles
     * @return mixed
     */
    public function handle($request, Closure $next, ...$roles)
    {
        if (auth()->check()) {
            $userRoleId = auth()->user()->role_id;
            $userRole = Role::find($userRoleId)->name;

            if (in_array($userRole, $roles)) {
                return $next($request);
            }
        }
        return response()->json(['error' => 'Unauthorized for this action'], 403);
    }
}