<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserHasRoles extends Model
{
    use HasFactory;

    public $table = "userhasroles";
    public $fillable = ['role_id', 'user_id'];

    public function roles()
    {
        return $this->belongsToMany(Roles::class, 'userhasroles', 'id', 'role_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'userhasroles', 'id', 'user_id');
    }
}
