<?php


namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\Post;
use App\Services\PostService;
use App\Services\ReactionService;
use App\Http\Resources\PostResource;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function __construct(private PostService $postService, private ReactionService $reactionService) {}

    public function index()
    {
        $posts = $this->postService->getPosts();
        return PostResource::collection($posts);
    }

    public function createPost(StorePostRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = $request->user()->id;
        $posts = $this->postService->createPost($data);
        return $posts;
    }

    public function updatePost(UpdatePostRequest $request, Post $post)
    {
        $data = $request->validated();
        $post = $this->postService->updatePost($post, $data);
        return $post;
    }

    public function deletePost(Post $post)
    {
        $this->postService->deletePost($post);
        return [
            'success'  => true,
            'message' => 'Post deleted successfully',
        ];
    }

    public function react(Request $request, Post $post)
    {
        $request->validate([
            'type' => 'required|string|in:like,dislike,love,haha,wow,sad,angry'
        ]);

        $reaction = $this->reactionService->toggleReaction($post, $request->type);

        return $reaction;
    }
}
