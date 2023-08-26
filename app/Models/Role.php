<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model {
    use HasFactory, HasUuids;

    protected $fillable = [
        'name', 'slug',
    ];

    protected $hidden = [
        'pivot',
        'created_at',
        'updated_at',
    ];

    public function users() {
        return $this->belongsToMany(User::class, 'user_roles');
    }
    public function roleRoutes(){
        return $this->hasMany(RoleRoutes::class);
    }
}
