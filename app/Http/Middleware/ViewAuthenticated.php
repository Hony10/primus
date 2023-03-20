<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ViewAuthenticated
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
            return redirect('/sign-in');
        }
        $user = User::where('id', $userId)->first();
        if (!$user || $user->enabled <= 0) {
            return redirect('/sign-in');
        }

        // Append the user object to the request
        $request->merge(['user' => $user]);

        // Check if we need to check for specific roles
        if ($roleList !== '') {
            $roleParts = \explode('|', $roleList);
            $userRoles = \explode(',', $user->roles);
            $roleFound = false;

            Log::debug('userRoles', $userRoles);
            Log::debug('roleParts', $roleParts);

            foreach ($roleParts as $role) {
                Log::debug('role: ' . $role);
                if (\in_array($role, $userRoles)) {
                    $roleFound = true;
                    Log::debug('FOUND');
                }
            }

            Log::debug('roleFound: ' . $roleFound);

            if (!$roleFound) {
                return redirect('/');
            }
        }
        return $next($request);
    }
}
