<?php
namespace App\Services\PostService;

use App\Models\Admin;
use App\Models\Post;
use App\Models\Posts_Photo;
use App\Notifications\AdminPost;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class StoringPostService
{

    public function storePost($data)
    {
        $worker = Auth::guard('worker')->user();
        $data['worker_id'] = $worker->id;

        $post = Post::create([
            'title' => $data['title'],
            'content' => $data['content'],
            'price' => $data['price'],
            'worker_id' => $data['worker_id'],
        ]);
        $post->price = $post->price - $post->price * .05;
        $post->save();

        // Remove the extra square brackets
        // if (!$post) {
        //     return response()->json(['message' => 'post not created'], 500);
        // }
        return $post;

    }
    // public function discount(data) {

    // }
    public function storePostPhotos($request, $post)
    {
        // dd($request->file('photo')->store('post_photos'));
        $photo = $request->file('photo');
        $post_photo = Posts_Photo::create([
            'post_id' => $post->id,
            'photo_path' => $request->file('photo')->store('post_photos')]);
        return response()->json(['message' => 'File uploaded successfully', 'post_photo' => $post_photo], 200);
    }
    public function sendAdminNotifications($post)
    {
        $admins = Admin::get();
        Notification::send($admins, new AdminPost($post, Auth::guard('worker')->user()));
    }

    public function store($request)
    {
        try {
            DB::beginTransaction();
            $post = $this->storePost($request);
            if ($request->hasFile('photo')) {
                $postPhotos = $this->storePostPhotos($request, $post);
                $this->sendAdminNotifications($post);
                DB::commit();
                return response()->json(['message' => 'post created successfully and your price after discount is' . $post->price, 'post_photos' => $postPhotos], 201);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
    public function showAllPosts()
    {
        $posts = Post::whereStatus('approved')->get();
        return response()->json(['posts' => $posts], 200);
    }
}
