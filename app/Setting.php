<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Setting extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'night_starts', 'night_ends', 'night_multiplier', 'holiday_multiplier'
    ];
}
