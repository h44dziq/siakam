@props(['title', 'text' => null, 'icon' => 'inbox'])

<div class="empty">
    <span class="empty-icon"><x-icon :name="$icon" :size="24" /></span>
    <b>{{ $title }}</b>
    @if ($text)<p>{{ $text }}</p>@endif
    {{ $slot }}
</div>
