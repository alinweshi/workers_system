<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkerReview extends Model
{
    use HasFactory;
    protected $fillable = ['post_id', 'client_id', 'rate', 'comment'];
    protected $guarded = [];
    protected function post()
    {
        return $this->belongsTo(Worker::class);
    }
    protected function client()
    {
        return $this->belongsTo(Client::class);
    }
}
