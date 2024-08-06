<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user' => $this->whenLoaded('user', fn () => UserResource::make($this->user)), // if user rel on this post isnt loaded, it doesn't exist, It wont load in the user model.
            'title' => $this->title,
            'body' => $this->body,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at
        ];
    }
}
