<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * SIAKAM kini hanya punya tiga role: mahasiswa, admin, pemimpin.
 * Role lama dipetakan ke admin supaya akun dan riwayat laporannya tetap utuh.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::table('users')
            ->whereIn('role', ['ob', 'superadmin'])
            ->update(['role' => 'admin']);
    }

    public function down(): void
    {
        // Pemetaan role tidak bisa dikembalikan secara akurat.
    }
};
