<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('events')->truncate();
        Schema::enableForeignKeyConstraints();

        $events = [
            ['name' => 'Pendaftaran Praktikum 1','start_date'=>'2023-10-21','end_date'=>'2023-11-8'],
            ['name' => 'Pendaftaran Praktikum 2','start_date'=>'2023-12-3','end_date'=>'2023-12-28']
        ];
        foreach($events as $e){
            Event::create($e);
        }
    }
}
