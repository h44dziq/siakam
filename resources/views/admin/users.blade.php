@extends('layouts.app')
@section('title', 'Manajemen Pengguna')

@section('content')
@php
    $me = auth()->id();
    $titles = ['mahasiswa' => 'Mahasiswa', 'admin' => 'Admin'];
@endphp

<div class="page-head">
    <div>
        <h2 class="page-title">Manajemen pengguna</h2>
        <p class="page-sub">Akun tidak dihapus, hanya dinonaktifkan, agar riwayat laporan tetap utuh.</p>
    </div>
    @if ($tab === 'admin')
        <div class="page-actions">
            <button class="btn btn-primary" type="button" data-open="dlg-create">
                <x-icon name="plus" :size="16" /> Tambah admin
            </button>
        </div>
    @endif
</div>

<div class="tabs">
    <a class="tab {{ $tab === 'mahasiswa' ? 'on' : '' }}" href="{{ route('admin.users', 'mahasiswa') }}">
        Mahasiswa <span class="count-pill">{{ $counts['mahasiswa'] ?? 0 }}</span>
    </a>
    <a class="tab {{ $tab === 'admin' ? 'on' : '' }}" href="{{ route('admin.users', 'admin') }}">
        Admin <span class="count-pill">{{ $counts['admin'] ?? 0 }}</span>
    </a>
</div>

<form class="filters" method="GET">
    <div class="filter-grid">
        <div class="field grow">
            <label for="q">Cari akun</label>
            <input id="q" type="text" name="q" value="{{ request('q') }}"
                   placeholder="Nama, email{{ $tab === 'mahasiswa' ? ', atau NIM' : '' }}">
        </div>
        <div class="filter-actions">
            <button class="btn"><x-icon name="search" :size="15" /> Cari</button>
            @if (request('q'))<a class="btn btn-ghost" href="{{ route('admin.users', $tab) }}">Reset</a>@endif
        </div>
    </div>
</form>

<div class="card">
    <div class="card-head">
        <div>
            <h3 class="card-title">Akun {{ strtolower($titles[$tab]) }}</h3>
            <p class="card-sub">{{ $users->total() }} akun terdaftar.</p>
        </div>
    </div>
    <div class="card-body">
        @if ($users->isEmpty())
            <x-empty-state title="Belum ada akun {{ strtolower($titles[$tab]) }}"
                text="Mahasiswa muncul otomatis setelah mendaftar." icon="users" />
        @else
            <div class="table-wrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Email</th>
                            @if ($tab === 'mahasiswa')<th class="hide-sm">NIM</th>@endif
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td>
                                    <span class="cell-title">{{ $user->name }}</span>
                                    @if ($user->id === $me)<span class="cell-sub">Akun Anda</span>@endif
                                </td>
                                <td class="hide-sm">{{ $user->email }}</td>
                                @if ($tab === 'mahasiswa')<td class="hide-sm">{{ $user->nim ?: '—' }}</td>@endif
                                <td>
                                    <span class="badge badge-{{ $user->is_active ? 'success' : 'neutral' }}">
                                        <i class="dot"></i>{{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="table-actions">
                                        @if ($user->id !== $me)
                                            @if ($tab === 'admin')
                                                <button class="btn btn-sm" type="button" data-edit
                                                    data-action="{{ route('admin.users.update', $user) }}"
                                                    data-name="{{ $user->name }}"
                                                    data-email="{{ $user->email }}"
                                                    data-active="{{ $user->is_active ? 1 : 0 }}">Ubah</button>
                                            @endif
                                            <form method="POST" action="{{ route('admin.users.toggle', $user) }}">
                                                @csrf
                                                @method('PATCH')
                                                <button class="btn btn-sm {{ $user->is_active ? 'btn-danger' : 'btn-soft' }}">
                                                    {{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                                </button>
                                            </form>
                                        @else
                                            <a class="btn btn-sm" href="{{ route('profil.edit') }}">Profil</a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $users->links() }}
        @endif
    </div>
</div>

@if ($tab === 'admin')
    <dialog id="dlg-create">
        <form method="POST" action="{{ route('admin.users.store') }}">
            @csrf
            <input type="hidden" name="role" value="admin">
            <h2>Tambah akun admin</h2>
            <div class="field"><label>Nama lengkap</label><input type="text" name="name" required maxlength="100"></div>
            <div class="field"><label>Email</label><input type="email" name="email" required></div>
            <div class="field"><label>Password</label><input type="password" name="password" required minlength="8" autocomplete="new-password"></div>
            <div class="field"><label>Ulangi password</label><input type="password" name="password_confirmation" required minlength="8" autocomplete="new-password"></div>
            <div class="form-actions">
                <button class="btn btn-primary">Simpan akun</button>
                <button class="btn btn-ghost" type="button" data-close>Batal</button>
            </div>
        </form>
    </dialog>

    <dialog id="dlg-edit">
        <form method="POST" action="">
            @csrf
            @method('PUT')
            <h2>Ubah akun admin</h2>
            <div class="field"><label>Nama lengkap</label><input type="text" name="name" required maxlength="100"></div>
            <div class="field"><label>Email</label><input type="email" name="email" required></div>
            <div class="field">
                <label>Reset password</label>
                <input type="password" name="password" minlength="8" autocomplete="new-password">
                <span class="field-hint">Kosongkan jika tidak diganti.</span>
            </div>
            <div class="field"><label>Ulangi password baru</label><input type="password" name="password_confirmation" minlength="8" autocomplete="new-password"></div>
            <div class="field">
                <label>Status akun</label>
                <select name="is_active"><option value="1">Aktif</option><option value="0">Nonaktif</option></select>
            </div>
            <div class="form-actions">
                <button class="btn btn-primary">Simpan perubahan</button>
                <button class="btn btn-ghost" type="button" data-close>Batal</button>
            </div>
        </form>
    </dialog>
@endif
@endsection
