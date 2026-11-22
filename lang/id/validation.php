<?php

/*
| Pesan validasi bahasa Indonesia. Kunci yang tidak ada di sini otomatis
| memakai terjemahan bawaan Laravel (fallback en).
*/
return [
    'required' => 'Kolom :attribute wajib diisi.',
    'email' => 'Format :attribute tidak valid.',
    'unique' => ':attribute sudah dipakai.',
    'exists' => ':attribute yang dipilih tidak tersedia.',
    'confirmed' => 'Konfirmasi :attribute tidak sama.',
    'current_password' => 'Password saat ini salah.',
    'boolean' => 'Kolom :attribute harus berupa ya atau tidak.',
    'in' => ':attribute yang dipilih tidak valid.',
    'string' => 'Kolom :attribute harus berupa teks.',
    'image' => 'Berkas :attribute harus berupa gambar.',
    'min' => [
        'string' => 'Kolom :attribute minimal :min karakter.',
        'file' => 'Ukuran :attribute minimal :min kilobyte.',
        'numeric' => 'Kolom :attribute minimal :min.',
    ],
    'max' => [
        'string' => 'Kolom :attribute maksimal :max karakter.',
        'file' => 'Ukuran :attribute maksimal :max kilobyte.',
        'numeric' => 'Kolom :attribute maksimal :max.',
    ],

    'attributes' => [
        'name' => 'nama',
        'username' => 'username',
        'email' => 'email',
        'password' => 'password',
        'current_password' => 'password saat ini',
        'description' => 'deskripsi',
        'building_id' => 'gedung',
        'room_id' => 'ruangan',
        'category_id' => 'kategori',
        'photo' => 'foto',
        'role' => 'peran',
        'is_active' => 'status akun',
        'note' => 'catatan',
    ],
];
