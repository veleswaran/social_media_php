<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Post extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'content', 'file'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function shares()
    {
        return $this->hasMany(Share::class);
    }

    /**
     * Delete file when post is deleted.
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($post) {
            if ($post->file) {
                Storage::delete($post->file);
            }
        });
    }
}
