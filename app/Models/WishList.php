<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WishList extends Model
{
    use HasFactory;

    public $table = "wishlists";
    public $fillable = ['user_id','product_id','cate_id'];

    public function products()
    {
        return $this->belongsTo(Products::class,'product_id');
    }

    public function category()
    {
        return $this->belongsTo(Categories::class,'cate_id');
    }
}
