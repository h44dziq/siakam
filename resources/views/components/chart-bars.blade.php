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
    $groups = count($labels);
    $groupW = $groups ? $innerW / $groups : $innerW;
    $seriesCount = max(count($series), 1);
    $barW = min(16, $groupW / ($seriesCount + 1.6));
    $gap = 4;
    $yAt = fn ($v) => $padT + $innerH - ((float) $v / $max * $innerH);
    $ticks = 4;
@endphp

<div class="chart">
    <svg viewBox="0 0 {{ $w }} {{ $h }}" preserveAspectRatio="xMidYMid meet" role="img" aria-label="Grafik perbandingan laporan">
        @for ($t = 0; $t <= $ticks; $t++)
            @php $gridY = $padT + $innerH - ($t / $ticks * $innerH); @endphp
            <line x1="{{ $padL }}" x2="{{ $w - $padR }}" y1="{{ $gridY }}" y2="{{ $gridY }}" class="chart-grid" />
            <text x="{{ $padL - 10 }}" y="{{ $gridY + 4 }}" class="chart-axis" text-anchor="end">{{ (int) round($max * $t / $ticks) }}</text>
        @endfor

        @foreach ($labels as $i => $label)
            @php
                $totalBarsW = $barW * $seriesCount + $gap * ($seriesCount - 1);
                $startX = $padL + $i * $groupW + ($groupW - $totalBarsW) / 2;
            @endphp
            @foreach ($series as $s => $set)
                @php
                    $value = $set['data'][$i] ?? 0;
                    $top = $yAt($value);
                    $barH = max(0, $padT + $innerH - $top);
                @endphp
                <rect x="{{ round($startX + $s * ($barW + $gap), 1) }}" y="{{ round($top, 1) }}"
                      width="{{ round($barW, 1) }}" height="{{ round($barH, 1) }}" rx="3"
                      fill="{{ $set['color'] ?? '#14b8a6' }}" />
            @endforeach
            <text x="{{ $padL + $i * $groupW + $groupW / 2 }}" y="{{ $h - 8 }}" class="chart-axis" text-anchor="middle">{{ $label }}</text>
        @endforeach
    </svg>

    <div class="chart-legend">
        @foreach ($series as $s)
            <span><i style="background: {{ $s['color'] ?? '#14b8a6' }}"></i>{{ $s['name'] ?? '' }}</span>
        @endforeach
    </div>
</div>
