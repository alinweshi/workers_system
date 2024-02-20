<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Posts_Photo extends Model
{
    use HasFactory;
    protected $fillable = ['post_id', 'photo_path'];
    protected $table = 'posts_photos';
    public function post()
    {
        return $this->belongsTo(Post::class);
    }

}
