<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Schedule extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'max_hours', 'max_minutes', 'private', 'working_minutes', 'night_minutes', 'holiday_minutes'
    ];

    public function employee(){
      return $this->hasMany(Employee::class);
    }

    public function data(){
      return $this->hasMany(ScheduleData::class)->orderBy('start', 'asc');
    }
}
