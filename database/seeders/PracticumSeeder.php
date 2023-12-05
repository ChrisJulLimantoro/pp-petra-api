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
            ['name' => 'Praktikum Struktur Data','code'=>'A','quota'=>30,'subject_id'=>Subject::where('code','TF4219')->first()->id,'room_id'=>Room::where('code','P.201a')->first()->id,'day' => 1,'time' => '730'],
            ['name' => 'Praktikum Struktur Data','code'=>'B','quota'=>30,'subject_id'=>Subject::where('code','TF4219')->first()->id,'room_id'=>Room::where('code','P.206')->first()->id,'day' => 1,'time' => '1630'],
            ['name' => 'Praktikum Struktur Data','code'=>'C','quota'=>30,'subject_id'=>Subject::where('code','TF4219')->first()->id,'room_id'=>Room::where('code','P.208')->first()->id,'day' => 3,'time' => '1330'],
            ['name' => 'Praktikum Struktur Data','code'=>'D','quota'=>30,'subject_id'=>Subject::where('code','TF4219')->first()->id,'room_id'=>Room::where('code','P.208')->first()->id,'day' => 3,'time' => '1630'],
            ['name' => 'Praktikum Struktur Data','code'=>'E','quota'=>30,'subject_id'=>Subject::where('code','TF4219')->first()->id,'room_id'=>Room::where('code','P.206')->first()->id,'day' => 4,'time' => '1630'],
            ['name' => 'Praktikum Struktur Data','code'=>'F','quota'=>30,'subject_id'=>Subject::where('code','TF4219')->first()->id,'room_id'=>Room::where('code','P.210')->first()->id,'day' => 4,'time' => '1630'],
            ['name' => 'Praktikum Struktur Data','code'=>'G','quota'=>30,'subject_id'=>Subject::where('code','TF4219')->first()->id,'room_id'=>Room::where('code','P.208')->first()->id,'day' => 5,'time' => '730'],
            ['name' => 'Praktikum Struktur Data','code'=>'H','quota'=>30,'subject_id'=>Subject::where('code','TF4219')->first()->id,'room_id'=>Room::where('code','P.207')->first()->id,'day' => 5,'time' => '1430'],
            ['name' => 'Praktikum Basis Data','code'=>'A','quota'=>18,'subject_id'=>Subject::where('code','TF4229')->first()->id,'room_id'=>Room::where('code','P.208')->first()->id,'day' => 5,'time' => '1430'],
            ['name' => 'Praktikum Basis Data','code'=>'B','quota'=>20,'subject_id'=>Subject::where('code','TF4229')->first()->id,'room_id'=>Room::where('code','P.208')->first()->id,'day' => 6,'time' => '730'],
            ['name' => 'Praktikum Basis Data Lanjutan','code'=>'A','quota'=>30,'subject_id'=>Subject::where('code','TF4273')->first()->id,'room_id'=>Room::where('code','P.201')->first()->id,'day' => 1,'time' => '1530'],
            ['name' => 'Praktikum Basis Data Lanjutan','code'=>'B','quota'=>25,'subject_id'=>Subject::where('code','TF4273')->first()->id,'room_id'=>Room::where('code','P.208')->first()->id,'day' => 2,'time' => '1330'],
            ['name' => 'Praktikum Basis Data Lanjutan','code'=>'C','quota'=>30,'subject_id'=>Subject::where('code','TF4273')->first()->id,'room_id'=>Room::where('code','P.207')->first()->id,'day' => 2,'time' => '1530'],
            ['name' => 'Praktikum Basis Data Lanjutan','code'=>'D','quota'=>20,'subject_id'=>Subject::where('code','TF4273')->first()->id,'room_id'=>Room::where('code','P.201a')->first()->id,'day' => 5,'time' => '730'],
            ['name' => 'Praktikum Sistem Operasi','code'=>'A','quota'=>25,'subject_id'=>Subject::where('code','TF4243')->first()->id,'room_id'=>Room::where('code','P.201')->first()->id,'day' => 5,'time' => '1430'],
            ['name' => 'Praktikum Sistem Operasi','code'=>'B','quota'=>25,'subject_id'=>Subject::where('code','TF4243')->first()->id,'room_id'=>Room::where('code','P.201')->first()->id,'day' => 6,'time' => '730'],
            ['name' => 'Praktikum DAA','code'=>'A','quota'=>25,'subject_id'=>Subject::where('code','TF4270')->first()->id,'room_id'=>Room::where('code','P.208')->first()->id,'day' => 1,'time' => '1530'],
            ['name' => 'Praktikum DAA','code'=>'B','quota'=>25,'subject_id'=>Subject::where('code','TF4270')->first()->id,'room_id'=>Room::where('code','P.201a')->first()->id,'day' => 1,'time' => '1530'],
            ['name' => 'Praktikum DAA','code'=>'C','quota'=>25,'subject_id'=>Subject::where('code','TF4270')->first()->id,'room_id'=>Room::where('code','P.201a')->first()->id,'day' => 2,'time' => '730'],
            ['name' => 'Praktikum DAA','code'=>'D','quota'=>25,'subject_id'=>Subject::where('code','TF4270')->first()->id,'room_id'=>Room::where('code','P.201')->first()->id,'day' => 2,'time' => '830'],
            ['name' => 'Praktikum DAA','code'=>'E','quota'=>25,'subject_id'=>Subject::where('code','TF4270')->first()->id,'room_id'=>Room::where('code','P.207')->first()->id,'day' => 2,'time' => '1330'],
            ['name' => 'Praktikum DAA','code'=>'F','quota'=>25,'subject_id'=>Subject::where('code','TF4270')->first()->id,'room_id'=>Room::where('code','P.201a')->first()->id,'day' => 3,'time' => '1330'],
            ['name' => 'Praktikum DAA','code'=>'G','quota'=>25,'subject_id'=>Subject::where('code','TF4270')->first()->id,'room_id'=>Room::where('code','P.209')->first()->id,'day' => 5,'time' => '1430'],
        ];
        foreach($practicums as $practicum){
            Practicum::create($practicum);
        }
    }
}
