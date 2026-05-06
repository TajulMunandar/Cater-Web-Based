<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        $user = Auth::user();
        
        // Convert role names to level values: admin=0, petugas=1
        $levelMap = [
            'admin' => 0,
            'petugas' => 1,
        ];

        foreach ($roles as $role) {
            if (isset($levelMap[$role]) && $user->level == $levelMap[$role]) {
                return $next($request);
            }
        }

        // If no role matches, show 403
        abort(403, 'Anda tidak memiliki akses ke halaman ini.');
    }
}