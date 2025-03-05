<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Share extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id', 'post_id', 'shared_with'
    ];

    // Relationship with User (who shared the post)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship with Post
    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    // Relationship with User (who received the shared post)
    public function sharedWithUser()
    {
        return $this->belongsTo(User::class, 'shared_with');
    }
}
