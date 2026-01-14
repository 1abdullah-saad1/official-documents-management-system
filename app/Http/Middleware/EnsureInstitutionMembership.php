<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EnsureInstitutionMembership
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        $institutionId = (int) $request->route('institution');

        // If no auth user, block (should already be behind auth middleware)
        if (! $user) {
            abort(401);
        }

        // Global superadmin bypass
        if ($user->hasRole('superadmin')) {
            return $next($request);
        }

        // Check if the user has ANY role on this institution (team)
        $hasRoleInInstitution = DB::table('model_has_roles')
            ->where('model_type', get_class($user))
            ->where('model_id', $user->getKey())
            ->where('team_id', $institutionId)
            ->exists();

        if (! $hasRoleInInstitution) {
            abort(403, 'No access to this institution.');
        }

        return $next($request);
    }
}
