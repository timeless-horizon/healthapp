<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */


    public function handle(Request $request, Closure $next, string $role)
    {
        $user = $request->user();

        // Check if user is logged in
        if (!$user) {
            return abort(403, 'Unauthorized action.');
        }

        // Allow access if user role matches OR if the role allows multiple roles
        $allowedRoles = explode(',', $role); // Split roles for multiple access

        if (!in_array($user->user_role, $allowedRoles)) {
            return abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
