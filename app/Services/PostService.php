<?php

namespace App\Services;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class PostService
{
    public function getPosts()
    {
        $posts = Post::with(['user', 'category', 'genres'])
            ->withCount([
                'reactions as likes_count' => fn($q) => $q->where('type', 'like'),
                'reactions as dislikes_count' => fn($q) => $q->where('type', 'dislike'),
                'reactions as loves_count' => fn($q) => $q->where('type', 'love'),
                'reactions as hahas_count' => fn($q) => $q->where('type', 'haha'),
                'reactions as wows_count' => fn($q) => $q->where('type', 'wow'),
                'reactions as sads_count' => fn($q) => $q->where('type', 'sad'),
                'reactions as angrys_count' => fn($q) => $q->where('type', 'angry'),
            ])
            ->when($user = Auth::user(), function ($query) use ($user) {
                $query->with(['reactions' => fn($q) => $q->where('user_id', $user->id)]);
            })
            ->latest()->get();
        return $posts;
    }

    public function myPosts(Request $request)
    {
        $posts = $request->user()
            ->posts()
            ->with(['category', 'genres'])
            ->latest()
            ->get();
        return $posts;
    }

    public function createPost($data)
    {
        $slug = Str::slug($data['title']);
        $data['slug'] = $slug;
        $post = Post::create($data);
        $post->genres()->sync($data['genres']);
        return $post;
    }

    public function updatePost(Post $post, array $data): Post
    {
        if ($post->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $post->update($data);

        if (isset($data['genres'])) {
            $post->genres()->sync($data['genres']);
        }

        return $post->load(['user', 'category', 'genres']);
    }


    public function deletePost(Post $post): void
    {
        if ($post->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }
        $post->genres()->detach();
        $post->delete();
    }
}
