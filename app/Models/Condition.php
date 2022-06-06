<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Condition
 *
 * @property $id
 * @property $name
 * @property $intervals
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Condition extends Model {

    static $rules = [
        'name' => 'required',
        'intervals' => 'required',
        'money' => 'required',
    ];
    protected $dates = [
    ];
    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'intervals', 'money'];

    public function __toString() {
        if ($this->intervals)
            return $this->name . ' - [inicial, final]';
        else
            return $this->name;
    }

}
