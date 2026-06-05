<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        if ($user->is_banned) {
            Auth::logout();
            return redirect()->route('login')
                ->withErrors(['email' => 'Akun kamu telah dinonaktifkan. Hubungi admin.']);
        }

        if (!in_array($user->role, $roles)) {
            // Redirect ke dashboard sesuai role
            return redirect()->route($user->dashboardRoute())
                ->with('error', 'Kamu tidak memiliki akses ke halaman tersebut.');
        }

        return $next($request);
    }
}
