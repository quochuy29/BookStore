<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    use HasFactory;

    public $table = "products";
    
    public $fillable = [
        'name',
        'cate_id',
        'price',
        'quantity',
        'status',
        'detail',
        'slug'
    ];

    public function categories()
    {
        return $this->belongsTo(Categories::class, 'cate_id');
    }

    public function galleries()
    {
        return $this->hasMany(Galleries::class,'product_id');
    }

    public function genresProducts(){
        return $this->hasMany(GenresProduct::class,'product_id');
    }

    public function authorProducts()
    {
        return $this->hasMany(AuthorProduct::class,'product_id');
    }
}
