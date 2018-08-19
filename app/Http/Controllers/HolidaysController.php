<?php

namespace App\Http\Controllers;
use App\Holiday;
use Illuminate\Http\Request;

class HolidaysController extends Controller
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
      $holidays = Holiday::all();

      return response()->json($holidays, 200);
    }

    public function update(Request $r){
      Holiday::truncate();

      $holidays = Holiday::insert($r->all());

      return response()->json($holidays, 200);
    }
}
