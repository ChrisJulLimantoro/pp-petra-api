<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class UsersSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        Schema::disableForeignKeyConstraints();
        DB::table('users')->truncate();
        Schema::enableForeignKeyConstraints();

        $data = [
            ['email' => 'admin@hydra.project','name' => 'Hydra Admin'],
            ['email' => 'c14210073@john.petra.ac.id','name' => 'ceje'],
            ['email' => 'c14210025@john.petra.ac.id','name' => 'Darrel'],
            ['email' => 'c14210017@john.petra.ac.id','name' => 'Nico'],
            ['email' => 'c14210206@john.petra.ac.id','name' => 'Leo'],
            ['email' => 'c14210099@john.petra.ac.id','name' => 'Nichgun']
        ];
        foreach ($data as $userData) {
            $user = User::create([
                'email' => $userData['email'],
                'name' => $userData['name'],
            ]);
            UserRole::create([
                'user_id' => $user->id,
                'role_id' => Role::where('slug', 'admin')->first()->id,
            ]);
        }

    }
}
