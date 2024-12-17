<?php
namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated 
{
    public function handle(Request $request, Closure $next, string ...$guards): Response 
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::guard($guard)->user();

                if ($user->role === 'admin') {
                    return redirect()->route('dashboard'); // Pastikan route 'dashboard' sudah didefinisikan
                } else {
                    // Untuk semua role selain admin, kembali ke halaman utama
                    return redirect(RouteServiceProvider::HOME);
                }
            }
        }

        return $next($request);
    }
}