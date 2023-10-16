<?php

namespace Database\Seeders;

use App\Models\Assistant;
use App\Models\Room;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AssistantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('assistants')->truncate();
        Schema::enableForeignKeyConstraints();
        // Database Assistant
        $assistant = [
            ['user_id' => User::where('email','c14210073@john.petra.ac.id')->first()->id, 'room_id' => Room::where('code','P.206')->first()->id,'description' => 'Merekap data mahasiswa'],
            ['user_id' => User::where('email','c14210025@john.petra.ac.id')->first()->id],
            ['user_id' => User::where('email','c14210017@john.petra.ac.id')->first()->id, 'room_id' => Room::where('code','P.201a')->first()->id,'description' => 'Merekap data mahasiswa'],
            ['user_id' => User::where('email','c14210206@john.petra.ac.id')->first()->id, 'room_id' => Room::where('code','P.207')->first()->id],
            ['user_id' => User::where('email','c14210099@john.petra.ac.id')->first()->id],
        ];
        foreach($assistant as $assistant){
            Assistant::create($assistant);
        }
    }
}
