<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Roles extends Model
{
    use HasFactory;

    public $table = "roles";
    public $fillable = ['name'];

    public function hasPermission()
    {
        return $this->hasMany(RoleHasPermissions::class,'role_id');
    }

    public function hasUser()
    {
        return $this->hasMany(UserHasRoles::class,'role_id');
    }
}
