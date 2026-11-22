<!DOCTYPE html>
<html lang="id">
<head>
@include('layouts.head')
</head>
<body>
<div class="auth">
    <section class="auth-visual">
        <a class="auth-brand" href="{{ route('login') }}">
            <span class="brand-mark">S</span>
            <span class="brand-text"><b>siakam</b><small>Sistem Pelaporan Fasilitas</small></span>
        </a>

        <div class="auth-copy">
            <span class="auth-kicker"><x-icon name="spark" :size="13" /> Fakultas Ilmu Komputer</span>
            <h1>Fasilitas kampus yang bermasalah, kini terlacak sampai tuntas.</h1>
            <p>Laporkan kerusakan dan pemborosan listrik dalam satu alur yang jelas — dari laporan mahasiswa, penanganan admin, hingga evaluasi pimpinan.</p>

            <ol class="auth-flow">
                <li>
                    <span class="num">1</span>
                    <div><b>Laporkan</b><span>Pilih gedung, ruangan, kategori, dan lampirkan foto bukti.</span></div>
                </li>
                <li>
                    <span class="num">2</span>
                    <div><b>Ditangani</b><span>Admin memverifikasi dan mencatat setiap tindakan penanganan.</span></div>
                </li>
                <li>
                    <span class="num">3</span>
                    <div><b>Dievaluasi</b><span>Pimpinan memantau tren, kinerja, dan area yang perlu perhatian.</span></div>
                </li>
            </ol>
        </div>

        <p class="auth-foot">© {{ now()->year }} SIAKAM · Universitas Pembangunan Nasional Veteran Jakarta</p>
    </section>

    <section class="auth-form">
        <div class="auth-card">
            @yield('content')
        </div>
    </section>
</div>
</body>
</html>
