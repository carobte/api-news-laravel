<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Request;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with('user')->get();

        return response()->json($posts);
    }


    public function store(Request $request)
    {
        // Validación de los datos de entrada
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        // Crear el nuevo post asociado al usuario autenticado o uno ya existente
        $post = Post::create([
            'title' => $validatedData['title'],
            'content' => $validatedData['content'],
            'user_id' => User::inRandomOrder()->first()->id, // Asocia con un usuario existente aleatorio
        ]);

        // Devolver el post recién creado
        return response()->json($post, 201);
    }

    public function show($id)
    {

        $post = Post::with('user')->findOrFail($id);

        return response()->json($post);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Validación de los datos de entrada
        $validatedData = $request->validate([
            'title' => 'sometimes|string|max:255',
            'content' => 'sometimes|string',
        ]);

        // Buscar el post por ID
        $post = Post::findOrFail($id);

        // Actualizar el post
        $post->update([
            'title' => $validatedData['title'] ?? $post->title,
            'content' => $validatedData['content'] ?? $post->content,
        ]);

        // Devolver el post actualizado
        return response()->json($post);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Buscar el post por ID
        $post = Post::findOrFail($id);

        // Eliminar el post
        $post->delete();

        // Devolver una respuesta de éxito
        return response()->json(null, 204);
    }
}
