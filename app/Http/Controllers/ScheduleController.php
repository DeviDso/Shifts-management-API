<?php

namespace App\Http\Controllers;
use App\Schedule;

use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function index(){
      $schedules =  Schedule::with('employee')->orderBy('name', 'asc')->get();

      return response()->json($schedules);
    }

    public function view($id){
      $schedule = Schedule::with('data')->find($id);

      return response()->json($schedule);
    }

    public function store(Request $r){
      $this->validate($r, [
        'name' => 'required',
        'max_hours' => 'integer',
        'max_minutes' => 'integer',
        'private' => 'boolean'
      ]);

      $schedule = Schedule::create($r->all());

      return response()->json($schedule, 200);
    }

    public function update($id, Request $r){
      $this->validate($r, [
        'name' => 'required',
        'max_hours' => 'integer',
        'max_minutes' => 'integer',
        'private' => 'boolean'
      ]);

      $schedule = Schedule::findOrFail($id);

      $schedule->update($r->all());

      return response()->json($schedule, 200);
    }

    public function delete($id){
      Schedule::find($id)->delete();

      return response("Tvarkaraštis ištrintas!", 200);
    }
}
