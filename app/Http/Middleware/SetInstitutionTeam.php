<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetInstitutionTeam
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $institutionId = (int) $request->route('institution');

        // Spatie teams: set current team id for permission checks
        setPermissionsTeamId($institutionId);

        // Avoid stale cached relations when team changes
        if ($request->user()) {
            $request->user()->unsetRelation('roles');
            $request->user()->unsetRelation('permissions');
        }

        return $next($request);
    }
}
