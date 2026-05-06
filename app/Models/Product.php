<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'price',
        'description',
        'creator_id',
    ];

    /**
     * Get the user that created the product.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'product_id');
    }
    public function photos()
    {
        return $this->hasMany(Photo::class, 'product_id');
    }
}
