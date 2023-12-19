<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\User;
use App\Models\Validate;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ValidateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('validates')->truncate();
        Schema::enableForeignKeyConstraints();
        // Database Practicum
        $validates = [
            ['student_id' => User::where('email','c14210206@john.petra.ac.id')->first()->id, 'event_id' => Event::where('name','Pendaftaran Praktikum 1')->first()->id, 'validate' => '1'],
            ['student_id' => User::where('email','c14210017@john.petra.ac.id')->first()->id, 'event_id' => Event::where('name','Pendaftaran Praktikum 1')->first()->id, 'validate' => '1'],
            ['student_id' => User::where('email','c14210025@john.petra.ac.id')->first()->id, 'event_id' => Event::where('name','Pendaftaran Praktikum 1')->first()->id, 'validate' => '1'],
            ['student_id' => User::where('email','c14210073@john.petra.ac.id')->first()->id, 'event_id' => Event::where('name','Pendaftaran Praktikum 1')->first()->id, 'validate' => '1'],
            ['student_id' => User::where('email','c14210099@john.petra.ac.id')->first()->id, 'event_id' => Event::where('name','Pendaftaran Praktikum 1')->first()->id, 'validate' => '1']
        ];
        foreach($validates as $validate){
            Validate::create($validate);
        }
    }
}
