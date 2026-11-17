<?php

namespace Database\Seeders;

use App\Models\Building;
use App\Models\Category;
use App\Models\Report;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Akun demo (password semuanya: password). Ganti sebelum dipakai sungguhan.
        User::create(['name' => 'Superadmin', 'username' => 'superadmin', 'email' => 'superadmin@siakam.test', 'password' => 'password', 'role' => 'superadmin']);
        $admin = User::create(['name' => 'Putri Sarpras', 'username' => 'admin', 'email' => 'admin@siakam.test', 'password' => 'password', 'role' => 'admin']);
        $ob = User::create(['name' => 'Rara Wijaya', 'username' => 'ob1', 'email' => 'ob@siakam.test', 'password' => 'password', 'role' => 'ob']);
        $mhs = User::create(['name' => 'dimas', 'username' => 'dimas', 'email' => '2310512001@mahasiswa.upnvj.ac.id', 'password' => 'password', 'role' => 'mahasiswa', 'nim' => '2310512001']);
        $mhs2 = User::create(['name' => 'salsa', 'username' => 'salsa', 'email' => '2310512002@mahasiswa.upnvj.ac.id', 'password' => 'password', 'role' => 'mahasiswa', 'nim' => '2310512002']);

        $layout = [
            'Gedung A' => ['Lab Komputer 1', 'Lab Komputer 2', 'Ruang Kelas A201', 'Toilet Lantai 2'],
            'Gedung B' => ['Lab Jaringan', 'Ruang Kelas B101', 'Ruang Dosen', 'Toilet Lantai 1'],
            'Gedung C' => ['Auditorium', 'Perpustakaan', 'Ruang Himpunan'],
        ];
        $rooms = collect();
        foreach ($layout as $b => $names) {
            $building = Building::create(['name' => $b]);
            foreach ($names as $n) {
                $rooms->push($building->rooms()->create(['name' => $n]));
            }
        }

        $cats = collect(['AC menyala tanpa penghuni', 'Lampu menyala atau mati', 'Pemborosan listrik', 'Kerusakan hardware', 'Toilet atau keran bocor', 'Lainnya'])
            ->map(fn ($n) => Category::create(['name' => $n]));

        $descs = [
            'AC masih menyala padahal ruangan kosong sejak siang.',
            'Dua lampu di barisan belakang berkedip dan sebagian mati.',
            'Semua monitor dibiarkan menyala setelah kelas selesai.',
            'Keyboard di meja 4 tidak terbaca, beberapa tombol lepas.',
            'Keran wastafel menetes terus, lantai jadi basah.',
            'Proyektor tidak menampilkan gambar, kabel HDMI longgar.',
        ];

        for ($i = 0; $i < 28; $i++) {
            $created = now()->subDays(rand(0, 40))->subMinutes(rand(0, 900));
            $status = collect(['reported', 'reported', 'in_progress', 'resolved', 'resolved'])->random();

            $r = Report::create([
                'user_id' => ($i % 2 ? $mhs : $mhs2)->id,
                'room_id' => $rooms->random()->id,
                'category_id' => $cats->random()->id,
                'description' => $descs[array_rand($descs)],
                'status' => $status,
                'created_at' => $created,
                'updated_at' => $created,
            ]);

            $chain = ['reported', 'in_progress', 'resolved'];
            $upTo = array_search($status, $chain);
            $prev = null;
            for ($s = 0; $s <= $upTo; $s++) {
                $r->histories()->create([
                    'changed_by' => $s === 0 ? $r->user_id : $ob->id,
                    'from_status' => $prev,
                    'to_status' => $chain[$s],
                    'note' => $s === 2 ? 'Sudah dicek dan diperbaiki.' : null,
                    'created_at' => (clone $created)->addHours($s * 3),
                    'updated_at' => (clone $created)->addHours($s * 3),
                ]);
                $prev = $chain[$s];
            }
        }
    }
}
