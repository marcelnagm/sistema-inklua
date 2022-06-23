<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class checkPjTypeUser
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
        $user = Auth::user();
        if (!$user || $user->type != 'PJ'  ){
            return response()->json([
                'error' => 'Usuario precisa ter CNPJ',
            ], 400);
        }
        return $next($request);
    }
}
