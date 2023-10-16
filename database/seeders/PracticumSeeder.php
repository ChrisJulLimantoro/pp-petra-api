<?php

namespace Database\Seeders;

use App\Models\Practicum;
use App\Models\Room;
use App\Models\Subject;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class PracticumSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('practicums')->truncate();
        Schema::enableForeignKeyConstraints();
        // Database Practicum
        $practicums = [
            ['name' => 'Praktikum Struktur Data','code'=>'A','quota'=>30,'subject_id'=>Subject::where('code','TF4501')->first()->id,'room_id'=>Room::where('code','P.201')->first()->id,'day' => 1,'time' => '730'],
            ['name' => 'Praktikum Struktur Data','code'=>'B','quota'=>30,'subject_id'=>Subject::where('code','TF4501')->first()->id,'room_id'=>Room::where('code','P.201')->first()->id,'day' => 1,'time' => '1630'],
            ['name' => 'Praktikum Struktur Data','code'=>'C','quota'=>30,'subject_id'=>Subject::where('code','TF4501')->first()->id,'room_id'=>Room::where('code','P.207')->first()->id,'day' => 1,'time' => '730'],
            ['name' => 'Praktikum Struktur Data','code'=>'D','quota'=>30,'subject_id'=>Subject::where('code','TF4501')->first()->id,'room_id'=>Room::where('code','P.201a')->first()->id,'day' => 5,'time' => '730'],
            ['name' => 'Praktikum Struktur Data','code'=>'E','quota'=>30,'subject_id'=>Subject::where('code','TF4501')->first()->id,'room_id'=>Room::where('code','P.206')->first()->id,'day' => 4,'time' => '1630'],
            ['name' => 'Praktikum Statistika Dasar','code'=>'A','quota'=>20,'subject_id'=>Subject::where('code','TF4502')->first()->id,'room_id'=>Room::where('code','P.208')->first()->id,'day' => 1,'time' => '730'],
            ['name' => 'Praktikum Basis Data','code'=>'A','quota'=>18,'subject_id'=>Subject::where('code','TF4503')->first()->id,'room_id'=>Room::where('code','P.209')->first()->id,'day' => 1,'time' => '730'],
            ['name' => 'Praktikum Basis Data','code'=>'B','quota'=>20,'subject_id'=>Subject::where('code','TF4503')->first()->id,'room_id'=>Room::where('code','P.207')->first()->id,'day' => 5,'time' => '730'],
            ['name' => 'Praktikum Basis Data Lanjutan','code'=>'A','quota'=>22,'subject_id'=>Subject::where('code','TF4504')->first()->id,'room_id'=>Room::where('code','P.206')->first()->id,'day' => 3,'time' => '1030']
        ];
        foreach($practicums as $practicum){
            Practicum::create($practicum);
        }
    }
}
