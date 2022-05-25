<?php

namespace App\Http\Controllers\cms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Content;
use App\Models\Group;
use App\Models\User;
use Carbon\Carbon;

class UserPositionController extends Controller
{
    public function index(Request $request)
    {
        $logged = Auth::user();
            
        $search_position = $request->input("search_position");
        $status = $request->input('status') ? $request->input('status') : null;
        
        $positions = Content::where('type', 1)
                                ->whereNotNull('user_id')
                                ->when($search_position, function ($query, $search_position) {
                                    
                                    $query->where(function($query) use($search_position) {
                                        
                                        return $query->where("title", "like", "%{$search_position}%")
                                                        ->orWhere("description", "like", "%{$search_position}%");
                                    });
                                })
                                ->when(($status), function ($query) use ($status) {
                                    $query->where('status', $status);
                                })
                                ->orderBy('ordenation', 'desc')
                                ->orderBy('id', 'desc')
                                ->paginate(10);

        $data = [
            'positions' => $positions,
            'search_position' => $search_position,
            'status' => $status
        ];

        return view('cms.position.user_position_list', $data);
    }

    public function edit($id)
    {
        $logged = Auth::user();
        
        $position = Content::where('id', $id)->first();
        $groups = Group::get();
        

        if(!$position || $position->type != 1){
            return redirect()->back()->with("error", "Vaga não encontrada.");
        }

        $data = [
            'position'    => $position,
            'groups' => $groups,
        ];

        return view('cms.position.user_position_form', $data);
    }

    public function update(Request $request, $id) 
    {
        $position = Content::where('id', $id)->with('user')->first();

        if(!$position || $position->type != 1){
            return redirect()->back()->with("error", "vaga não encontrada.");
        }

        $data = $request->except([
            'status',
            'image'
        ]);

        if($position->status == 'aguardando_aprovacao') {
            if($request->input('status') == 'publicada') {
                $positionOwner = $position->user;
                if($position->checkExistenceOfPositionByCnpj($positionOwner->cnpj)) {
                    $data['status'] = 'aguardando_pagamento';
                    $position->update( $data );
                    $position->notifyPositionApproved();
                }else {
                    $data['status'] = 'publicada';
                    $data['published_at'] = Carbon::now()->format('Y-m-d');
                    $position->update( $data );
                    $position->notifyPositionPublished();
                }
            }elseif($request->input('status') == 'reprovada') {
                $data['status'] = 'reprovada';
                $position->update( $data );
            }
        }else {
            $position->update( $data );
        }


        // Atualiza o ordenation em todos os positions do mesmo grupo
        if($position->group_id){
            if($data['ordenation'] != ''){
                Content::where('group_id', $position->group_id)->update(['ordenation' => $data['ordenation'] ]);
            }
        }

        return redirect("admin/usuarios/vagas/$position->id/edit")->with("success", "Vaga atualizada com sucesso.");
    }
}
