<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = [
        'title',
        'content',
        'author_id',
        'category',
        'image',
        'status',
        'views',
        'likes',
    ];

    public function author()
    {
        return $this->belongsTo(User::class);
    }

    
}
