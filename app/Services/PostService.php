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
            ->withReactionCounts()
            ->withMyReaction()
            ->latest()->paginate();
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
