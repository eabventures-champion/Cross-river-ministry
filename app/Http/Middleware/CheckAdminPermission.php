<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdminPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  string  $permission
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        $user = $request->user();

        if (! $user || ! $user->isAdmin()) {
            return redirect()->route('admin.login')->with('error', 'Administrator access required.');
        }

        if (! $user->hasPermission($permission)) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'You do not have permission to access this feature.'], 403);
            }

            return redirect()->route('admin.dashboard')->with('error', 'Access restricted: You do not have permission to manage this section. Contact your super administrator.');
        }

        return $next($request);
    }
}
