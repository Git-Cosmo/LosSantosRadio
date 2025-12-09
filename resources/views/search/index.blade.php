<x-layouts.app :title="'Search'">
    <div class="card">
        <div class="card-header">
            <h1 class="card-title" style="font-size: 1.5rem;">
                <i class="fas fa-search" style="color: var(--color-accent);"></i>
                Search
            </h1>
        </div>
        <div class="card-body">
            <form action="{{ route('search') }}" method="GET" style="margin-bottom: 2rem;">
                <div style="display: flex; gap: 0.5rem;">
                    <input type="text"
                           name="q"
                           value="{{ $query }}"
                           placeholder="Search news, events, games, videos..."
                           class="form-input"
                           style="flex: 1;"
                           autofocus>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Search
                    </button>
                </div>
            </form>

            @if($query)
                <h2 style="font-size: 1.125rem; margin-bottom: 1rem; color: var(--color-text-secondary);">
                    Results for "{{ $query }}"
                </h2>

                @if(count($results) > 0)
                    <div class="search-results">
                        @foreach($results as $result)
                            <div class="search-result" style="padding: 1rem; border-bottom: 1px solid var(--color-border); transition: background 0.2s;">
                                <div style="display: flex; align-items: flex-start; gap: 1rem;">
                                    <div style="width: 32px; height: 32px; background: var(--color-bg-tertiary); border-radius: 6px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                        @switch($result['type'])
                                            @case('news')
                                                <i class="fas fa-newspaper" style="color: var(--color-accent);" aria-label="News article"></i>
                                                @break
                                            @case('event')
                                                <i class="fas fa-calendar" style="color: var(--color-accent);" aria-label="Event"></i>
                                                @break
                                            @case('poll')
                                                <i class="fas fa-poll" style="color: var(--color-info);" aria-label="Poll"></i>
                                                @break
                                            @case('game')
                                                <i class="fas fa-gamepad" style="color: var(--color-accent);" aria-label="Game"></i>
                                                @break
                                            @case('free_game')
                                                <i class="fas fa-gift" style="color: var(--color-success);" aria-label="Free game"></i>
                                                @break
                                            @case('deal')
                                                <i class="fas fa-tags" style="color: var(--color-warning);" aria-label="Game deal"></i>
                                                @break
                                            @case('video')
                                                <i class="fas fa-video" style="color: var(--color-danger);" aria-label="Video"></i>
                                                @break
                                            @case('dj')
                                                <i class="fas fa-microphone" style="color: var(--color-accent);" aria-label="DJ profile"></i>
                                                @break
                                            @default
                                                <i class="fas fa-file" style="color: var(--color-text-muted);" aria-label="Content"></i>
                                        @endswitch
                                    </div>
                                    <div style="flex: 1; min-width: 0;">
                                        <a href="{{ $result['url'] }}" style="font-weight: 600; font-size: 1rem; text-decoration: none;">
                                            {{ $result['title'] }}
                                        </a>
                                        <div style="display: flex; align-items: center; gap: 0.5rem; margin-top: 0.25rem;">
                                            <span class="badge badge-gray" style="font-size: 0.625rem;">{{ ucfirst(str_replace('_', ' ', $result['type'])) }}</span>
                                            <span style="color: var(--color-text-muted); font-size: 0.8125rem;">{{ $result['date'] }}</span>
                                        </div>
                                        @if($result['description'])
                                            <p style="color: var(--color-text-secondary); font-size: 0.875rem; margin-top: 0.5rem;">
                                                {{ $result['description'] }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div style="text-align: center; padding: 3rem;">
                        <i class="fas fa-search" style="font-size: 4rem; color: var(--color-text-muted); margin-bottom: 1rem; display: block;"></i>
                        <h3 style="color: var(--color-text-secondary); margin-bottom: 0.5rem;">No Results Found</h3>
                        <p style="color: var(--color-text-muted);">Try different keywords or browse our content using the navigation menu.</p>
                    </div>
                @endif
            @else
                <div style="text-align: center; padding: 3rem;">
                    <i class="fas fa-search" style="font-size: 4rem; color: var(--color-text-muted); margin-bottom: 1rem; display: block;"></i>
                    <h3 style="color: var(--color-text-secondary); margin-bottom: 0.5rem;">Start Searching</h3>
                    <p style="color: var(--color-text-muted);">Enter a search term above to find news, events, games, videos, and more.</p>
                </div>
            @endif
        </div>
    </div></x-layouts.app>
