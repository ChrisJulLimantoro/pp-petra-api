<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\RoleRoutes;
use App\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RoutesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('role_routes')->truncate();
        Schema::enableForeignKeyConstraints();

        $json = <<<JSON
        [{"route":"login","method":"GET","akses":"astap,asdos,admin,student","routes":"\/"},{"route":"asisten.dashboard","method":"GET","akses":"astap, admin","routes":"\/asisten"},{"route":"mahasiswa.addPracticum","method":"POST","akses":"astap, asdos, student","routes":"\/mahasiswa\/addStudentPracticum"},{"route":"Praktikum.Daftar Praktikum","method":"GET","akses":"astap, asdos, student","routes":"\/praktikum\/daftarPraktikum"},{"route":"mahasiswa.getClass","method":"GET","akses":"astap, asdos, student","routes":"\/mahasiswa\/getClass\/{id}"},{"route":"mahasiswa.deletePracticum","method":"DELETE","akses":"astap, asdos, student","routes":"\/mahasiswa\/deletePracticum\/{id}"},{"route":"mahasiswa.validate","method":"POST","akses":"astap, asdos, student","routes":"\/mahasiswa\/validate"},{"route":"Praktikum.View Kelas Praktikum","method":"GET","akses":"astap, asdos, admin","routes":"\/praktikum\/viewKelas"},{"route":"Mahasiswa.Manage Mahasiswa","method":"GET","akses":"admin, superadmin","routes":"\/mahasiswa\/manage-mahasiswa"},{"route":"deleteMahasiswa","method":"DELETE","akses":"admin, superadmin","routes":"\/deleteMahasiswa\/{id}"},{"route":"Mahasiswa.View Jadwal","method":"GET","akses":"admin, superadmin","routes":"\/mahasiswa\/viewJadwal"},{"route":"addMasterJadwal","method":"POST","akses":"admin, superadmin","routes":"\/addMasterJadwal"},{"route":"deleteMasterJadwal","method":"DELETE","akses":"admin, superadmin","routes":"\/deleteMasterJadwal\/{id}"},{"route":"updateMasterJadwal","method":"PATCH","akses":"admin, superadmin","routes":"\/update\/{id}"},{"route":"Dashboard","method":"GET","akses":"mahasiswa, asdos, astap","routes":"\/mahasiswa"},{"route":"Ruangan","method":"GET","akses":"astap, admin, superadmin","routes":"\/room"},{"route":"room.add","method":"POST","akses":"astap, admin","routes":"\/room"},{"route":"room.edit","method":"POST","akses":"astap, admin","routes":"\/room\/{id}"},{"route":"room.delete","method":"DELETE","akses":"astap, admin","routes":"\/room\/{id}"},{"route":"practicum.detail","method":"GET","akses":"astap,asdos,admin,student","routes":"\/praktikum\/{id}"},{"route":"studentAssistantPracticum.delete","method":"POST","akses":"astap,admin","routes":"\/praktikum\/{id}"},{"route":"practicum.move","method":"GET","akses":"astap,admin","routes":"\/praktikum\/{id}\/move\/{id}\/{id}"},{"route":"practicum.move_student_assistant","method":"POST","akses":"astap,admin","routes":"\/praktikum\/{id}\/move\/{id}\/{id}"},{"route":"practicum.addAssistant","method":"GET","akses":"astap,admin","routes":"\/praktikum\/{id}\/addassistant"},{"route":"practicum.insertAssistant","method":"POST","akses":"astap,admin","routes":"\/praktikum\/{id}\/addassistant"},{"route":"practicum.addStudent","method":"GET","akses":"astap,admin","routes":"\/praktikum\/{id}\/addmahasiswa"},{"route":"practicum.insertStudent","method":"POST","akses":"astap,admin","routes":"\/praktikum\/{id}\/addmahasiswa"},{"route":"practicum.detailStudent","method":"GET","akses":"astap,admin","routes":"\/praktikum\/addmahasiswa\/{id}"},{"route":"Praktikum.Manage Praktikum","method":"GET","akses":"astap,admin","routes":"\/praktikum\/manage-praktikum"},{"route":"practicum.store","method":"POST","akses":"astap,admin","routes":"\/praktikum"},{"route":"practicum.destroy","method":"DELETE","akses":"astap,admin","routes":"\/praktikum\/{id}"},{"route":"practicum.update","method":"PATCH","akses":"astap,admin","routes":"\/praktikum\/{id}"},{"route":"Asisten.Manage Asisten","method":"GET","akses":"admin","routes":"\/asisten\/manage-asisten"},{"route":"assistant.getAssistantRoleId","method":"GET","akses":"admin","routes":"\/manage-asisten\/getAssistantRoleId"},{"route":"assistant.delete","method":"DELETE","akses":"admin","routes":"\/manage-asisten\/{id}"},{"route":"assistant.store","method":"POST","akses":"admin","routes":"\/manage-asisten"},{"route":"assistant.getUser","method":"GET","akses":"admin","routes":"\/manage-asisten\/getUser\/{id}"},{"route":"assistant.getRooms","method":"GET","akses":"admin","routes":"\/manage-asisten\/getRooms"},{"route":"assistant.updateUser","method":"PATCH","akses":"admin","routes":"\/manage-asisten\/users\/{id}"},{"route":"assistant.updateRoom","method":"PATCH","akses":"admin","routes":"\/manage-asisten\/users\/{id}\/room"},{"route":"Result","method":"GET","akses":"admin","routes":"\/result"},{"route":"result.result-by-event","method":"GET","akses":"admin","routes":"\/result\/event\/{id}"},{"route":"result.generate-result","method":"POST","akses":"admin","routes":"\/result\/generate\/event\/{id}\/subject\/{id}"},{"route":"result.update-event-generated-status","method":"POST","akses":"admin","routes":"\/result\/updateEventGeneratedStatus\/{id}"},{"route":"result.assign-student","method":"POST","akses":"admin","routes":"\/result\/assignStudent"},{"route":"Event","method":"GET","akses":"admin","routes":"\/event"},{"route":"event.add","method":"POST","akses":"admin","routes":"\/event"},{"route":"event.edit","method":"POST","akses":"admin","routes":"\/event\/{id}"},{"route":"event.changeStatus","method":"POST","akses":"admin","routes":"\/event\/{id}\/status"},{"route":"event.delete","method":"POST","akses":"admin","routes":"\/event\/delete\/{id}"},{"route":"download-template-jadwal","method":"GET","akses":"admin","routes":"\/download-template-jadwal"},{"route":"download-template-prs","method":"GET","akses":"admin","routes":"\/download-template-prs"},{"route":"Contact","method":"GET","akses":"admin","routes":"\/contact"},{"route":"contact.store","method":"POST","akses":"admin","routes":"\/contact"},{"route":"contact.update","method":"POST","akses":"admin","routes":"\/contact\/{id}"},{"route":"contact.destroy","method":"DELETE","akses":"admin","routes":"\/contact\/{id}"},{"route":"viewPRS","method":"GET","akses":"admin","routes":"\/viewPRS\/{id}"},{"route":"addPRS","method":"POST","akses":"admin,astap,asdos","routes":"\/addPRS"},{"route":"deletePRS","method":"DELETE","akses":"admin,astap,asdos","routes":"\/deletePRS\/{id}\/student\/{prs}"},{"route":"MyPrs","method":"GET","akses":"admin,astap,asdos","routes":"\/my-prs"},{"route":"practicum.destroyAll","method":"POST","akses":"astap,admin","routes":"\/deleteAllPracticum"},{"route":"Asisten.View Jadwal Ajar","method":"GET","akses":"asdos, astap, admin","routes":"\/asisten\/viewAjar"}]
        JSON;
        $data = json_decode($json, true);
        // dd($data);
        foreach ($data as $d) {
            if (str_contains($d['akses'], 'admin')) {
                RoleRoutes::create([
                    'route' => $d['routes'],
                    'name' => $d['route'],
                    'method' => $d['method'],
                    'role_id' => Role::where('slug', 'admin')->first()->id
                ]);
            }
            if (str_contains($d['akses'], 'astap')) {
                RoleRoutes::create([
                    'route' => $d['routes'],
                    'name' => $d['route'],
                    'method' => $d['method'],
                    'role_id' => Role::where('slug', 'astap')->first()->id
                ]);
            }
            if (str_contains($d['akses'], 'asdos')) {
                RoleRoutes::create([
                    'route' => $d['routes'],
                    'name' => $d['route'],
                    'method' => $d['method'],
                    'role_id' => Role::where('slug', 'asdos')->first()->id
                ]);
            }
            if (str_contains($d['akses'], 'student')) {
                RoleRoutes::create([
                    'route' => $d['routes'],
                    'name' => $d['route'],
                    'method' => $d['method'],
                    'role_id' => Role::where('slug', 'student')->first()->id
                ]);
            }
        }
    }
}
