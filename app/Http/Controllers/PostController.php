<?php

namespace App\Http\Controllers;

use App\Http\Resources\CommentResource;
use App\Http\Resources\PostResource;
use App\Http\Resources\TopicResource;
use App\Models\Post;
use App\Models\Topic;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate as FacadesGate;
use Illuminate\Support\Str;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Topic $topic = null)
    {
        if($request->query('query')){
            $posts = Post::search($request->query('query'))
            ->query(fn (Builder $query) => $query->with(['user', 'topic']))
            ->when($topic, fn (\Laravel\Scout\Builder $query) => $query->where('topic_id', $topic->id));
        } else {
            $posts = Post::with(['user','topic'])
            ->when($topic, fn(Builder $query) => $query->WhereBelongsTo($topic))
            // ->when(
            //     $request->query('query'),
            //     fn(Builder $query) => $query->whereAny(['title', 'body'], 'like', '%' . $request->query('query') . '%')  // whereAny is like orWhere
            //     // ->orWhere('body', 'like', '%' . $request->query('query') . '%')
            //     )
            ->latest()
            ->latest('id');
            // ->paginate()
            // ->withQueryString(); // the paginate links that are created we will have the query string included
        }

        return Inertia('Posts/Index', [
            'posts' => PostResource::collection($posts->paginate()->withQueryString()),
            'topics' => fn () => TopicResource::collection(Topic::all()),
            'selectedTopic' => fn () => $topic ? TopicResource::make($topic) : null,
            'query' => $request->query('query')
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        FacadesGate::authorize('create', Post::class);

        return inertia('Posts/Create', [
            'topics' => fn () => TopicResource::collection(Topic::all()),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $postData = $request->validate([
            'title' => ['required', 'string', 'min:10', 'max:120'],
            'topic_id' => ['required', 'exists:topics,id'],
            'body' => ['required', 'string', 'min:100', 'max:10000'],
        ]);

        $post = Post::create([
            ...$postData,
            'user_id' => $request->user()->id
        ]);

        return redirect($post->showRoute());
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Post $post)
    {
        if (! Str::endsWith($post->showRoute(), $request->path())) {
            return redirect($post->showRoute($request->query()), 301);
        }

        $post->load('user', 'topic');

        return inertia('Posts/Show', [
            'post' => fn () => PostResource::make($post)->withLikePermission(),
            'comments' => function () use ($post) {
                $commentResource = CommentResource::collection($post->comments()->with('user')->latest()->latest('id')->paginate(10));
                $commentResource->collection->transform(fn ($resource) => $resource->withLikePermission());

                return $commentResource;
            }
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        //
    }
}
