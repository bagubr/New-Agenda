<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserLoginSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::insert([
            ["username" => "ferrykuntoaji", "nama" => "Ir. Ferry Kuntoaji, S.T.", "role" => "kepala_dinas", "devisi" => "SEKRETARIAT", "new_password" => bcrypt('12345')],
            ["username" => "ryansaputra", "nama" => "Ryan Saputra, S.T.", "role" => "kepala_bidang", "devisi" => "SEKRETARIAT", "new_password" => bcrypt('12345')],
            ["username" => "maskan", "nama" => "Maskan, S.E.,M.M.", "role" => "ketua_tim", "devisi" => "SEKRETARIAT", "new_password" => bcrypt('12345')],
            ["username" => "madesintianirwantari", "nama" => "Made Sintia Nirwantari, S.T., M.T.", "role" => "ketua_tim", "devisi" => "SEKRETARIAT", "new_password" => bcrypt('12345')],
            ["username" => "prasastiutamidewi", "nama" => "Prasasti Utami Dewi, S.T.", "role" => "ketua_tim",  "devisi" => "SEKRETARIAT", "new_password" => bcrypt('12345')],
            ["username" => "admin_sekretariat", "nama" => "Admin Sekretariat", "role" => "admin",  "devisi" => "SEKRETARIAT", "new_password" => bcrypt('12345')],

            ["username" => "gitaalfaarsyada", "nama" => "Gita Alfa Arsyada, S.T.", "role" => "kepala_bidang", "devisi" => "TATA BANGUNAN", "new_password" => bcrypt('12345')],
            ["username" => "bagusrilanto", "nama" => "Bagus Rilanto, S.T.", "role" => "ketua_tim", "devisi" => "TATA BANGUNAN", "new_password" => bcrypt('12345')],
            ["username" => "onnidhiansatria", "nama" => "Onni Dhian Satria, S.T.", "role" => "ketua_tim", "devisi" => "TATA BANGUNAN", "new_password" => bcrypt('12345')],
            ["username" => "admin_taba", "nama" => "Admin Taba", "role" => "admin",  "devisi" => "TATA BANGUNAN", "new_password" => bcrypt('12345')],

            ["username" => "transiskaluismarina", "nama" => "Ir.Transiska Luis Marina, S.T., M.M.", "role" => "kepala_bidang", "devisi" => "TATA RUANG", "new_password" => bcrypt('12345')],
            ["username" => "aditiarahmaputra", "nama" => "Aditia Rahma Putra, S.T., M.P.W.K.", "role" => "ketua_tim", "devisi" => "TATA RUANG", "new_password" => bcrypt('12345')],
            ["username" => "debbiariyanto", "nama" => "Debbi Ariyanto, S.T.", "role" => "ketua_tim", "devisi" => "TATA RUANG", "new_password" => bcrypt('12345')],
            ["username" => "febrinars", "nama" => "Febrina S, S.Ars., M.P.W.K", "role" => "ketua_tim", "devisi" => "TATA RUANG", "new_password" => bcrypt('12345')],
            ["username" => "admin_taru", "nama" => "Admin Taru", "role" => "admin",  "devisi" => "TATA RUANG", "new_password" => bcrypt('12345')],

            ["username" => "alfidianguptadi", "nama" => "Ir. Alfidian Guptadi, S.T., M.T.", "role" => "kepala_bidang", "devisi" => "JAKON", "new_password" => bcrypt('12345')],
            ["username" => "antoniushendrosulistyo", "nama" => "Antonius Hendro Sulistyo, S.T.", "role" => "ketua_tim", "devisi" => "JAKON", "new_password" => bcrypt('12345')],
            ["username" => "dietatunggalsetiyadi", "nama" => "Dieta Tunggal Setiyadi, S.T.", "role" => "ketua_tim", "devisi" => "JAKON", "new_password" => bcrypt('12345')],
            ["username" => "admin_jakon", "nama" => "Admin Jakon", "role" => "admin",  "devisi" => "JAKON", "new_password" => bcrypt('12345')],
            
            ["username" => "suriyaty", "nama" => "Suriyaty, ST, MM.", "role" => "kepala_bidang", "devisi" => "PERTANAHAN", "new_password" => bcrypt('12345')],
            ["username" => "tiarasetiawan", "nama" => "Tiara Setiawan, ST", "role" => "ketua_tim", "devisi" => "PERTANAHAN", "new_password" => bcrypt('12345')],
            ["username" => "admin_pertanahan", "nama" => "Admin Pertanahan", "role" => "admin",  "devisi" => "PERTANAHAN", "new_password" => bcrypt('12345')],
        ]);
    }
}
