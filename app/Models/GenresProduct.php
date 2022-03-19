<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GenresProduct extends Model
{
    use HasFactory;

    public $table = "genres_product";

    public $fillable = ['genres_id','product_id'];
}
