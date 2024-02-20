<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'content', 'price', 'worker_id','status', 'reject_reason'];

    protected $table = 'posts';
    public function worker(){
        return $this->belongsTo(Worker::class); // 1 post belongs to 1 worker
    }
    public function Posts_Photo(){
        return $this->hasMany(Posts_Photo::class); // 1 post has many comments
}
}
