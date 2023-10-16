<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('students')->truncate();
        Schema::enableForeignKeyConstraints();
        $prs = [
            ['code'=>'TF4501','day'=>rand(1,6),'time'=>1030,'duration'=>3],
            ['code'=>'TF4502','day'=>rand(1,6),'time'=>730,'duration'=>3],
            ['code'=>'TF4503','day'=>rand(1,6),'time'=>1430,'duration'=>3],
            ['code'=>'TF4504','day'=>rand(1,6),'time'=>1030,'duration'=>3],
        ];
        
        $students = [
            ['user_id' => User::where('email','c14210073@john.petra.ac.id')->first()->id,'ipk'=>3.88,'ips'=>'3.78','program'=>'i','semester'=>5,'prs'=>json_encode(array_slice($prs,rand(0,2)))],
            ['user_id' => User::where('email','c14210025@john.petra.ac.id')->first()->id,'ipk'=>3.88,'ips'=>'3.78','program'=>'s','semester'=>5,'prs'=>json_encode(array_slice($prs,rand(0,2)))],
            ['user_id' => User::where('email','c14210017@john.petra.ac.id')->first()->id,'ipk'=>3.88,'ips'=>'3.78','program'=>'i','semester'=>5,'prs'=>json_encode(array_slice($prs,rand(0,2)))],
            ['user_id' => User::where('email','c14210206@john.petra.ac.id')->first()->id,'ipk'=>3.88,'ips'=>'3.78','program'=>'d','semester'=>5,'prs'=>json_encode(array_slice($prs,rand(0,2)))],
            ['user_id' => User::where('email','c14210099@john.petra.ac.id')->first()->id,'ipk'=>3.88,'ips'=>'3.78','program'=>'d','semester'=>5,'prs'=>json_encode(array_slice($prs,rand(0,2)))],
        ];
        foreach($students as $student){
            Student::create($student);
        }
        
    }
}
