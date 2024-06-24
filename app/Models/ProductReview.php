<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductReview extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function product()
    {
        return $this->belongsTo(Product::class);
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
