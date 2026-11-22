@props(['items' => [], 'color' => 'var(--brand)', 'suffix' => ''])

@php
    $max = max(1, collect($items)->max('value') ?? 1);
@endphp

<ul class="rank">
    @forelse ($items as $item)
        <li>
            <div class="rank-head">
                <b>{{ $item['label'] }}</b>
                <span>{{ $item['value'] }}{{ $suffix }}</span>
            </div>
            @if (! empty($item['meta']))<div class="rank-meta">{{ $item['meta'] }}</div>@endif
            <div class="rank-bar">
                <i style="width: {{ max(4, (int) round($item['value'] / $max * 100)) }}%; background: {{ $color }}"></i>
            </div>
        </li>
    @empty
        <li class="rank-empty">Belum ada data pada periode ini.</li>
    @endforelse
</ul>
