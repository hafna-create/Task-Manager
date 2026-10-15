<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Staff;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    private function guard(Request $r): string
    {
        return $r->segment(1) === 'admin' ? 'admin' : 'staff';
    }

    public function show(Request $r)
    {
        return view('auth.login', ['guard' => $this->guard($r)]);
    }

    public function login(Request $r)
    {
        $guard = $this->guard($r);
        $credentials = $r->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (! Auth::guard($guard)->attempt($credentials)) {
            return back()->withErrors(['email' => 'Invalid credentials.'])->onlyInput('email');
        }

        $user = Auth::guard($guard)->user();
        if ($user instanceof Staff && ! $user->isEligible()) {
            Auth::guard('staff')->logout();
            return back()->withErrors(['email' => 'Your account is inactive or not verified.'])->onlyInput('email');
        }

        $r->session()->regenerate();
        return redirect()->intended(route($guard . '.dashboard'));
    }

    public function logout(Request $r)
    {
        $guard = $this->guard($r);
        Auth::guard($guard)->logout();
        $r->session()->regenerateToken();
        return redirect()->route($guard . '.login');
    }
}
