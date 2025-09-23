<?php

namespace App\Services;

use App\Models\Post;
use Illuminate\Http\Request;

class PostService
{
    public function getPosts()
    {
        $posts = Post::with(['user', 'category', 'genres'])->latest()->get();
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
}
