<?php

namespace App\Http\Controllers;
use App\Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    public function index(){
      $settings = Setting::first();

      return response()->json($settings, 200);
    }

    public function update(Request $r){
      // $this->validate($r,[
      //   'night_starts' => 'required',
      //   'night_ends' => 'required',
      //   'night_multiplier' => 'required',
      //   'holiday_multiplier' => 'required'
      // ]);

      Setting::truncate();

      $settings = Setting::create($r->all());

      return response()->json($settings, 200);
    }
}
