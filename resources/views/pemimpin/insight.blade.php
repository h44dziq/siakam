@extends('layouts.app')
@section('title', 'Insight & Evaluasi')

@section('content')
<div class="page-head">
    <div>
        <h2 class="page-title">Insight & evaluasi</h2>
        <p class="page-sub">Temuan otomatis dari data laporan tahun {{ $year }} untuk bahan pengambilan keputusan.</p>
    </div>
    <form method="GET">
        <div class="field">
            <label for="year">Tahun</label>
            <select id="year" name="year" data-auto>
                @foreach ($years as $option)
                    <option value="{{ $option }}" @selected($year === $option)>{{ $option }}</option>
                @endforeach
            </select>
        </div>
    </form>
</div>

<div class="insight-grid">
    @foreach ($insights as $insight)
        <article class="insight-card tone-{{ $insight['tone'] }}">
            <span class="insight-icon"><x-icon :name="$insight['icon']" :size="18" /></span>
            <h3>{{ $insight['title'] }}</h3>
            <div class="insight-value">{{ $insight['value'] }}</div>
            <p class="insight-text">{{ $insight['text'] }}</p>
        </article>
    @endforeach
</div>

<div class="card section">
    <div class="card-head">
        <div>
            <h3 class="card-title">Laporan yang perlu perhatian</h3>
            <p class="card-sub">Laporan terlama yang belum selesai pada tahun {{ $year }}.</p>
        </div>
        <a class="link" href="{{ route('pemimpin.monitoring') }}">Buka monitoring <x-icon name="chevron-right" :size="14" /></a>
    </div>
    <div class="card-body">
        <x-report-table :reports="$attention" :show-reporter="false" :show-age="true"
            empty-title="Semua laporan tertangani"
            empty-text="Tidak ada laporan yang menunggu tindak lanjut pada tahun ini." />
    </div>
</div>
@endsection
