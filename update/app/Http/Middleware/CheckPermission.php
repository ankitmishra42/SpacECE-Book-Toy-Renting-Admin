<?php

namespace App\Http\Middleware;

use App\Repositories\UserRepository;
use Closure;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = (new UserRepository())->find(auth()->id());
        $userPermissions = $user->getPermissionNames()->toArray();
        $userRole = $user->getRoleNames()->toArray()[0];
        $role = Role::where('name', $userRole)->first();
        $rolePermissions = $role->getPermissionNames()->toArray();

        $allPermissions = array_merge($userPermissions, $rolePermissions);
        $allPermissions = array_unique($allPermissions);

        $requestRoute = \request()->route()->getName();

        if (in_array($requestRoute, $allPermissions) || $userRole === 'root') {
            return $next($request);
        }

        return back()->with('error', 'Sorry, You have no permission');
    }
}
