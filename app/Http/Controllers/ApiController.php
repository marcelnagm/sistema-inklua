<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Content;
use App\Models\Group;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Mail\Contact;



class ApiController extends Controller
{
    public function home(Request $request){
        $type = $request->input("tipo");
        $page = $request->input("page");

        $cacheName = "HOME_{$type}_{$page}";

        // if(Cache::has($cacheName)) {
        //     return response()->json(Cache::get($cacheName));
        // }

        $content = Content::getHomeContent();
        

        $cities = Content::getCities();        
        $cities = collect(['cidades' => $cities->all()]);
        $data = $cities->merge($content);

        $result = $data->all();
        $result = $this->clearData($result);

    
        // Cache::put($cacheName, $result, 600);

        return response()->json($result);
    }

   public function search(Request $request){
        
        $search = $request->input("q");
        $city = $request->input("city");
        $state = $request->input("state");
        $contract = $request->input("contract_type");
        $page = $request->input("page");

        $cacheName = "SEARCH_{$search}_{$city}_{$state}_{$contract}_{$page}";
        // if (Cache::has($cacheName)) {
        //     return response()->json(Cache::get($cacheName));
        // }

        $counters = Content::getCounters($search, $state, $city, $contract);
        $content = Content::searchPositions($search, $state, $city, $contract);

        $data = $counters->merge($content);
        
        $result = $data->all();

            $result = $this->clearData($result);
//        $result = $this->clearData($result);
        //dd($result);
        // Cache::put($cacheName, $result, 600);

        return response()->json($result);
    }
    
    public function position(Request $request, $id){
        $user = auth()->guard('api')->user();

        $content = Content::selectRaw("id, type, image, title, date, description,city as 'cidade', state as 'estado', url, source,user_id")
                            ->where("type", 1)
                            ->where("id", $id)
                            ->when($user && $user->wallet, function ($query) use ($user) {
                                return $query->with(['actions' => function ($query) use ($user) {
                                    $query->where('wallet_id', $user->wallet->id);
                                }]);
                            })
                            ->first();

        $result = $content->toArray();
          
        $result["company"] = Content::companyName($content['user_id']);
        $result["shares"] = (object) [
            "whatsapp" => false,
            "facebook" => false,
            "linkedin" => false,
            "twitter"   => false
        ];

        if(isset($result["actions"])){
            foreach($result["actions"] as $action){
                if(isset($action["media"]) && isset($result["shares"]->{$action["media"]})){
                    $result["shares"]->{$action["media"]} = true;
                }
            }
        }
        unset($result["actions"]);

        
      
        $subcontent["type"] = "position";
        $result["location"] = $result["cidade"].' - '.$result["estado"];
        if($result["image"] == ''){
            $result["image"] = Content::getRandomImage();
        }
        unset($result["cidade"]);
        unset($result["estado"]);

        return response()->json($result);
    }

    public function contact(Request $request){

        $data = request()->only(
            'nome',
            'email',
            'tipo',
            'empresa',
            'mensagem'
        );

        $validator = Validator::make($data, [
            'nome' => 'required',
            'email' => 'required|email',
            'tipo' => 'required',
            'empresa' => 'required_if:type,empresa',
            'mensagem' => 'required',
        ]);

        if( $validator->fails() ){
            return response()->json(
                [
                    "code" => "MESSAGE_NOT_SENT",
                    "errors" => $validator->messages()
                ], 400
            );
        }

        $mailTo = ($data['tipo'] == 'Candidato') ? 'comunicacao@inklua.com.br' : 'contato@inklua.com.br';
        
        Mail::to($mailTo)
                ->send(new Contact($data));

        return response()->json(
            [
                "code" => "MESSAGE_SENT"
            ], 200
        );
    }

    private function clearData($data, $key = "data"){
        
        foreach($data[$key] as &$content){

            unset($content["unique_group"]);

            if(!$content["group_id"]){

                if($content["type"] == 2){
                    $content["clicked"] = false;

                    if(isset($content["actions"])){
                        foreach($content["actions"] as $action){
                            if(isset($action["action"]) && $action["action"] == 'click'){
                                $content["clicked"] = true;
                            }
                        }
                    }
                    
                }else{
                    $content["shares"] = (object) [
                        "whatsapp" => false,
                        "facebook" => false,
                        "linkedin" => false,
                        "twitter"   => false
                    ];

                    if(isset($content["actions"])){
                        foreach($content["actions"] as $action){
                            if(isset($action["media"]) && isset($content["shares"]->{$action["media"]})){
                                $content["shares"]->{$action["media"]} = true;
                            }
                        }
                    }
                }

            }



            unset($content["actions"]);

            // Positions
            if($content["type"] == 1 ){
//                dd( url()->current());
                if($content["group_id"] && strpos(url()->current(),'busca') === false){
                    $group = Group::where("id", $content["group_id"])->first();

                    $content["id"] = $content["group_id"];
                    $content["type"] = "group";
                    $content["title"] = $group != null ? $group->title : '-';

                    unset($content["group_id"]);
                    unset($content["date"]);
                    unset($content["description"]);
                    unset($content["url"]);
                    unset($content["source"]);
                    unset($content["image"]);
                    unset($content["company"]);
                    

                    $groupContents = Content::selectRaw("id, type, image, title, date, description,city as 'cidade', state as 'estado', url")
                                            ->when($group, function($query,$group){
                                                return $query->where("group_id", $group->id);
                                            })                                            
                                            ->get();

                    $content["positions"] = Content::hideFields($groupContents)->all();

                    foreach($content["positions"] as &$subcontent){
                         $content["company"] = Content::companyName($content['user_id']);
                        $subcontent["type"] = "position";
                        $subcontent["location"] = $subcontent["cidade"].' - '.$subcontent["estado"];
                        $subcontent["description"] = $this->clearHtml($subcontent["description"], 300);
                        if($subcontent["image"] == ''){
                            $subcontent["image"] = Content::getRandomImage();
                        }else{
                            $subcontent["image"] = url("storage/positions/{$subcontent["image"]}");
                        }
                        unset($subcontent["cidade"]);
                        unset($subcontent["estado"]);
                    }

                }else{
                     $content["company"] = Content::companyName($content['user_id']);
                    $content["type"] = "position";
                    $content["location"] = $content["cidade"].' - '.$content["estado"];
                    $content["description"] = $this->clearHtml($content["description"], 300);
                    if($content["image"] == ''){
                        $content["image"] = Content::getRandomImage();
                    }else{
                        $content["image"] = url("storage/positions/{$content["image"]}");
                    }
                    unset($content["group_id"]);
                    unset($content["source"]);
                }
                
                unset($content["cidade"]);
                unset($content["estado"]);
                unset($content["category"]);
            }else{
                unset($content["cidade"]);
                unset($content["estado"]);
                unset($content["group_id"]);

                // Ads
                if($content["type"] == 2){
                    if($content["category"] == "banner"){
                        $content["type"] = "image";
                        unset($content["title"]);
                    }else{
                        $content["type"] = "advertising";
                    }
                    unset($content["source"]);
                    unset($content["category"]);

                    $content["description"] = $this->clearHtml($content["description"]);
                    $content["description"] = $this->clearHtml($content["description"]);
                    
                    if($content["image"] != ''){
                        $content["image"] = url("storage/ads/{$content["image"]}");
                    }
                
                // Articles
                }else if($content["type"] == 3){
                    $content["type"] = "article";
                    $content["date"] = substr($content["created_at"], 0, 10);
                    $content["description"] = $this->clearHtml($content["description"],1000);

                    if($content["image"] != ''){
                        $content["image"] = url("storage/articles/{$content["image"]}");
                    }
                }    
            }

            unset($content["created_at"]);
        }

        return $data;
    }

    private function clearHtml($string, $limit = FALSE){
        $string = html_entity_decode(strip_tags($string));

        if($limit)
            $string = substr($string, 0, $limit);

        return mb_convert_encoding($string, 'UTF-8', 'UTF-8');
    }
}

