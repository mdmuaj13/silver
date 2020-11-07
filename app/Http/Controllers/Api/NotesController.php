<?php

namespace App\Http\Controllers\Api;

use App\Models\Note;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotesController extends Controller
{
    
    
    public function index() {
        $notes = Note::all();
        
        return $this->showAll_($notes);
    }


    public function store(Request $request){
        $rules = [];

        $note = Note::create($request->all());

        return $this->showOne($note);
    }


    public function show($id)
    {
        //
    }


    public function update(Request $request, $id)
    {
        //
    }


    public function destroy($id)
    {
        //
    }
}
