<x-layouts.app title="Games">
    <!-- Modern Hero Section -->
    <div style="background: linear-gradient(135deg, var(--color-bg-secondary) 0%, rgba(88, 166, 255, 0.08) 50%, rgba(168, 85, 247, 0.08) 100%); padding: 3rem 0; margin-bottom: 2rem; border-bottom: 1px solid var(--color-border);">
        <div style="max-width: 1400px; margin: 0 auto; padding: 0 1.5rem; text-align: center;">
            <div style="display: inline-flex; align-items: center; justify-content: center; width: 80px; height: 80px; background: linear-gradient(135deg, var(--color-accent), #a855f7); border-radius: 20px; margin-bottom: 1.5rem; box-shadow: 0 8px 30px rgba(88, 166, 255, 0.3);">
                <i class="fas fa-gamepad" style="font-size: 2.5rem; color: white;"></i>
            </div>
            <h1 style="font-size: 3rem; font-weight: 800; margin-bottom: 1rem; background: linear-gradient(135deg, var(--color-text-primary), var(--color-accent), #a855f7); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
                Games Hub
            </h1>
            <p style="font-size: 1.125rem; color: var(--color-text-secondary); max-width: 600px; margin: 0 auto;">
                Discover amazing games, hot deals, and free offerings for your favorite platforms
            </p>
        </div>
    </div>

    <div style="max-width: 1400px; margin: 0 auto; padding: 0 1.5rem;">
        <div style="max-width: 800px; margin: 0 auto 3rem;">
            <!-- Enhanced Search Bar -->
            <form method="GET" action="{{ route('games.index') }}" style="display: flex; gap: 0.75rem;">
                <div style="flex: 1; position: relative;">
                    <i class="fas fa-search" style="position: absolute; left: 1.25rem; top: 50%; transform: translateY(-50%); color: var(--color-text-muted); z-index: 1;"></i>
                    <input 
                        type="text" 
                        name="search" 
                        value="{{ $filters['search'] ?? '' }}"
                        placeholder="Search for games..." 
                        class="form-input"
                        style="width: 100%; padding: 1rem 1rem 1rem 3.5rem; font-size: 1rem; border-radius: 12px; border: 2px solid var(--color-border); transition: all 0.3s ease;"
                        onfocus="this.style.borderColor='var(--color-accent)'"
                        onblur="this.style.borderColor='var(--color-border)'"
                    >
                </div>
                <button type="submit" class="btn btn-primary" style="padding: 1rem 2rem; font-size: 1rem; border-radius: 12px; white-space: nowrap;">
                    <i class="fas fa-search"></i> Search
                </button>
            </form>
        </div>

            <!-- Top Deals Section -->
            @if($topDeals->count() > 0)
            <div style="margin-bottom: 4rem;">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 2rem;">
                    <h2 style="font-size: 2rem; font-weight: 700; display: flex; align-items: center; gap: 0.75rem;">
                        <span style="display: flex; align-items: center; justify-content: center; width: 48px; height: 48px; background: linear-gradient(135deg, #ef4444, #dc2626); border-radius: 12px;">
                            <i class="fas fa-fire" style="color: white;"></i>
                        </span>
                        Hot Deals
                    </h2>
                    <a href="{{ route('games.deals') }}" style="color: var(--color-accent); font-weight: 600; display: flex; align-items: center; gap: 0.5rem; text-decoration: none; transition: all 0.2s ease;" onmouseover="this.style.transform='translateX(4px)'" onmouseout="this.style.transform='translateX(0)'">
                        View All <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1.5rem;">
                    @foreach($topDeals as $deal)
                    <a href="{{ route('games.deals.show', $deal) }}" style="text-decoration: none;">
                        <div class="game-card card" style="overflow: hidden; height: 100%; transition: all 0.3s ease; border: 2px solid transparent;">
                            <div style="height: 180px; background: var(--color-bg-tertiary); display: flex; align-items: center; justify-content: center; position: relative; overflow: hidden;">
                                @if($deal->thumb)
                                    <img src="{{ $deal->thumb }}" alt="{{ $deal->title }}" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s ease;">
                                @else
                                    <i class="fas fa-gamepad" style="font-size: 3rem; color: var(--color-text-muted);"></i>
                                @endif
                                <div style="position: absolute; top: 0.75rem; right: 0.75rem;">
                                    <span style="background: linear-gradient(135deg, #ef4444, #dc2626); color: white; padding: 0.5rem 1rem; border-radius: 50px; font-weight: 700; font-size: 0.875rem; box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4);">
                                        -{{ $deal->savings_percent }}%
                                    </span>
                                </div>
                            </div>
                            <div class="card-body" style="padding: 1.25rem;">
                                @if($deal->store)
                                <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.75rem;">
                                    <span class="badge badge-gray" style="font-size: 0.75rem;">
                                        <i class="fas fa-store"></i> {{ $deal->store->name }}
                                    </span>
                                </div>
                                @endif
                                <h3 style="font-weight: 600; margin-bottom: 1rem; font-size: 1.125rem; color: var(--color-text-primary); line-height: 1.4; min-height: 2.8em;">
                                    {{ Str::limit($deal->title, 60) }}
                                </h3>
                                <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 0.75rem;">
                                    <span style="font-size: 1.75rem; font-weight: 700; color: #22c55e;">
                                        ${{ number_format($deal->sale_price, 2) }}
                                    </span>
                                    <span style="font-size: 1rem; color: var(--color-text-muted); text-decoration: line-through;">
                                        ${{ number_format($deal->normal_price, 2) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Free Games Section -->
            @if($freeGames->count() > 0)
            <div style="margin-bottom: 4rem;">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 2rem;">
                    <h2 style="font-size: 2rem; font-weight: 700; display: flex; align-items: center; gap: 0.75rem;">
                        <span style="display: flex; align-items: center; justify-content: center; width: 48px; height: 48px; background: linear-gradient(135deg, #22c55e, #16a34a); border-radius: 12px;">
                            <i class="fas fa-gift" style="color: white;"></i>
                        </span>
                        Free Games
                    </h2>
                    <a href="{{ route('games.free') }}" style="color: var(--color-accent); font-weight: 600; display: flex; align-items: center; gap: 0.5rem; text-decoration: none; transition: all 0.2s ease;" onmouseover="this.style.transform='translateX(4px)'" onmouseout="this.style.transform='translateX(0)'">
                        View All <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1.5rem;">
                    @foreach($freeGames as $game)
                    <a href="{{ route('games.free.show', $game) }}" style="text-decoration: none;">
                        <div class="game-card card" style="overflow: hidden; height: 100%; transition: all 0.3s ease; border: 2px solid transparent;">
                            <div style="height: 180px; background: linear-gradient(135deg, var(--color-bg-tertiary) 0%, rgba(34, 197, 94, 0.1) 100%); display: flex; align-items: center; justify-content: center; position: relative; overflow: hidden;">
                                @if($game->image_url)
                                    <img src="{{ $game->image_url }}" alt="{{ $game->title }}" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s ease;">
                                @else
                                    <i class="fas fa-gamepad" style="font-size: 3rem; color: var(--color-text-muted);"></i>
                                @endif
                                <div style="position: absolute; top: 0.75rem; right: 0.75rem;">
                                    <span style="background: linear-gradient(135deg, #22c55e, #16a34a); color: white; padding: 0.5rem 1rem; border-radius: 50px; font-weight: 700; font-size: 0.875rem; text-transform: uppercase; box-shadow: 0 4px 12px rgba(34, 197, 94, 0.4);">
                                        FREE
                                    </span>
                                </div>
                            </div>
                            <div class="card-body" style="padding: 1.25rem;">
                                @if($game->store)
                                <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.75rem;">
                                    <span class="badge badge-gray" style="font-size: 0.75rem;">
                                        @if($game->store === 'Epic Games')
                                            <i class="fas fa-gamepad"></i>
                                        @elseif($game->store === 'Steam')
                                            <i class="fab fa-steam"></i>
                                        @else
                                            <i class="fas fa-shopping-cart"></i>
                                        @endif
                                        {{ $game->store }}
                                    </span>
                                </div>
                                @endif
                                <h3 style="font-weight: 600; margin-bottom: 1rem; font-size: 1.125rem; color: var(--color-text-primary); line-height: 1.4; min-height: 2.8em;">
                                    {{ Str::limit($game->title, 60) }}
                                </h3>
                                @if($game->expires_at)
                                <div style="display: flex; align-items: center; gap: 0.5rem; color: var(--color-warning); font-size: 0.875rem; margin-top: auto;">
                                    <i class="far fa-clock"></i>
                                    <span>Ends {{ $game->expires_at->diffForHumans() }}</span>
                                </div>
                                @endif
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>

    <style>
        .game-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .game-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
            border-color: var(--color-accent) !important;
        }
        .game-card:hover img {
            transform: scale(1.08);
        }
    </style>
</x-layouts.app>
