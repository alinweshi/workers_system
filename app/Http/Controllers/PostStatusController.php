<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostStatusRequest;
use App\Models\Post;
use App\Notifications\AdminPost;
use Illuminate\Support\Facades\Notification;

class PostStatusController extends Controller
{
    public function showPending()
    {
        $pendingPost = Post::where('status', 'pending')->get();
        if (!$pendingPost) {
            return response()->json(['message' => 'No pending posts found'], 404);
        }
        return response()->json(['pendingPost' => $pendingPost], 404);
    }
    public function changeStatus(PostStatusRequest $request)
    {
        $post = Post::find($request->post_id);
        $post->update([
            'status' => $request->status,
            'reject_reason' => $request->reject_reason,
        ]);
        // dd($request->post_id, $request->status, $request->rejected_reason);
        if (!$post) {
            return response()->json(['message' => 'No  posts found'], 404);
        }
        Notification::send($post->worker, new AdminPost($post, $post->worker));

        return response()->json(['message' => ' posts status changed successfully'], 200);
    }
}
