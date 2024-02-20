<?php
namespace App\Http\Controllers;

use App\Http\Requests\StoringPostRequest;
use App\Services\PostService\StoringPostService;

class PostController extends Controller
{

    protected $StoringPostService;
    public function __construct(StoringPostService $StoringPostService)
    {
        $this->StoringPostService = $StoringPostService;
    }
    public function store(StoringPostRequest $request)
    {
        return $this->StoringPostService->store($request);
    }
    public function show()
    {
        return $this->StoringPostService->showAllPosts();
    }

    //creating post
    //     //sending notifications to admins
    //     Public function sendNotification($post){
    //         $admins=DB::table('users')->where('role','admin')->get();
    //         foreach($admins as $admin){
    //             $notification=new Notification();
    //             $notification->user_id=$admin->id;
    //             $notification->post_id=$post->id;
    //             $notification->save();
    //         }
    // }

}
