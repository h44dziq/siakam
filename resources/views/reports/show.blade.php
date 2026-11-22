@extends('layouts.app')
@section('title', 'Laporan #'.$report->id)

@section('content')
@php
    $u = auth()->user();
    $back = match ($u->role) {
        'mahasiswa' => route('mahasiswa.riwayat'),
        'pemimpin' => route('pemimpin.monitoring'),
        default => route('admin.laporan.index'),
    };
    $next = $report->nextStatus();
    $canHandle = $u->role === 'admin' && $next;
@endphp

<div class="page-head">
    <div>
        <a class="link" href="{{ $back }}">← Kembali ke daftar</a>
        <h2 class="page-title" style="margin-top:8px">{{ $report->category->name }}</h2>
        <p class="page-sub">
            Laporan <span class="id-pill">#{{ $report->id }}</span> · dikirim {{ $report->created_at->translatedFormat('d F Y, H:i') }}
        </p>
    </div>
    <div class="page-actions"><x-status-badge :status="$report->status" /></div>
</div>

<div class="detail-grid">
    <div class="stack">
        <div class="card">
            <div class="card-head">
                <div>
                    <h3 class="card-title">Informasi laporan</h3>
                    <p class="card-sub">Detail lokasi, pelapor, dan deskripsi masalah.</p>
                </div>
            </div>
            <div class="card-body">
                <dl class="facts">
                    <div><dt>Gedung</dt><dd>{{ $report->room->building->name }}</dd></div>
                    <div><dt>Ruangan</dt><dd>{{ $report->room->name }}</dd></div>
                    <div>
                        <dt>Pelapor</dt>
                        <dd>{{ $report->user->name }}@if ($report->user->nim) <span class="muted small">({{ $report->user->nim }})</span>@endif</dd>
                    </div>
                    <div><dt>Jenis masalah</dt><dd>{{ $report->category->name }}</dd></div>
                    <div><dt>Tanggal laporan</dt><dd>{{ $report->created_at->translatedFormat('d M Y, H:i') }}</dd></div>
                    <div><dt>Usia laporan</dt><dd>{{ $report->daysOpen() }} hari</dd></div>
                </dl>

                <div class="desc">{{ $report->description }}</div>

                @if ($report->photo_path)
                    <img class="photo" src="{{ route('foto.show', $report) }}" alt="Foto bukti laporan #{{ $report->id }}">
                @endif
            </div>
        </div>

        @if ($canHandle)
            <form class="card" method="POST" action="{{ route('admin.laporan.status', $report) }}">
                @csrf
                @method('PATCH')
                <input type="hidden" name="status" value="{{ $next }}">
                <div class="card-head">
                    <div>
                        <h3 class="card-title">{{ $next === 'in_progress' ? 'Mulai tangani laporan' : 'Tandai laporan selesai' }}</h3>
                        <p class="card-sub">Status hanya bisa maju satu tahap dan tidak bisa dikembalikan.</p>
                    </div>
                    <span class="badge badge-{{ $next === 'in_progress' ? 'info' : 'success' }}">{{ \App\Models\Report::LABELS[$next] }}</span>
                </div>
                <div class="card-body">
                    <div class="field">
                        <label for="note">Catatan penanganan <span class="muted">(opsional)</span></label>
                        <textarea id="note" name="note" maxlength="500"
                                  placeholder="{{ $next === 'in_progress' ? 'Contoh: petugas menuju lokasi sekarang.' : 'Contoh: kabel dipasang ulang, proyektor sudah menyala.' }}"></textarea>
                    </div>
                </div>
                <div class="card-foot">
                    <button class="btn btn-primary">
                        <x-icon name="check" :size="16" />
                        {{ $next === 'in_progress' ? 'Ubah ke Diproses' : 'Ubah ke Selesai' }}
                    </button>
                </div>
            </form>
        @elseif ($u->role === 'pemimpin')
            <div class="alert alert-info">
                Mode pengawasan: Pemimpin dapat melihat seluruh riwayat penanganan, tetapi tidak dapat mengubah status laporan.
            </div>
        @endif
    </div>

    <div class="stack">
        <div class="card">
            <div class="card-head"><h3 class="card-title">Progres penanganan</h3></div>
            <div class="card-body">
                <div class="stepper">
                    @foreach (\App\Models\Report::LABELS as $key => $label)
                        @php $step = $loop->iteration; @endphp
                        <div class="step {{ $step < $report->step() ? 'is-done' : ($step === $report->step() ? 'is-current' : '') }}">
                            {{ $label }}
                        </div>
                    @endforeach
                </div>
                <div class="divider"></div>
                <div class="meter-label"><span>Langkah selesai</span><b>{{ $report->step() }} / 3</b></div>
                <div class="progress"><i style="width: {{ (int) round($report->step() / 3 * 100) }}%"></i></div>
            </div>
        </div>

        <div class="card">
            <div class="card-head">
                <div>
                    <h3 class="card-title">Riwayat penanganan</h3>
                    <p class="card-sub">Semua perubahan status tercatat permanen.</p>
                </div>
            </div>
            <div class="card-body">
                <div class="timeline">
                    @foreach ($report->histories->reverse() as $history)
                        @php
                            $tone = match ($history->to_status) {
                                'resolved' => 'is-success',
                                'in_progress' => 'is-info',
                                default => 'is-warn',
                            };
                        @endphp
                        <div class="timeline-item {{ $tone }}">
                            <span class="timeline-dot"></span>
                            <div class="timeline-body">
                                <b>{{ \App\Models\Report::LABELS[$history->to_status] ?? $history->to_status }}</b>
                                <div class="timeline-meta">
                                    {{ $history->author->name }} · {{ $history->created_at->translatedFormat('d M Y, H:i') }}
                                </div>
                                @if ($history->note)
                                    <div class="timeline-note">{{ $history->note }}</div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
