<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

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
            'title' => $this->title,
            'slug' => $this->slug,
            'content' => $this->content,
            'slug' => $this->slug,
            'image' => $this->getFirstMediaUrl('poster'),          // original
            'thumbnail'  => $this->getFirstMediaUrl('poster', 'thumb'),
            'published_at' => $this->published_at_formated,
            'user'     => new UserResource($this->whenLoaded('user')),
            'category' => new CategoryResource($this->whenLoaded('category')),
            'genres'   => GenreResource::collection($this->whenLoaded('genres')),
            'reactions' => [
                'like'    => $this->likes_count,
                'dislike' => $this->dislikes_count,
                'love'    => $this->loves_count,
                'haha'    => $this->hahas_count,
                'wow'     => $this->wows_count,
                'sad'     => $this->sads_count,
                'angry'   => $this->angrys_count,
            ],
            'my_reaction' => $this->when(
                Auth::check(),
                fn() => $this->reactions->first()?->type
            ),
        ];
    }
}
