<?php

namespace Database\Seeders;

use App\Models\Practicum;
use App\Models\StudentPracticum;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class StudentPracticumSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('student_practicums')->truncate();
        Schema::enableForeignKeyConstraints();

        // Database Student Practicum
        $studPrac = [
            ['student_id' => User::where('email','c14210025@john.petra.ac.id')->first()->id, 'practicum_id' => Practicum::where('name','Praktikum Struktur Data')->where('code','B')->first()->id, 'attempt' => 1, 'choice' => 1],
            ['student_id' => User::where('email','c14210025@john.petra.ac.id')->first()->id, 'practicum_id' => Practicum::where('name','Praktikum Struktur Data')->where('code','D')->first()->id, 'attempt' => 1, 'choice' => 2],
            ['student_id' => User::where('email','c14210025@john.petra.ac.id')->first()->id, 'practicum_id' => Practicum::where('name','Praktikum Basis Data Lanjutan')->where('code','A')->first()->id, 'attempt' => 1, 'choice' => 1],
            ['student_id' => User::where('email','c14210025@john.petra.ac.id')->first()->id, 'practicum_id' => Practicum::where('name','Praktikum Basis Data')->where('code','A')->first()->id, 'attempt' => 1, 'choice' => 1],
            ['student_id' => User::where('email','c14210025@john.petra.ac.id')->first()->id, 'practicum_id' => Practicum::where('name','Praktikum Statistika Dasar')->where('code','A')->first()->id, 'attempt' => 1, 'choice' => 1],
    ];
        foreach($studPrac as $s){
            StudentPracticum::create($s);
        }
    }
}
