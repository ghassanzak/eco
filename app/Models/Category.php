<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Nicolaslopezj\Searchable\SearchableTrait;

class Category extends Model
{
    use HasFactory;
    use SearchableTrait;

    protected $table = 'categories';
    protected $fillable = [
        'name',
        'image',
        'note',
        'status',
        'is_popular',
    ];
    protected $searchable = [
        'columns' => [
            'categories.name' => 10,
            'categories.note' => 10,
        ],
    ];


    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function status()
    {
        return $this->status == 1 ? true : false;
    }
    public function is_popular()
    {
        return $this->is_popular == 1 ? true : false;
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}
