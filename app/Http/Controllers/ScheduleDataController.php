<?php

namespace App\Http\Controllers;
use App\Schedule;
use App\ScheduleData;

use Illuminate\Http\Request;

class ScheduleDataController extends Controller
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
      $data = ScheduleData::all();

      return response()->json($data, 200);
    }

    public function view($id){
      $schedule = Schedule::find($id);

      $data = $schedule->data()->get();

      return response()->json($data);
    }

    public function update(Request $r, $id){
      $schedule = Schedule::find($id);

      foreach($schedule->data()->get() as $item){
        $item->delete();
      }

      $data = $schedule->data()->createMany($r->all());

      return response()->json($data);
    }
}
