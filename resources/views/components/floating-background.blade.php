{{-- Floating Background Effects Component --}}
{{-- Reusable visual effects extracted from coming-soon page --}}

@props([
    'intensity' => 'subtle',
    'icons' => ['music', 'headphones', 'radio', 'microphone', 'volume-up', 'compact-disc', 'gamepad'],
])

@php
    $iconCount = match($intensity) {
        'subtle' => 4,
        'medium' => 7,
        'full' => 10,
        default => 4,
    };
    
    $opacity = match($intensity) {
        'subtle' => '0.05',
        'medium' => '0.08',
        'full' => '0.1',
        default => '0.05',
    };

    $selectedIcons = array_slice($icons, 0, $iconCount);
@endphp

<div class="floating-background-wrapper" aria-hidden="true">
    @foreach($selectedIcons as $index => $icon)
        @php
            $left = ($index * (100 / $iconCount)) + (5 * ($index % 2));
            $delay = $index * 2;
        @endphp
        <div class="floating-bg-icon" style="left: {{ $left }}%; animation-delay: {{ $delay }}s;">
            <i class="fas fa-{{ $icon }}"></i>
        </div>
    @endforeach
</div>
