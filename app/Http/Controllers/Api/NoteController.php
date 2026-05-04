<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Note; 
use Illuminate\Http\Request;

class NoteController extends Controller
{
    // GET /api/notes 
    public function index()
    {
        return response()->json(Note::all(), 200);
    }

    // POST /api/notes 
    public function store(Request $request)
    {
        //validacion
        $validated = $request->validate([
            'title' => 'required|string',
            'author' => 'required|string',
            'body' => 'required|string',
            'classification' => 'required|string',
        ]);

        // Crear la nota en la base de datos
        $note = Note::create($validated);

        return response()->json($note, 201);
        //return Note::create($validated);
    }

    // GET /api/notes/{id} Consulta una nota 
    public function show($id)
    {
        $note = Note::find($id);
        if (!$note) {
            return response()->json(['error' => 'Nota no encontrada'], 404);
        }
        return response()->json($note, 200);
    }

    // PUT /api/notes/{id} Para editar notas
    public function update(Request $request, $id)
    {
        $note = Note::find($id);
        if (!$note) return response()->json(['error' => 'No existe'], 404);

        $note->update($request->all());
        return response()->json($note, 200);
    }

    //para eliminar
    public function destroy($id)
    {
        $note = Note::find($id);
        if (!$note) return response()->json(['error' => 'No encontrado'], 404);
        
        $note->delete();
        return response()->json(['message' => 'Nota eliminada'], 200);
    }
}