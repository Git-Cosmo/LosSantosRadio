<x-layouts.app title="Analytics Dashboard">
    <div style="margin-bottom: 1.5rem;">
        <a href="{{ route('home') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Home
        </a>
    </div>

    <livewire:listener-analytics />
</x-layouts.app>
