<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Galleries extends Model
{
    use HasFactory;

    public $table = 'galleries';
    public $fillable = ['url_image','product_id'];

    public function products(){
        return $this->belongsTo(Products::class,'product_id');
    }
}
