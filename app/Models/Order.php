<?php

namespace App\Models;

use App\Models\Client;
use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = ['post_id', 'post_title', 'client_id', 'worker_id', 'status', 'phone', 'location', 'is_completed', 'is_paid', 'is_cancelled', 'cancellation_reason'];
    public function post()
    {
        return $this->belongsTo(Post::class);
    }
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
