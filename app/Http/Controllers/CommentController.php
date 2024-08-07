<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Auth\Access\Gate;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate as FacadesGate;
use Illuminate\Routing\Controller;

class CommentController extends Controller
{
    use AuthorizesRequests;

    public function __construct()
    {
        $this->authorizeResource(Comment::class);
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Post $post)
    {
       $data = $request->validate([
            'body' =>['required', 'string', 'max:2500']
        ]);

    //    Comment::make($data)
    //         ->user()->associate($request->user())
    //         ->post()->associate($post)
    //         ->save();

        Comment::create([
            'user_id' => $request->user()->id,
            'post_id' => $post->id,
            'body' => $data['body']
        ]);

        return to_route('posts.show', $post);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Comment $comment)
    {
        $data = $request->validate(['body' => ['required', 'string', 'max:2500']]);

        $comment->update($data);

        return to_route('posts.show', ['post' => $comment->post_id, 'page' => $request->query('page')]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Comment $comment)
    {
        $comment->delete();

        return to_route('posts.show', ['post' => $comment->post_id, 'page' => $request->query('page')]);
    }
}
