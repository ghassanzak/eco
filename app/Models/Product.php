<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Nicolaslopezj\Searchable\SearchableTrait;

class Product extends Model
{
    use HasFactory, Sluggable;
    use SearchableTrait;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'category_id',
        'user_id',
        'image_path',
        'code',
        'brand',
        'current_purchase_cost',
        'current_sale_price',
        'available_quantity',
        'description',
        'is_popular',
        'is_trending',
        'status',

    ];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }
    protected $searchable = [
        'columns' => [
            'products.name' => 10,
            'products.description' => 10,
            
        ],
        // 'joins' => [
        //     'products' => ['products.id','products.user_id'],
        // ],
    ];


    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function images_product()
    {
        return $this->hasMany(ProductImage::class);
    }
    public function reviews()
    {
        return $this->hasMany(ProductReview::class);
    }

    public function status()
    {
        return $this->status == 1 ? 'Active' : 'Inactive';
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'products_tags','product_id','tag_id');
    }

}
