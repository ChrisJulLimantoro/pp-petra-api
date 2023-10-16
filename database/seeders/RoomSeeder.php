<?php

namespace Database\Seeders;

use App\Models\Room;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('rooms')->truncate();
        Schema::enableForeignKeyConstraints();
        // Database Room
        $rooms = [
            ['name' => 'Lab SI','code' => 'P.206','capacity'=>60],
            ['name' => 'Lab PG','code' => 'P.207','capacity'=>50],
            ['name' => 'Lab Studio','code' => 'P.201a','capacity'=>35],
            ['name' => 'Lab Mobdev','code' => 'P.201','capacity'=>40],
            ['name' => 'Lab JK','code' => 'P.208','capacity'=>20],
            ['name' => 'Lab MM','code' => 'P.209','capacity'=>20],
            ['name' => 'Lab SC','code' => 'P.210','capacity'=>20],
        ];
        foreach($rooms as $room){
            Room::create($room);
        }
    }
}
