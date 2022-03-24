<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoleHasPermissions extends Model
{
    use HasFactory;

    public $table = "rolehaspermissions";

    public function permission()
    {
        return $this->belongsToMany(Permissions::class, 'rolehaspermissions','id','permission_id');
    }
}
