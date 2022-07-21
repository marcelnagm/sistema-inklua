<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class checkAdminPermission {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next) {
        $user = Auth::user();
        if ($user == null)
            $user = auth()->guard('api')->user();
        if ($request->is('api/*')) {
            if (!$user || $user->admin != 1) {
                return response()->json([
                            'status' => false,
                            'error' => true,
                            'msg' => 'Usuario precisa ser administrador',
                                ], 400);
            }
        } else
        if (!$user || $user->admin != 1) {
            return redirect('/admin/login');
        }

        return $next($request);
    }

}
