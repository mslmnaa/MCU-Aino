@props(['order'])

@php
    $badge = $order->mcu_type_badge;
@endphp

<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $badge['color'] }}">
    <span class="mr-1">{{ $badge['icon'] }}</span>
    {{ $badge['text'] }}
</span>