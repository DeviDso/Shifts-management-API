<?php

namespace App\Http\Controllers;
use App\Position;

use Illuminate\Http\Request;

class PositionController extends Controller
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
      $positions = Position::with('employee')->get();

      return response()->json($positions);
    }

    public function store(Request $r){
      $this->validate($r,[
        'name' => 'required',
        'wage' => 'required',
      ]);

      $position = Position::create($r->all());

      return response()->json($position, 200);
    }

    public function view($id){
      $position = Position::findOrFail($id);

      return response()->json($position, 200);
    }

    public function update($id, Request $r){
      $this->validate($r,[
        'name' => 'required',
        'wage' => 'required',
      ]);

      $position = Position::find($id);
      $position->update($r->all());

      return response()->json($position, 200);
    }

    public function delete($id){
      Position::findOrFail($id)->delete();

      return response()->json('Pareigos ištrintos');
    }
}
