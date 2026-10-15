<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Admin;
use App\Models\Staff;


class EnsureUserType
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string $type): Response
    {
        $model = $type === 'admin' ? Admin::class : Staff::class;

        if (! $request->user() instanceof $model) {
            return response()->json(['message' => 'Forbidden for this user type.'], 403);
        }
        return $next($request);
    }
}
