<?php

namespace Jamstackvietnam\Support\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureHasPermission
{
    public function handle(Request $request, Closure $next)
    {
        if ($this->hasPermission()) {
            return $next($request);
        }

        if (request()->wantsJson()) {
            return response('Unauthorized.', 401);
        }

        return abort('403');
    }

    private function hasPermission()
    {
        $routeName = explode('.', request()->route()->getName())[1];

        return !contains($routeName, 'sidebar') ?: auth()->guard('admin')->user()->can($routeName);
    }
}
