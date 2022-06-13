<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Comment extends Model
{
    use HasFactory;

    protected $primaryKey = 'comment_id';

    public function user() {
        return $this->belongsTo(User::class, 'userId');
    }

    public function movie() {
        return $this->belongsTo(Movie::class, 'movieId');
    }
}
