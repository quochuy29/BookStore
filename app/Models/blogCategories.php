<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class blogCategories extends Model
{
    use HasFactory;

    public $table ="blog_categories";
    public $fillable = ['name'];
}
