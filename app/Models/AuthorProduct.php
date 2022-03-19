<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuthorProduct extends Model
{
    use HasFactory;

    public $table = "author_product";

    public $fillable = ['product_id','author_id'];
}
