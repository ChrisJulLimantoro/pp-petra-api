<?php

namespace Database\Seeders;

use App\Models\AssistantPracticum;
use App\Models\Practicum;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AssistantPracticumSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('assistant_practicums')->truncate();
        Schema::enableForeignKeyConstraints();

        // Database assistant_practicums
        $assPrac = [
            ['assistant_id'=> User::where('email','c14210073@john.petra.ac.id')->first()->id, 'practicum_id'=>Practicum::where('name','Praktikum Struktur Data')->first()->id],
            ['assistant_id'=> User::where('email','c14210017@john.petra.ac.id')->first()->id, 'practicum_id'=>Practicum::where('name','Praktikum Struktur Data')->first()->id],
            ['assistant_id'=> User::where('email','c14210099@john.petra.ac.id')->first()->id, 'practicum_id'=>Practicum::where('name','Praktikum Struktur Data')->first()->id],
            ['assistant_id'=> User::where('email','c14210206@john.petra.ac.id')->first()->id, 'practicum_id'=>Practicum::where('name','Praktikum Basis Data')->first()->id],
            ['assistant_id'=> User::where('email','c14210073@john.petra.ac.id')->first()->id, 'practicum_id'=>Practicum::where('name','Praktikum Basis Data')->first()->id],
            ['assistant_id'=> User::where('email','c14210073@john.petra.ac.id')->first()->id, 'practicum_id'=>Practicum::where('name','Praktikum Basis Data Lanjutan')->first()->id],
        ];
        foreach($assPrac as $a){
            AssistantPracticum::create($a);
        }
    }
}
