<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Product;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{

    use SoftDeletes;
    protected $fillable = [
        'text_comment',
        'user_id',
        'product_id',
    ];

    /**
     * Get the user that made the comment.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the product associated with the comment.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
