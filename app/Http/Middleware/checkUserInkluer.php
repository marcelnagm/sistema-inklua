<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class checkUserInkluer
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        
//        dd('hpa');
        $user = Auth::user();
//        dd($user->toArray());
       if (!$user->isInklua() ) {
            return response()->json([
                        'error' => 'Usuario precisa ser inkluer',
                            ], 400);
        }
        return $next($request);
    }
}
