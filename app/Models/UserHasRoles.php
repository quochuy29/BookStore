<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserHasRoles extends Model
{
    use HasFactory;

    public $table = "userhasroles";

    public function roles()
    {
        return $this->belongsToMany(Roles::class,'userhasroles','id','role_id');
    }
}
