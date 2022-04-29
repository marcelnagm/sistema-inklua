<?php

namespace App\Http\Controllers\cms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\Action;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('checkAdminPermission');
    }

    public function searchReport()
    {
        $data = [
            'date_start' => '',
            'date_end' => '',
        ];

        return view('cms.report.report', $data);
    }

    public function report(Request $request)
    {
        $data = $request->all();

        $this->validator($data);

        $date_start = Carbon::createFromFormat('d/m/Y', $request->input('date_start'));
        $date_end = Carbon::createFromFormat('d/m/Y', $request->input('date_end'));

        $actions = Action::where('coins', '>', 0)
                                ->whereBetween('created_at', [$date_start, $date_end])
                                ->get();

        if($request->input('type') == 'csv'){
            $headers = [
                "Content-type"        => "text/csv",
                "Content-Disposition" => "attachment; filename=relatorio_inkoins_geradas.csv",
                "Pragma"              => "no-cache",
                "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                "Expires"             => "0",
            ];
            $columns  = ['DATA', 'TÍTULO', 'AÇÃO', 'MÍDIA', 'INKOINS'];
            $callback = function () use ($actions, $columns) {
                $file = fopen('php://output', 'w'); //<-here. name of file is written in headers
                fputcsv($file, $columns);
                foreach ($actions as $action) {
                    fputcsv($file, [$action->created_at->format('d/m/Y'), $action->content ? $action->content->title : 'Vaga removida', $action->action, $action->media, $action->coins]);
                }
                fclose($file);
            };

            return response()->stream($callback, 200, $headers); 
        }

        $data = [
            'actions' => $actions,
            'date_start' => $date_start,
            'date_end' => $date_end
        ];

        return view('cms.report.report', $data);
    }

    public function searchDonateReport()
    {
        $data = [
            'date_start' => '',
            'date_end' => '',
            'donate' => 'donate'
        ];

        return view('cms.report.report', $data);
    }

    public function donateReport(Request $request)
    {
        $data = $request->all();

        $this->validator($data);

        $date_start = Carbon::createFromFormat('d/m/Y', $request->input('date_start'));
        $date_end = Carbon::createFromFormat('d/m/Y', $request->input('date_end'));

        $actions = Action::where('coins', '<', 0)
                                ->whereBetween('created_at', [$date_start, $date_end])
                                ->get();

        if($request->input('type') == 'csv'){
            $headers = [
                "Content-type"        => "text/csv",
                "Content-Disposition" => "attachment; filename=relatorio_inkoins_doadas.csv",
                "Pragma"              => "no-cache",
                "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                "Expires"             => "0",
            ];
            $columns  = ['DATA', 'NOME', 'INKOINS'];
            $callback = function () use ($actions, $columns) {
                $file = fopen('php://output', 'w'); //<-here. name of file is written in headers
                fputcsv($file, $columns);
                foreach ($actions->all() as $action) {
                    fputcsv($file, [$action->created_at->format('d/m/Y'), $action->wallet->user->name, $action->coins]);
                }
                fclose($file);
            };
            return response()->stream($callback, 200, $headers); 
        }
       
        $data = [
            'actions' => $actions,
            'date_start' => $date_start,
            'date_end' => $date_end,
            'donate' => 'donate'
        ];

        return view('cms.report.report', $data);
    }

    public function validator($data)
    {
        Validator::make($data, [
            'date_start'    => ['required', 'date_format:"d/m/Y"'],
            'date_end'    => ['required', 'date_format:"d/m/Y"', 'after_or_equal:date_start'],
        ])->validate();
    }
    
}
