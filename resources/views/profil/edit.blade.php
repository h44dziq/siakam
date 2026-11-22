@extends('layouts.app')
@section('title', 'Profil')

@section('content')
@php $u = auth()->user(); @endphp

<div class="page-head">
    <div>
        <h2 class="page-title">Profil pengguna</h2>
        <p class="page-sub">Kelola nama tampilan dan password akun Anda.</p>
    </div>
</div>

<div class="split">
    <form class="card" method="POST" action="{{ route('profil.update') }}">
        @csrf
        @method('PUT')
        <div class="card-head">
            <div>
                <h3 class="card-title">Informasi akun</h3>
                <p class="card-sub">Email tidak dapat diubah setelah akun dibuat.</p>
            </div>
        </div>
        <div class="card-body">
            <div class="form-grid">
                <div class="field full">
                    <label for="name">Nama</label>
                    <input id="name" type="text" name="name" value="{{ old('name', $u->name) }}" required maxlength="100">
                </div>
                <div class="field full">
                    <label for="email">Email</label>
                    <input id="email" type="email" value="{{ $u->email }}" disabled>
                </div>
                <div class="field full">
                    <h3 class="card-title" style="margin-bottom:2px">Ganti password</h3>
                    <span class="field-hint">Kosongkan jika tidak ingin mengganti password.</span>
                </div>
                <div class="field full">
                    <label for="current_password">Password saat ini</label>
                    <input id="current_password" type="password" name="current_password" autocomplete="current-password">
                </div>
                <div class="field">
                    <label for="password">Password baru</label>
                    <input id="password" type="password" name="password" autocomplete="new-password">
                </div>
                <div class="field">
                    <label for="password_confirmation">Ulangi password baru</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" autocomplete="new-password">
                </div>
            </div>
        </div>
        <div class="card-foot">
            <button class="btn btn-primary"><x-icon name="check" :size="16" /> Simpan profil</button>
        </div>
    </form>

    <div class="stack">
        <div class="card">
            <div class="card-body">
                <div class="meta-row" style="align-items:center">
                    <span class="avatar lg">{{ $u->initials() }}</span>
                    <div>
                        <div class="cell-title" style="font-size:16px">{{ $u->name }}</div>
                        <div class="cell-sub">{{ $u->email }}</div>
                        <div style="margin-top:8px"><span class="badge badge-info"><i class="dot"></i>{{ $u->roleLabel() }}</span></div>
                    </div>
                </div>
                <div class="divider"></div>
                <dl class="facts" style="grid-template-columns: minmax(0, 1fr)">
                    @if ($u->nim)
                        <div><dt>NIM</dt><dd>{{ $u->nim }}</dd></div>
                    @endif
                    <div><dt>Status akun</dt><dd>{{ $u->is_active ? 'Aktif' : 'Nonaktif' }}</dd></div>
                    <div><dt>Bergabung</dt><dd>{{ $u->created_at->locale('id')->translatedFormat('d F Y') }}</dd></div>
                </dl>
            </div>
        </div>

        <div class="card">
            <div class="card-head"><h3 class="card-title">Keamanan akun</h3></div>
            <div class="card-body">
                <p class="muted small">Gunakan password minimal 8 karakter dan jangan bagikan akun Anda. Perubahan password akan langsung berlaku pada sesi berikutnya.</p>
            </div>
        </div>
    </div>
</div>
@endsection
