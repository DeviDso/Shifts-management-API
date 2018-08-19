<?php

namespace App\Http\Controllers;
use App\Position;
use App\Schedule;
use App\Employee;

use Illuminate\Http\Request;

class PdfController extends Controller
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
      $data['schedule'] = Schedule::with('data')->find(3);

      return view('pdf', $data);
    }

    public function month(Request $r){
      $data['employees'] = Employee::with('schedule')->findMany($r->input('employees'));
      $data['selectedMonth'] = $r->input('selectedMonth');
      $data['settingNightStarts'] = $r->input('night_starts');
      $data['settingNightEnds'] = $r->input('night_ends');

      $pdf = \PDF::loadView('pdf', $data);
      $pdf->setPaper('A4', 'landscape');

      return $pdf->stream('invoice.pdf');
    }

    public function monthView(Request $r){
      $data['employees'] = Employee::with(['schedule', 'schedule.data'])->findMany([1,2]);
      $data['selectedMonth'] = '2018-08-01';

      return view('pdf', $data);
    }
}
