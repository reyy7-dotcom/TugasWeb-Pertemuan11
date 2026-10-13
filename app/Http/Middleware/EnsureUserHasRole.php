<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            abort(Response::HTTP_UNAUTHORIZED, 'Silakan login terlebih dahulu untuk mengakses halaman ini.');
        }

        // Expand any comma-separated roles (e.g. "admin,editor")
        $allowedRoles = [];
        foreach ($roles as $role) {
            foreach (explode(',', $role) as $r) {
                $trimmed = trim($r);
                if ($trimmed !== '') {
                    $allowedRoles[] = $trimmed;
                }
            }
        }

        if (! in_array($user->role, $allowedRoles, true)) {
            abort(Response::HTTP_FORBIDDEN, "Akses ditolak. Peran Anda ({$user->role}) tidak memiliki izin untuk mengakses resource ini. Diperlukan peran: " . implode(', ', $allowedRoles) . '.');
        }

        return $next($request);
    }
}
