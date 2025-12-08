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

<style>
    @keyframes floatUp {
        0% { transform: translateY(100vh) rotate(0deg); opacity: 0; }
        10% { opacity: {{ $opacity }}; }
        90% { opacity: {{ $opacity }}; }
        100% { transform: translateY(-100px) rotate(360deg); opacity: 0; }
    }

    .floating-background-wrapper {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
        overflow: hidden;
        z-index: 0;
    }

    .floating-bg-icon {
        position: absolute;
        font-size: 2rem;
        opacity: {{ $opacity }};
        animation: floatUp 15s linear infinite;
        color: rgb(var(--color-accent-rgb, 88 166 255));
    }

    @media (prefers-reduced-motion: reduce) {
        .floating-bg-icon {
            animation: none;
            opacity: 0 !important;
        }
    }

    @media (max-width: 768px) {
        .floating-bg-icon {
            font-size: 1.5rem;
        }
    }
</style>

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
