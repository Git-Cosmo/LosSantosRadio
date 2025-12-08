---
applyTo: "resources/views/**"
applyTo: "resources/css/**"
applyTo: "resources/js/**"
---

# Frontend Development Instructions

## Blade Templates

### Component Usage

Prefer Blade components over includes for reusable UI:

```blade
{{-- Good: Use components --}}
<x-floating-background intensity="subtle" :icons="[]" />

{{-- Avoid: Direct includes for reusable elements --}}
@include('partials.background')
```

### Layout Structure

Use layouts for consistent page structure:

```blade
@extends('layouts.app')

@section('title', 'Page Title')

@section('content')
    <div class="container mx-auto px-4">
        {{-- Content here --}}
    </div>
@endsection
```

### Form Security

Always include CSRF tokens in forms:

```blade
<form method="POST" action="{{ route('events.like', $event) }}">
    @csrf
    <button type="submit">Like</button>
</form>
```

## Tailwind CSS

### Responsive Design

Use mobile-first responsive classes:

```blade
{{-- Mobile first --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
    {{-- Content --}}
</div>
```

### Common Patterns

```blade
{{-- Card component --}}
<div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 hover:shadow-xl transition-shadow">
    {{-- Card content --}}
</div>

{{-- Button styles --}}
<button class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
    Click Me
</button>

{{-- Gradient text --}}
<h1 class="text-3xl font-bold bg-gradient-to-r from-blue-500 to-purple-600 bg-clip-text text-transparent">
    Gradient Title
</h1>
```

### Avoid Arbitrary Values

Prefer standard Tailwind classes over arbitrary values:

```blade
{{-- Good: Standard classes --}}
<div class="p-4 mt-8">

{{-- Avoid: Arbitrary values unless necessary --}}
<div class="p-[17px] mt-[33px]">
```

## Alpine.js

### Simple Interactivity

Use Alpine.js for simple interactive components:

```blade
<div x-data="{ open: false }">
    <button @click="open = !open">Toggle</button>
    <div x-show="open" x-transition>
        Content
    </div>
</div>
```

### Common Patterns

```blade
{{-- Dropdown --}}
<div x-data="{ open: false }" @click.away="open = false">
    <button @click="open = !open">Menu</button>
    <div x-show="open" x-transition>
        Dropdown items
    </div>
</div>

{{-- Tab switching --}}
<div x-data="{ tab: 'tab1' }">
    <button @click="tab = 'tab1'" :class="{ 'active': tab === 'tab1' }">Tab 1</button>
    <button @click="tab = 'tab2'" :class="{ 'active': tab === 'tab2' }">Tab 2</button>
    
    <div x-show="tab === 'tab1'">Tab 1 content</div>
    <div x-show="tab === 'tab2'">Tab 2 content</div>
</div>

{{-- Form submission with loading state --}}
<form x-data="{ loading: false }" @submit="loading = true">
    <button type="submit" :disabled="loading">
        <span x-show="!loading">Submit</span>
        <span x-show="loading">Loading...</span>
    </button>
</form>
```

## Accessibility

Always include proper accessibility attributes:

```blade
{{-- Images --}}
<img src="{{ $image }}" alt="{{ $event->title }}" />

{{-- Buttons --}}
<button aria-label="Close modal" @click="close()">
    <x-icon name="x" />
</button>

{{-- Links --}}
<a href="{{ route('events.show', $event) }}" 
   aria-label="View {{ $event->title }} event details">
    Read More
</a>
```

## Performance

### Lazy Loading

Use lazy loading for images:

```blade
<img src="{{ $image }}" alt="{{ $title }}" loading="lazy" />
```

### Conditional Asset Loading

Load assets only when needed:

```blade
@push('scripts')
    <script src="{{ asset('js/chart.js') }}" defer></script>
@endpush
```

## Common Blade Directives

```blade
{{-- Authentication checks --}}
@auth
    <a href="{{ route('profile') }}">Profile</a>
@endauth

@guest
    <a href="{{ route('login') }}">Login</a>
@endguest

{{-- Loops with empty state --}}
@forelse($items as $item)
    <div>{{ $item->title }}</div>
@empty
    <p>No items found</p>
@endforelse

{{-- Conditional rendering --}}
@if($condition)
    <div>Content</div>
@elseif($otherCondition)
    <div>Other content</div>
@else
    <div>Default content</div>
@endif

{{-- Inline conditions --}}
<div class="{{ $isActive ? 'active' : '' }}">
```
