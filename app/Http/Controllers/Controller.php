<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Carbon;

class Controller extends BaseController {

    use AuthorizesRequests,
        DispatchesJobs,
        ValidatesRequests;

    public static function displayQuery($query) {
        dd(self::getEloquentSqlWithBindings($query));
    }
    
    public static function getEloquentSqlWithBindings($query) {
        return vsprintf(str_replace('?', '%s', str_replace('%', '%%', $query->toSql())), collect($query->getBindings())->map(function ($binding) {
                    return is_numeric($binding) ? $binding : "'{$binding}'";
                })->toArray());
    }

    public function validate_request($data, $rules) {
        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            return response()->json(
                            [
                                "errors" => $validator->messages()
                            ], 400, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
                            JSON_UNESCAPED_UNICODE
            );
        }
        $response = Validator::make($data, $rules);

        return $response;
    }

    public function filter_date(Request $request, $query) {
        $date_start = Carbon\Carbon::createFromFormat('d/m/Y', $request->input('date_start'))->format('Y/m/d');
        $date_end = Carbon\Carbon::createFromFormat('d/m/Y', $request->input('date_end'))->format('Y/m/d');

        $query = $query->whereRaw('(created_at between "' . $date_start
                . '" and '
                . '"' . $date_end . '")');
        if ($request->exists('debug')) {
            dd(Controller::getEloquentSqlWithBindings($query));
        }
        return $query;
    }

}
