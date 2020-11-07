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

        $data = [
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'description' => $request->description,
            'parent_id' => $request->parent_id,
            'user_id' => auth()->user()->id,
        ];

        $note = Note::create($data);

        return $this->showOne($note);
    }


    public function show($id) {
        $note = Note::findOrFail($id);

        return $this->showOne($note);
    }


    public function update(Request $request, $id) {
        $note = Note::findOrFail($id);
        if($request->filled('title')){
            $note->title = $request->title;
        }
        if($request->filled('subtitle')){
            $note->subtitle = $request->subtitle;
        }
        if($request->filled('description')){
            $note->description = $request->description;
        }
        if($request->filled('parent_id')){
            $note->parent_id = $request->parent_id;
        }

        if($note->isClean()){
            return $this->errorResponse('You need to specify any different value to update! 😐', 401);
        }

        $note->save();

        return $this->showOne($note);
    }


    public function destroy($id) {
        $note = Note::findOrFail($id);

        if($note->user_id == auth()->user()->id){
            $note->delete();
            return $this->successResponse('Note delete ! ❎');
        }
        
        return $this->errorResponse("You are not authorized to delete this note ! 😌 Nice try btw 🥱", 401);
    }
}
