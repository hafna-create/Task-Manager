<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Staff;
use Illuminate\Support\Facades\Auth;

class EnsureStaffIsActive
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $isApi = $request->is('api/*');
        $user = $isApi ? $request->user('sanctum') : Auth::guard('staff')->user();

        if ($user instanceof Staff && ! $user->isEligible()) {
            if ($isApi) {
                return response()->json(['message' => 'Your account is inactive or not verified.'], 403);
            }
            Auth::guard('staff')->logout();
            $request->session()->regenerateToken();
            return redirect()->route('staff.login')
                ->withErrors(['email' => 'Your account is inactive or not verified.']);
        }

        return $next($request);
    }
}
