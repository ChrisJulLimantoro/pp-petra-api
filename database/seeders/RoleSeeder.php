<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RoleSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        Schema::disableForeignKeyConstraints();
        DB::table('roles')->truncate();
        Schema::enableForeignKeyConstraints();

        $roles = [
            ['name' => 'Administrator', 'slug' => 'admin'],
            ['name' => 'Student', 'slug' => 'student'],
            ['name' => 'Asisten Dosen', 'slug' => 'asdos'],
            ['name' => 'Asisten Tetap', 'slug' => 'astap'],
            ['name' => 'Super Admin', 'slug' => 'super-admin'],
        ];

        collect($roles)->each(function ($role) {
            Role::create($role);
        });
    }
}
