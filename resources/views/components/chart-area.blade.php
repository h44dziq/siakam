@props(['labels' => [], 'series' => [], 'height' => 240])

@php
    $w = 760;
    $h = (int) $height;
    $padL = 44;
    $padR = 16;
    $padT = 18;
    $padB = 30;
    $innerW = $w - $padL - $padR;
    $innerH = $h - $padT - $padB;
    $values = collect($series)->flatMap(fn ($s) => $s['data'] ?? [])->all();
    $max = max(1, $values ? max($values) : 1);
    $count = max(count($labels), 2);
    $xAt = fn (int $i) => $padL + ($i * $innerW / max($count - 1, 1));
    $yAt = fn ($v) => $padT + $innerH - ((float) $v / $max * $innerH);
    $ticks = 4;
@endphp

<div class="chart">
    <svg viewBox="0 0 {{ $w }} {{ $h }}" preserveAspectRatio="xMidYMid meet" role="img" aria-label="Grafik tren laporan">
        @for ($t = 0; $t <= $ticks; $t++)
            @php $gridY = $padT + $innerH - ($t / $ticks * $innerH); @endphp
            <line x1="{{ $padL }}" x2="{{ $w - $padR }}" y1="{{ $gridY }}" y2="{{ $gridY }}" class="chart-grid" />
            <text x="{{ $padL - 10 }}" y="{{ $gridY + 4 }}" class="chart-axis" text-anchor="end">{{ (int) round($max * $t / $ticks) }}</text>
        @endfor

        @if (count($labels))
            @foreach ($series as $s)
                @php
                    $points = collect($s['data'] ?? [])->map(fn ($v, $i) => [$xAt($i), $yAt($v)]);
                    $line = $points->map(fn ($p, $i) => ($i === 0 ? 'M' : 'L').round($p[0], 1).' '.round($p[1], 1))->implode(' ');
                    $area = $line.' L'.round($xAt(count($labels) - 1), 1).' '.($padT + $innerH)
                        .' L'.round($xAt(0), 1).' '.($padT + $innerH).' Z';
                    $color = $s['color'] ?? '#14b8a6';
                @endphp
                <path d="{{ $area }}" fill="{{ $color }}" class="chart-area" />
                <path d="{{ $line }}" fill="none" stroke="{{ $color }}" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" />
                @foreach ($points as $p)
                    <circle cx="{{ $p[0] }}" cy="{{ $p[1] }}" r="3" fill="#fff" stroke="{{ $color }}" stroke-width="2" />
                @endforeach
            @endforeach

            @foreach ($labels as $i => $label)
                <text x="{{ $xAt($i) }}" y="{{ $h - 8 }}" class="chart-axis" text-anchor="middle">{{ $label }}</text>
            @endforeach
        @endif
    </svg>

    <div class="chart-legend">
        @foreach ($series as $s)
            <span><i style="background: {{ $s['color'] ?? '#14b8a6' }}"></i>{{ $s['name'] ?? '' }}</span>
        @endforeach
    </div>
</div>
