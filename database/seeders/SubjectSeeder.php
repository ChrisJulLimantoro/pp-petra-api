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
            ['name' => 'Struktur Data','code'=>'TF4219','sks'=>3,'semester'=>3,'duration'=>3,'program'=>'isd'],
            ['name' => 'Design dan Analisis Algoritma','code'=>'TF4270','sks'=>3,'semester'=>3,'duration'=>2,'program'=>'id'],
            ['name' => 'Basis Data','code'=>'TF4229','sks'=>3,'semester'=>2,'duration'=>3,'program'=>'isd'],
            ['name' => 'Basis Data Lanjutan','code'=>'TF4273','sks'=>3,'semester'=>3,'duration'=>3,'program'=>'s'],
            ['name' => 'Sistem Operasi','code'=>'TF4243','sks'=>3,'semester'=>4,'duration'=>3,'program'=>'isd']
        ];
        foreach($subjects as $subject){
            Subject::create($subject);
        }
    }
}
