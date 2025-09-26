@props(['size' => 'md', 'showText' => true, 'variant' => 'default'])

@php
    $sizes = [
        'sm' => 'w-8 h-8',
        'md' => 'w-10 h-10',
        'lg' => 'w-16 h-16',
        'xl' => 'w-20 h-20'
    ];

    $textSizes = [
        'sm' => 'text-base',
        'md' => 'text-xl',
        'lg' => 'text-2xl',
        'xl' => 'text-3xl'
    ];

    $logoSize = $sizes[$size] ?? $sizes['md'];
    $textSize = $textSizes[$size] ?? $textSizes['md'];
@endphp

<div class="flex items-center {{ $showText ? 'space-x-3' : '' }}">
    <!-- Logo Image or Placeholder -->
    @if(file_exists(public_path('images/pt-aino-logo.png')))
        <img src="{{ asset('images/pt-aino-logo.png') }}"
             alt="PT Aino Logo"
             class="{{ $logoSize }} object-contain">
    @elseif(file_exists(public_path('images/pt-aino-logo.svg')))
        <img src="{{ asset('images/pt-aino-logo.svg') }}"
             alt="PT Aino Logo"
             class="{{ $logoSize }} object-contain">
    @else
        <!-- Placeholder Logo -->
        <div class="{{ $logoSize }}
                    @if($variant === 'light') bg-white text-primary-500 border-2 border-primary-300
                    @elseif($variant === 'sidebar') bg-white text-primary-600 border-2 border-white shadow-sm
                    @elseif($variant === 'dark') bg-primary-500 text-white
                    @else bg-neutral-50 text-primary-500 @endif
                    rounded-lg flex items-center justify-center">
            <span class="font-bold
                        @if($size === 'sm') text-sm
                        @elseif($size === 'lg') text-xl
                        @elseif($size === 'xl') text-2xl
                        @else text-lg @endif">A</span>
        </div>
    @endif

    @if($showText)
        <div>
            <h1 class="{{ $textSize }} font-sans font-bold
                       @if($variant === 'light') text-white
                       @elseif($variant === 'sidebar') text-primary-700
                       @elseif($variant === 'dark') text-neutral-900
                       @else text-primary-700 @endif">PT Aino</h1>
            @if($size !== 'sm')
                <p class="@if($variant === 'light') text-primary-100
                          @elseif($variant === 'sidebar') text-neutral-600
                          @elseif($variant === 'dark') text-neutral-600
                          @else text-neutral-600 @endif
                          @if($size === 'lg' || $size === 'xl') text-base @else text-sm @endif">
                    Medical Check-Up System
                </p>
            @endif
        </div>
    @endif
</div>