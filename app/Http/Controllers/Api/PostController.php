<?php


namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Services\PostService;
use App\Http\Resources\PostResource;

class PostController extends Controller
{
    public function __construct(private PostService $postService) {}

    public function index()
    {
        $posts = $this->postService->getPosts();
        return PostResource::collection($posts);
    }
}
