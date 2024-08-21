<?php

use App\Models\Comment;
use App\Models\Post;

it('uses title case for titles', function(){
    $post = Post::factory()->create(['title' => 'Hello, how are you?']);

    expect($post->title)->toBe('Hello, How Are You?');
});

it('can generate a route to the show page', function() {
    $post = Post::factory()->create();

    expect($post->showRoute())->toBe(route('posts.show', [$post, Str::slug($post->title)]));
});

it('can generate additional query parameters on the show route', function() {
    $post = Post::factory()->create();

    expect($post->showRoute(['page' => 2]))->toBe(route('posts.show', [$post, Str::slug($post->title), 'page' => 2]));
});

it('generates the html', function () {
    $comment = Comment::factory()->make(['body' => '## Hello world']);

    $comment->save();

    expect($comment->html)->toEqual(str($comment->body)->markdown());
});