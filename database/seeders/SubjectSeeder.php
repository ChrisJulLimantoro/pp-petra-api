<?php

namespace Database\Seeders;

use App\Models\Subject;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('subjects')->truncate();
        Schema::enableForeignKeyConstraints();
        // Database Subject
        $subjects = [
            ['name' => 'Struktur Data','code'=>'TF4501','sks'=>3,'semester'=>3,'duration'=>3,'program'=>'isd'],
            ['name' => 'Statistika Dasar','code'=>'TF4502','sks'=>3,'semester'=>2,'duration'=>3,'program'=>'isd'],
            ['name' => 'Basis Data','code'=>'TF4503','sks'=>3,'semester'=>2,'duration'=>3,'program'=>'isd'],
            ['name' => 'Basis Data Lanjutan','code'=>'TF4504','sks'=>3,'semester'=>3,'duration'=>3,'program'=>'s']
        ];
        foreach($subjects as $subject){
            Subject::create($subject);
        }
    }
}
