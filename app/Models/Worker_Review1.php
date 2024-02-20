<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Worker_Review1 extends Model
{
    use HasFactory;
    protected $fillable = ['worker_id', 'client_id', 'comment', 'rate'];
    protected $table = 'workers_reviews';
}
