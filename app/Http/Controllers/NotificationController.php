<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;


class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index() {
        $user = Auth::user();

        $notifications = Notification::where('user_id', $user->id)->where('active', 1)->get();

        if(!$notifications){
            return response()->json([
                'error' => 'Não há notificações ativas',
            ], 400);
        }
        
        return response()->json($notifications);
    }

    public function update(Request $request, $id) {
        $user = Auth::user();

        $notification = Notification::where('id', $id)->first();

        if(!$notification || $notification->user_id != $user->id){
            return response()->json([
                'error' => 'Usuário não tem acesso a essa notificação',
            ], 400);
        }

        $notification->active = 0;

        $notification->update();

        return response()->json($notification);
    }
}
