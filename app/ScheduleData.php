<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class ScheduleData extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'start', 'end', 'color', 'type', 'overNight'
    ];

    public function schedule(){
      return $this->belongsTo(Schedule::class);
    }
}
