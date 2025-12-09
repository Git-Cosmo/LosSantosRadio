@props([
    'title' => 'Quick Links',
    'items' => []
])

<div class="sidebar-widget card">
    <div class="card-header">
        <h3 class="card-title" style="font-size: 1.125rem; font-weight: 600;">
            {{ $title }}
        </h3>
    </div>
    <div class="card-body" style="padding: 0;">
        <div class="sidebar-widget-list">
            {{ $slot }}
        </div>
    </div>
</div>
