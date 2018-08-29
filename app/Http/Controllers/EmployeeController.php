<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Employee;
use App\Schedule;

class EmployeeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    public function index(Request $r){
        $users = Employee::with(['schedule', 'position'])->get();

        return response()->json($users);
    }

    public function store(Request $r){
        $this->validate($r, [
          'name' => 'required',
          'position_id' => 'required',
          'schedule_id' => 'required',
        ]);

        if($r->input('schedule_id') == 'private'){
          if(strlen($r->input('private_schedule_name')) > 0){
            $ps = ' (' . $r->input('private_schedule_name') . ')';
          } else {
            $ps = '';
          }
          $schedule = Schedule::create([
            'name' => $r->input('name') . ' ' . $r->input('surname') . $ps,
            'max_hours' => 0,
            'max_minutes' => 0,
            'private' => true
          ]);

          $employee = $schedule->employee()->create($r->all());
        } else {
          $employee = Employee::create($r->all());
        }

        return response()->json($employee, 200);
    }

    public function view($id){
      $employee = Employee::findOrFail($id);

      return response()->json($employee, 200);
    }

    public function update($id, Request $r){
      $this->validate($r, [
        'name' => 'required',
        'position_id' => 'required',
        'schedule_id' => 'required',
      ]);

      $employee = Employee::findOrFail($id);

      if($r->input('schedule_id') == 'private'){
        if(strlen($r->input('private_schedule_name')) > 0){
          $ps = ' (' . $r->input('private_schedule_name') . ')';
        } else {
          $ps = '';
        }
        $schedule = Schedule::create([
          'name' => $r->input('name') . ' ' . $r->input('surname') . $ps,
          'max_hours' => 0,
          'max_minutes' => 0,
          'private' => true
        ]);

        $data['schedule_id'] = $schedule->id;
        $r->merge($data);
      }

      $employee->update($r->all());

      return response()->json($employee, 200);
    }

    public function delete($id){
      Employee::find($id)->delete();

      return response()->json('Darbuotojas ištrintas', 200);
    }
}
