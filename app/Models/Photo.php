<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    protected $fillable = [
        'url',
        'product_id',
    ];

    /**
     * Get the product associated with the photo.
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
