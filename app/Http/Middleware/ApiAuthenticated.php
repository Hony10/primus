<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;

class ApiAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string $roleList
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $roleList='')
    {
        // If not logged in, redirect to sign in page
        $userId = $request->session()->get('user', false);
        if (!$userId) {
            abort(403, 'Access denied');
        }
        $user = User::where('id', $userId)->first();
        if (!$user || $user->enabled <= 0) {
            abort(403, 'Access denied');
        }

        // Check if we need to check for specific roles
        if ($roleList !== '') {
            $roleParts = \explode(',', $roleList);
            $userRoles = \explode(',', $user->roles);
            $roleFound = false;

            foreach ($roleParts as $role) {
                if (\in_array($role, $userRoles)) {
                    $roleFound = true;
                }
            }

            if (!$roleFound === false) {
                abort(403, 'Access denied');
            }
        }
        return $next($request);
    }
}
