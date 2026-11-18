<span class="status s-{{ $report->status }}">
    <span class="track" aria-hidden="true">@for ($i = 1; $i <= 3; $i++)<i class="{{ $i <= $report->step() ? 'f' : '' }}"></i>@endfor</span>
    {{ $report->label() }}
</span>
