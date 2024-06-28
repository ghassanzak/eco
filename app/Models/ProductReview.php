<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Nicolaslopezj\Searchable\SearchableTrait;

class ProductReview extends Model
{
    use HasFactory;
    use SearchableTrait;

    public $table = 'product_reviews';

    protected $guarded = [];

    protected $searchable = [
        'columns' => [
            'product_reviews.product_review_details' => 10,
        ],
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function status()
    {
        return $this->status == 1 ? 'Active' : 'Inactive';
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}
