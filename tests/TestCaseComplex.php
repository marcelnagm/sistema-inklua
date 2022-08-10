<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

class TestCaseComplex extends BaseTestCase
{
    use CreatesApplication;
    
     public function random_date($format = 'BR', $separator = '/') {
         if($format != 'BR') return random_int(1999, 2022) . $separator. random_int(01, 12) . $separator . random_int(01, 28);
        else  return  random_int(01, 28).$separator.random_int(01, 12) .  $separator . random_int(1999, 2022);
    }

    public function random_date_hour() {
        return random_int(1999, 2022) . '-' . random_int(01, 12) . '-' . random_int(01, 28) . ' ' . random_int(1, 12) . ':' . random_int(1, 59) . ':' . random_int(1, 59);
    }
    
     function display($myvar) {

        fwrite(STDERR, print_r($myvar));
        fwrite(STDERR, print_r("\n"));
    }
    
   public function getEloquentSqlWithBindings($query) {
        return vsprintf(str_replace('?', '%s', str_replace('%', '%%', $query->toSql())), collect($query->getBindings())->map(function ($binding) {
                    return is_numeric($binding) ? $binding : "'{$binding}'";
                })->toArray());
    }
    
}
