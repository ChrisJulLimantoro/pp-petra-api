<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoleRoutes extends Model
{
    use HasFactory,HasUuids;
    protected $fillable = [
        'role_id', 'route', 'method', 'name'
    ];

    public function role(){
        return $this->belongsTo(Role::class);
    }
}
