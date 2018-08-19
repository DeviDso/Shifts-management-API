<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Report;

class ReportsController extends Controller
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

    public function store(Request $r){
      $this->validate($r,[
        'category' => 'required',
        'description' => 'required'
      ]);

      $report = Report::create($r->all());

      return response()->json($report, 200);
    }
}
