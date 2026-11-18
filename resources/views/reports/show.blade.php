@extends('layouts.app')
@section('title', 'Laporan #'.$report->id)
@section('content')
@php
    $u = auth()->user();
    $back = match (true) {
        $u->role === 'mahasiswa' => route('mahasiswa.dashboard'),
        $u->role === 'ob' => route('ob.dashboard'),
        default => route('admin.laporan.index'),
    };
    $next = $report->nextStatus();
    $canUpdate = in_array($u->role, ['ob', 'admin', 'superadmin']) && $next;
@endphp
<div class="head">
    <div>
        <a class="small" href="{{ $back }}">Kembali ke daftar</a>
        <h1 style="margin-top:6px">{{ $report->category->name }}</h1>
        <p>Laporan #{{ $report->id }}, dikirim {{ $report->created_at->translatedFormat('d F Y, H:i') }}</p>
    </div>
    @include('partials.status', ['report' => $report])
</div>

<div class="detail">
    <div class="stack">
        <div class="panel pad">
            <dl class="facts">
                <div><dt>Gedung</dt><dd>{{ $report->room->building->name }}</dd></div>
                <div><dt>Ruangan</dt><dd>{{ $report->room->name }}</dd></div>
                <div><dt>Pelapor</dt><dd>{{ $report->user->name }}@if($report->user->nim) <span class="muted" style="font-weight:400">({{ $report->user->nim }})</span>@endif</dd></div>
                <div><dt>Jenis masalah</dt><dd>{{ $report->category->name }}</dd></div>
            </dl>
            <p style="margin-top:20px; white-space:pre-line">{{ $report->description }}</p>
            @if ($report->photo_path)
                <img class="photo" style="margin-top:20px" src="{{ route('foto.show', $report) }}" alt="Foto bukti laporan #{{ $report->id }}">
            @endif
        </div>

        @if ($canUpdate)
            <form class="panel pad" method="POST" action="{{ route('laporan.status', $report) }}">
                @csrf @method('PATCH')
                <input type="hidden" name="status" value="{{ $next }}">
                <h2 style="margin-bottom:14px">{{ $next === 'in_progress' ? 'Mulai tangani laporan ini' : 'Tandai laporan selesai' }}</h2>
                <div class="field">
                    <label for="note">Catatan penanganan (opsional)</label>
                    <textarea id="note" name="note" style="min-height:80px" maxlength="500" placeholder="{{ $next === 'in_progress' ? 'Contoh: menuju lokasi sekarang.' : 'Contoh: kabel dipasang ulang, proyektor sudah menyala.' }}"></textarea>
                    <span class="hint">Status hanya bisa maju dan tidak bisa dikembalikan.</span>
                </div>
                <div class="actions" style="margin-top:16px">
                    <button class="btn btn-primary">{{ $next === 'in_progress' ? 'Ubah ke Diproses' : 'Ubah ke Selesai' }}</button>
                </div>
            </form>
        @endif
    </div>

    <div class="stack">
        <div class="panel pad s-{{ $report->status }}">
            <div class="stepper">
                @foreach (\App\Models\Report::LABELS as $key => $label)
                    <div class="{{ $loop->iteration <= $report->step() ? 'f' : '' }} s-{{ $key }}"><b>{{ $label }}</b></div>
                @endforeach
            </div>
        </div>
        <div class="panel pad">
            <h2 style="margin-bottom:18px">Riwayat penanganan</h2>
            <ul class="log">
                @foreach ($report->histories->reverse() as $h)
                    <li class="s-{{ $h->to_status }}">
                        <b>{{ \App\Models\Report::LABELS[$h->to_status] ?? $h->to_status }}</b>
                        <div class="muted small">{{ $h->author->name }}, {{ $h->created_at->translatedFormat('d M Y, H:i') }}</div>
                        @if ($h->note)<div class="note">{{ $h->note }}</div>@endif
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
@endsection
