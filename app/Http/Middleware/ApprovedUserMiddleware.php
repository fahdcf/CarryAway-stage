<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ApprovedUserMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        if (!$user->isApproved()) {
            if ($user->isPending()) {
                return redirect()->route('home')->with('warning', 'Votre compte est en attente d\'approbation. Veuillez patienter.');
            } elseif ($user->isRejected()) {
                return redirect()->route('home')->with('error', 'Votre compte a été rejeté. Veuillez contacter l\'administrateur.');
            } elseif ($user->isSuspended()) {
                return redirect()->route('home')->with('error', 'Votre compte a été suspendu. Veuillez contacter l\'administrateur.');
            }
        }

        return $next($request);
    }
}
