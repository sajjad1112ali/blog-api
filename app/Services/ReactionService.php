<?php
namespace App\Services;

use App\Models\Post;
use App\Models\Reaction;
use Illuminate\Support\Facades\Auth;

class ReactionService
{
    public function toggleReaction(Post $post, string $type): Reaction
    {
        $userId = Auth::id();

        $reaction = Reaction::where('post_id', $post->id)
            ->where('user_id', $userId)
            ->first();

        if ($reaction) {
            if ($reaction->type === $type) {
                $reaction->delete();
                return $reaction;
            }
            $reaction->update(['type' => $type]);
            return $reaction;
        }

        return Reaction::create([
            'post_id' => $post->id,
            'user_id' => $userId,
            'type'    => $type,
        ]);
    }
}
