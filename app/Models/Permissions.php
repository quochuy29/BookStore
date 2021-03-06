<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permissions extends Model
{
    use HasFactory;

    public $table = "permissions";
    public $fillable = ['name'];

    public function hasRole()
    {
        return $this->hasMany(RoleHasPermissions::class,'permission_id');
    }
}
