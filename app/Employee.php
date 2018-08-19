<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Employee extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'surname', 'schedule_id', 'position_id'
    ];

    public function schedule(){
      return $this->belongsTo(Schedule::class)->with('data');
    }

    public function position(){
      return $this->belongsTo(Position::class);
    }
}
