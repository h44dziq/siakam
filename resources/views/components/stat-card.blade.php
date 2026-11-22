@props(['label', 'value', 'icon' => 'dot', 'tone' => 'brand', 'hint' => null, 'delta' => null, 'compare' => null])

<article class="stat tone-{{ $tone }}">
    <div class="stat-head">
        <span class="stat-icon"><x-icon :name="$icon" :size="18" /></span>
        <span class="stat-label">{{ $label }}</span>
    </div>
    <div class="stat-value">{{ $value }}</div>
    <div class="stat-foot">
        @if ($delta !== null)
            <span class="delta {{ $delta > 0 ? 'up' : ($delta < 0 ? 'down' : 'flat') }}">
                <x-icon :name="$delta > 0 ? 'trend-up' : ($delta < 0 ? 'trend-down' : 'activity')" :size="13" />
                {{ $delta > 0 ? '+' : '' }}{{ $delta }}%
            </span>
        @endif
        @if ($hint || $compare)
            <span class="stat-hint">{{ $hint ?? 'vs '.$compare }}</span>
        @endif
    </div>
</article>
