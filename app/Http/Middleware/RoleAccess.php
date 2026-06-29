<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Jika role adalah 'user', batasi akses hanya ke dashboard dan surat-masuk
        if ($user->role === 'user') {
            $routeName = $request->route()->getName();

            // Routes yang diizinkan untuk user
            $allowedRoutes = [
                'dashboard',
                'surat-masuk',
                'surat-masuk.edit',
                'surat-masuk.create',
                'surat-masuk-data',
                'surat-masuk-post',
                'surat-masuk-notulen',
                'surat-masuk-notulen-update',
                'surat-masuk-notulen-data',
                'surat-masuk-notulen-file-delete',
                'surat-masuk.update',
                'surat-masuk-delete',
                'dispo-masuk-delete',
                'delete-file',
                'upload-file',
                'surat-masuk-files',
                'profile',
                'profile-update',
                'logout',
                'check-url'
            ];

            if (!in_array($routeName, $allowedRoutes)) {
                return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
            }
        }

        return $next($request);
    }
}
