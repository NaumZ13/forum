<?php

namespace App\Providers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /** 
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        JsonResource::withoutWrapping(); // remove the data wrapper for the json data => { id: 1}

        Model::preventLazyLoading(); // throw exeption

        Relation::enforceMorphMap([  // custom value for our polymorphic models
            'post' => Post::class,
            'comment' => Comment::class,
        ]);
    }
}
