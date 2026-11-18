@extends('layouts.app')
@section('title', 'Pengguna')
@section('content')
@php
    $isSuper = auth()->user()->role === 'superadmin';
    $me = auth()->id();
    $canCreate = in_array($tab, ['ob', 'admin']);
    $titles = ['mahasiswa' => 'Mahasiswa', 'ob' => 'Petugas OB', 'admin' => 'Admin'];
@endphp
<div class="head">
    <div>
        <h1>Pengguna</h1>
        <p>Akun tidak pernah dihapus, hanya dinonaktifkan, supaya riwayat laporan tetap utuh.</p>
    </div>
    @if ($canCreate)<button class="btn btn-primary" data-open="dlg-create">Tambah {{ $tab === 'ob' ? 'petugas OB' : 'admin' }}</button>@endif
</div>

<div class="tabs">
    <a href="{{ route('admin.users', 'mahasiswa') }}" class="{{ $tab === 'mahasiswa' ? 'on' : '' }}">Mahasiswa</a>
    <a href="{{ route('admin.users', 'ob') }}" class="{{ $tab === 'ob' ? 'on' : '' }}">Petugas OB</a>
    @if ($isSuper)<a href="{{ route('admin.users', 'admin') }}" class="{{ $tab === 'admin' ? 'on' : '' }}">Admin</a>@endif
</div>

<form class="filters" method="GET">
    <div class="field grow"><label for="q">Cari</label><input id="q" type="text" name="q" value="{{ request('q') }}" placeholder="Nama, email{{ $tab === 'mahasiswa' ? ', atau NIM' : '' }}"></div>
    <button class="btn">Cari</button>
</form>

<div class="panel">
    @if ($users->isEmpty())
        <div class="empty"><b>Belum ada akun {{ strtolower($titles[$tab]) }}</b>{{ $canCreate ? 'Tambahkan akun lewat tombol di kanan atas.' : 'Mahasiswa muncul di sini setelah mendaftar sendiri.' }}</div>
    @else
    <div class="tbl-wrap">
        <table class="tbl">
            <thead><tr><th>Nama</th><th>Email</th>@if ($tab === 'mahasiswa')<th>NIM</th>@endif<th>Status akun</th><th></th></tr></thead>
            <tbody>
            @foreach ($users as $usr)
                <tr>
                    <td><b style="font-weight:600">{{ $usr->name }}</b></td>
                    <td>{{ $usr->email }}</td>
                    @if ($tab === 'mahasiswa')<td>{{ $usr->nim ?: '-' }}</td>@endif
                    <td>@if ($usr->is_active)Aktif @else<span class="badge-off">Nonaktif</span>@endif</td>
                    <td class="num">
                        <div class="actions" style="justify-content:flex-end">
                        @if ($usr->id !== $me)
                            @if ($tab !== 'mahasiswa')
                                <button class="btn btn-sm" type="button" data-edit
                                    data-action="{{ route('admin.users.update', $usr) }}"
                                    data-name="{{ $usr->name }}" data-email="{{ $usr->email }}" data-active="{{ $usr->is_active ? 1 : 0 }}">Ubah</button>
                            @endif
                            <form method="POST" action="{{ route('admin.users.toggle', $usr) }}">
                                @csrf @method('PATCH')
                                <button class="btn btn-sm {{ $usr->is_active ? 'btn-danger' : '' }}">{{ $usr->is_active ? 'Nonaktifkan' : 'Aktifkan' }}</button>
                            </form>
                        @endif
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    @if ($users->hasPages())<div class="pager">{{ $users->links() }}</div>@endif
    @endif
</div>

@if ($canCreate)
<dialog id="dlg-create">
    <form method="POST" action="{{ route('admin.users.store') }}">
        @csrf
        <input type="hidden" name="role" value="{{ $tab }}">
        <h2>Tambah {{ $tab === 'ob' ? 'petugas OB' : 'admin' }}</h2>
        <div class="field"><label>Nama lengkap</label><input type="text" name="name" required maxlength="100"></div>
        <div class="field"><label>Email</label><input type="email" name="email" required></div>
        <div class="field"><label>Password</label><input type="password" name="password" required minlength="8" autocomplete="new-password"></div>
        <div class="field"><label>Ulangi password</label><input type="password" name="password_confirmation" required minlength="8" autocomplete="new-password"></div>
        <div class="actions"><button class="btn btn-primary">Simpan akun</button><button class="btn" type="button" data-close>Batal</button></div>
    </form>
</dialog>

<dialog id="dlg-edit">
    <form method="POST" action="">
        @csrf @method('PUT')
        <h2>Ubah akun</h2>
        <div class="field"><label>Nama lengkap</label><input type="text" name="name" required maxlength="100"></div>
        <div class="field"><label>Email</label><input type="email" name="email" required></div>
        <div class="field"><label>Reset password</label><input type="password" name="password" minlength="8" autocomplete="new-password"><span class="hint">Kosongkan jika tidak diganti.</span></div>
        <div class="field"><label>Ulangi password baru</label><input type="password" name="password_confirmation" minlength="8" autocomplete="new-password"></div>
        <div class="field"><label>Status akun</label>
            <select name="is_active"><option value="1">Aktif</option><option value="0">Nonaktif</option></select>
        </div>
        <div class="actions"><button class="btn btn-primary">Simpan perubahan</button><button class="btn" type="button" data-close>Batal</button></div>
    </form>
</dialog>
@endif
@endsection
