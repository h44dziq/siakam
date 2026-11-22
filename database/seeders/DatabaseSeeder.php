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
        // Akun demo (password: password). Ganti sebelum dipakai sungguhan.
        User::updateOrCreate(['email' => 'pemimpin@siakam.test'], [
            'name' => 'Hendra Wijaya', 'username' => 'pemimpin', 'password' => 'password',
            'role' => 'pemimpin', 'is_active' => true,
        ]);
        $admin = User::updateOrCreate(['email' => 'admin@siakam.test'], [
            'name' => 'Putri Sarpras', 'username' => 'admin', 'password' => 'password',
            'role' => 'admin', 'is_active' => true,
        ]);
        $mhs = User::updateOrCreate(['email' => '2310512001@mahasiswa.upnvj.ac.id'], [
            'name' => 'Dimas Pratama', 'username' => 'dimas', 'password' => 'password',
            'role' => 'mahasiswa', 'nim' => '2310512001', 'is_active' => true,
        ]);
        $mhs2 = User::updateOrCreate(['email' => '2310512002@mahasiswa.upnvj.ac.id'], [
            'name' => 'Salsa Nabila', 'username' => 'salsa', 'password' => 'password',
            'role' => 'mahasiswa', 'nim' => '2310512002', 'is_active' => true,
        ]);

        $layout = [
            'Gedung A' => ['Lab Komputer 1', 'Lab Komputer 2', 'Ruang Kelas A201', 'Toilet Lantai 2'],
            'Gedung B' => ['Lab Jaringan', 'Ruang Kelas B101', 'Ruang Dosen', 'Toilet Lantai 1'],
            'Gedung C' => ['Auditorium', 'Perpustakaan', 'Ruang Himpunan'],
        ];
        $rooms = collect();
        foreach ($layout as $buildingName => $roomNames) {
            $building = Building::firstOrCreate(['name' => $buildingName]);
            foreach ($roomNames as $roomName) {
                $rooms->push($building->rooms()->firstOrCreate(['name' => $roomName]));
            }
        }

        $categories = collect([
            'AC menyala tanpa penghuni', 'Lampu menyala atau mati', 'Pemborosan listrik',
            'Kerusakan hardware', 'Toilet atau keran bocor', 'Lainnya',
        ])->map(fn ($name) => Category::firstOrCreate(['name' => $name]));

        if (Report::query()->exists()) {
            return; // jangan gandakan laporan contoh setiap kali seeder dijalankan
        }

        $descriptions = [
            'AC masih menyala padahal ruangan kosong sejak siang.',
            'Dua lampu di barisan belakang berkedip dan sebagian mati.',
            'Semua monitor dibiarkan menyala setelah kelas selesai.',
            'Keyboard di meja 4 tidak terbaca, beberapa tombol lepas.',
            'Keran wastafel menetes terus, lantai jadi basah.',
            'Proyektor tidak menampilkan gambar, kabel HDMI longgar.',
            'Lampu koridor mati sejak kemarin sehingga gelap.',
            'Kursi di baris tengah patah dan berbahaya jika dipakai.',
        ];

        $chain = ['reported', 'in_progress', 'resolved'];
        $reporterIds = [$mhs->id, $mhs2->id];

        for ($i = 0; $i < 96; $i++) {
            $created = now()->subDays(rand(0, 210))->subMinutes(rand(0, 900));
            $status = collect(['reported', 'reported', 'in_progress', 'resolved', 'resolved', 'resolved'])->random();
            $reporter = $reporterIds[$i % 2];

            $report = Report::create([
                'user_id' => $reporter,
                'room_id' => $rooms->random()->id,
                'category_id' => $categories->random()->id,
                'description' => $descriptions[array_rand($descriptions)],
                'status' => $status,
                'created_at' => $created,
                'updated_at' => $created,
            ]);

            $upTo = array_search($status, $chain);
            $prev = null;
            $at = $created;
            for ($s = 0; $s <= $upTo; $s++) {
                if ($s > 0) {
                    $at = (clone $at)->addHours(rand(2, 20));
                }
                $report->histories()->create([
                    'changed_by' => $s === 0 ? $reporter : $admin->id,
                    'from_status' => $prev,
                    'to_status' => $chain[$s],
                    'note' => $s === 2 ? 'Sudah dicek dan diperbaiki oleh petugas.' : ($s === 1 ? 'Petugas menuju lokasi.' : null),
                    'created_at' => $at,
                    'updated_at' => $at,
                ]);
                $prev = $chain[$s];
            }
        }

        // Pastikan ada satu laporan baru untuk contoh alur penanganan.
        $fresh = Report::create([
            'user_id' => $mhs->id,
            'room_id' => $rooms->first()->id,
            'category_id' => $categories->first()->id,
            'description' => 'AC di ruangan ini menyala sejak pagi padahal tidak ada kelas.',
            'status' => 'reported',
        ]);
        $fresh->histories()->create([
            'changed_by' => $mhs->id, 'from_status' => null, 'to_status' => 'reported',
        ]);
    }
}
