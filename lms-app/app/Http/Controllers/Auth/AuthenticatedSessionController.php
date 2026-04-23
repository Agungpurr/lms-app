<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended($this->getDashboardRoute());
    }

    /**
     * Get dashboard route based on user role using Spatie Permission
     */
    protected function getDashboardRoute(): string
    {
        $user = Auth::user();
        
        // Gunakan hasRole() dari Spatie Permission
        if ($user->hasRole('admin')) {
            return route('admin.dashboard', absolute: false);
        } elseif ($user->hasRole('instructor')) {
            return route('instructor.dashboard', absolute: false);
        } else {
            return route('student.dashboard', absolute: false);
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}