@props(['data' => [], 'center' => null, 'label' => 'Total'])

@php
    $sum = collect($data)->sum('value');
    $circumference = 2 * M_PI * 54;
    $offset = 0;
@endphp

<div class="donut-chart">
    <div class="donut-wrap">
        <svg viewBox="0 0 140 140" class="donut" role="img" aria-label="Distribusi status laporan">
            <circle cx="70" cy="70" r="54" fill="none" stroke="var(--line)" stroke-width="16" />
            @foreach ($data as $item)
                @php
                    $length = $sum > 0 ? ($item['value'] / $sum) * $circumference : 0;
                @endphp
                @if ($length > 0)
                    <circle cx="70" cy="70" r="54" fill="none" stroke="{{ $item['color'] }}" stroke-width="16"
                            stroke-dasharray="{{ round($length, 2) }} {{ round($circumference - $length, 2) }}"
                            stroke-dashoffset="{{ round(-$offset, 2) }}"
                            transform="rotate(-90 70 70)" />
                    @php $offset += $length; @endphp
                @endif
            @endforeach
        </svg>
        <div class="donut-center">
            <b>{{ $center ?? $sum }}</b>
            <span>{{ $label }}</span>
        </div>
    </div>
    <ul class="donut-legend">
        @foreach ($data as $item)
            <li>
                <i style="background: {{ $item['color'] }}"></i>
                <span>{{ $item['label'] }}</span>
                <b>{{ $item['value'] }}</b>
            </li>
        @endforeach
    </ul>
</div>
