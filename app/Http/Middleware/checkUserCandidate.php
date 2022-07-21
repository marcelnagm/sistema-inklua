<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class checkUserCandidate
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
        $user = auth()->guard('api')->user();
   
       if ($user->candidatehunting() == null) {
            return response()->json([
                 'status' => false,
                            'error' => true,
                        'msg' => 'Usuario precisa se cadastrar como candidato para poder utilizar as funções',
                            ], 400);
        }
        return $next($request);
    }
}
