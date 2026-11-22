@props(['status'])

@php
    $map = [
        'reported' => ['Dilaporkan', 'warn'],
        'in_progress' => ['Diproses', 'info'],
        'resolved' => ['Selesai', 'success'],
    ];
    [$label, $tone] = $map[$status] ?? [$status, 'neutral'];
@endphp

<span class="badge badge-{{ $tone }}"><i class="dot"></i>{{ $label }}</span>
