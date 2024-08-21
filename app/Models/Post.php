<?php

namespace App\Models;

use App\Models\Concerns\ConvertsMarkdownToHtml;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    use ConvertsMarkdownToHtml;
    
    protected $guarded = ['id'];

    public function user() 
    {
        return $this->belongsTo(User::class);
    }
 
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function showRoute(array $parameters = []) {
        return route('posts.show', [$this, Str::slug($this->title), ...$parameters]);
    }

    public function title(): Attribute {
        return Attribute::set(fn($value) => Str::title($value));
    }
}
