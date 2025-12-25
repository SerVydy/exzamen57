<?php

namespace App\Http\Controllers;

use App\Http\Resources\NoteCollection;
use App\Http\Resources\NoteResource;
use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class NoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!Auth::user())
        {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        return NoteResource::collection(Note::query()->where('user_id', Auth::user()->id)->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!Auth::user())
        {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        $validate = Validator::make($request->all(), [
            'title' => 'required',
            'content' => 'required'
        ]);

        if($validate->fails()) {
            return response()->json([
                'success' => 'false',
                'message' => $validate->errors()
            ]);
        };

        $note = Note::create([
            'user_id' => Auth::user()->id,
        ] + $validate->validate());
        return response()->json([
            'note' => new NoteResource($note),
        ],201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Note $note)
    {
        if (!Auth::user())
        {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        return response()->json([
            'note' => new NoteResource($note),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Note $note)
    {
        if (!Auth::user())
        {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        $validate = Validator::make($request->all(), [
            'title' => 'required',
            'content' => 'required'
        ]);

        if($validate->fails()) {
            return response()->json([
                'success' => 'false',
                'message' => $validate->errors()
            ]);
        };

        $note->update($validate->validate());

        return response()->json([
            'note' => new NoteResource($note),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Note $note)
    {
        $note->delete();
        return response()->json([], 204);
    }
}
