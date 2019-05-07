<?php

namespace Thinkstudeo\Guardian\Middleware;

use Illuminate\Http\Response;

class CheckRole
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return mixed
     */
    public function handle($request, \Closure $next, $role)
    {
        if (!$request->user()) {
            return redirect(route('login'));
        }

        $roles = is_string($role) ? explode('|', $role) : $role;

        if (!$request->user()->hasAnyRole($roles)) {
            abort(Response::HTTP_FORBIDDEN, 'You do not have the permission.');
        }

        return $next($request);
    }
}
