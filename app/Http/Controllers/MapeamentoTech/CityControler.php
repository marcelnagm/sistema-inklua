<?php

namespace App\Http\Controllers\MapeamentoTech;

use App\Models\City;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller; 

class CityControler extends Controller {


   /**
    *   Retorna um Json com todos os registos
    * @return Json 
    */ 
    public function index() {
        return City::orderBy('id')->paginate(10);
    }
    
    public function uf(Request $request) {
        return City::where('uf',$request->input('id'))->orderBy('id')->get();
    }
    
    public function by_name(Request $request) {
        return City::where('name','like','%'.$request->input('id')."%")
                ->where('uf',$request->input('uf'))    
                ->orderBy('id')->get();
    }

  

}
