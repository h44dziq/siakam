@extends('layouts.app')
@section('title', 'Ringkasan')
@section('content')
@php
    $months = [1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember'];
    $exportQuery = array_filter(['month' => $f['month'], 'year' => $f['year']]);
    $max = max(1, $byCategory->max('c') ?? 1);
    $maxRoom = max(1, $topRooms->max('c') ?? 1);
@endphp
<div class="head">
    <div>
        <h1>Ringkasan fasilitas</h1>
        <p>Kondisi laporan {{ $f['month'] ? $months[(int) $f['month']] : 'sepanjang tahun' }} {{ $f['year'] ?: '(semua tahun)' }}.</p>
    </div>
    <a class="btn" href="{{ route('admin.laporan.export', $exportQuery) }}">Unduh CSV</a>
</div>

<form class="filters" method="GET">
    <div class="field">
        <label for="month">Bulan</label>
        <select id="month" name="month" onchange="this.form.submit()">
            <option value="">Semua bulan</option>
            @foreach ($months as $n => $name)<option value="{{ $n }}" @selected((int) $f['month'] === $n)>{{ $name }}</option>@endforeach
        </select>
    </div>
    <div class="field">
        <label for="year">Tahun</label>
        <select id="year" name="year" onchange="this.form.submit()">
            <option value="">Semua tahun</option>
            @foreach ($years as $y)<option value="{{ $y }}" @selected((string) $f['year'] === (string) $y)>{{ $y }}</option>@endforeach
        </select>
    </div>
    <noscript><button class="btn">Terapkan</button></noscript>
</form>

<div class="panel metrics">
    <div><b>{{ $total }}</b><span>Laporan masuk</span></div>
    <div><b>{{ $byStatus['reported'] ?? 0 }}</b><span>Menunggu ditangani</span></div>
    <div><b>{{ $byStatus['in_progress'] ?? 0 }}</b><span>Sedang diproses</span></div>
    <div><b>{{ $byStatus['resolved'] ?? 0 }}</b><span>Selesai</span></div>
</div>

<div class="cols">
    <div class="panel">
        <div class="panel-head"><h2>Tiga ruangan paling sering dilaporkan</h2></div>
        @if ($topRooms->isEmpty())
            <div class="empty">Belum ada laporan pada periode ini.</div>
        @else
        <ul class="bars">
            @foreach ($topRooms as $t)
                <li>
                    <div class="lbl"><b>{{ $t->room->name }}</b><span>{{ $t->room->building->name }}</span></div>
                    <div class="bar"><i style="width: {{ round($t->c / $maxRoom * 100) }}%"></i></div>
                    <div class="n">{{ $t->c }}</div>
                </li>
            @endforeach
        </ul>
        @endif
    </div>
    <div class="panel">
        <div class="panel-head"><h2>Laporan per jenis masalah</h2></div>
        @if ($byCategory->isEmpty())
            <div class="empty">Belum ada laporan pada periode ini.</div>
        @else
        <ul class="bars">
            @foreach ($byCategory as $c)
                <li>
                    <div class="lbl"><span style="color:var(--ink)">{{ $c->category->name }}</span></div>
                    <div class="bar"><i style="width: {{ round($c->c / $max * 100) }}%"></i></div>
                    <div class="n">{{ $c->c }}</div>
                </li>
            @endforeach
        </ul>
        @endif
    </div>
</div>
@endsection
