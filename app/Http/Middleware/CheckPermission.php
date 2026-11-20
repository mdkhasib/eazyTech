<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    public function handle(Request $request, Closure $next, string $module): Response
    {
        $user = $request->user();

        if (! $user || ! $user->is_active) {
            auth()->logout();

            return redirect()->route('admin.login')->withErrors(['email' => 'Your account is disabled.']);
        }

        abort_unless($user->hasPermission($module), 403, 'Your role does not have access to this section.');

        return $next($request);
    }
}
