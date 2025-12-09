<x-layouts.app :title="'Game Deals'">
    <div class="card">
        <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
            <h1 class="card-title" style="font-size: 1.5rem;">
                <i class="fas fa-tags" style="color: var(--color-accent);"></i>
                Game Deals
            </h1>
            <form action="{{ route('games.deals') }}" method="GET" style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                <select name="min_savings" class="form-input" style="width: auto;">
                    <option value="">All Savings</option>
                    <option value="50" {{ ($filters['min_savings'] ?? '') == '50' ? 'selected' : '' }}>50%+ Off</option>
                    <option value="70" {{ ($filters['min_savings'] ?? '') == '70' ? 'selected' : '' }}>70%+ Off</option>
                    <option value="90" {{ ($filters['min_savings'] ?? '') == '90' ? 'selected' : '' }}>90%+ Off</option>
                </select>
                <select name="store" class="form-input" style="width: auto;">
                    <option value="">All Stores</option>
                    @foreach($stores as $store)
                        <option value="{{ $store->id }}" {{ ($filters['store'] ?? '') == $store->id ? 'selected' : '' }}>
                            {{ $store->name }}
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-secondary">
                    <i class="fas fa-filter"></i> Filter
                </button>
            </form>
        </div>
        <div class="card-body">
            @if($deals->count() > 0)
                <div class="deals-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1.5rem;">
                    @foreach($deals as $deal)
                        <div class="deal-card card" style="overflow: hidden;">
                            <div style="height: 160px; background: var(--color-bg-tertiary); display: flex; align-items: center; justify-content: center; position: relative;">
                                @if($deal->thumb)
                                    <img src="{{ $deal->thumb }}" alt="{{ $deal->title }}" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                                @else
                                    <i class="fas fa-gamepad" style="font-size: 3rem; color: var(--color-text-muted);"></i>
                                @endif
                                <div style="position: absolute; top: 0.5rem; right: 0.5rem;">
                                    <span class="badge badge-success" style="font-size: 0.875rem; padding: 0.25rem 0.5rem;">
                                        -{{ $deal->savings_percent }}%
                                    </span>
                                </div>
                            </div>
                            <div class="card-body">
                                <h3 style="font-weight: 600; margin-bottom: 0.5rem;">{{ Str::limit($deal->title, 45) }}</h3>
                                @if($deal->store)
                                    <p style="color: var(--color-text-muted); font-size: 0.8125rem; margin-bottom: 0.5rem;">
                                        <i class="fas fa-store"></i> {{ $deal->store->name }}
                                    </p>
                                @endif
                                @if($deal->metacritic_score)
                                    <p style="color: var(--color-text-secondary); font-size: 0.8125rem; margin-bottom: 0.5rem;">
                                        <i class="fas fa-star"></i> Metacritic: {{ number_format($deal->metacritic_score) }}
                                    </p>
                                @endif
                                <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.75rem;">
                                    <span style="font-size: 1.25rem; font-weight: 700; color: var(--color-success);">
                                        ${{ number_format($deal->sale_price, 2) }}
                                    </span>
                                    <span style="font-size: 0.875rem; color: var(--color-text-muted); text-decoration: line-through;">
                                        ${{ number_format($deal->normal_price, 2) }}
                                    </span>
                                </div>
                                <a href="{{ $deal->deal_url }}" target="_blank" rel="noopener noreferrer" class="btn btn-primary" style="width: 100%;">
                                    <i class="fas fa-shopping-cart"></i> Get Deal
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div style="margin-top: 2rem;">
                    {{ $deals->withQueryString()->links() }}
                </div>
            @else
                <div style="text-align: center; padding: 3rem;">
                    <i class="fas fa-tags" style="font-size: 4rem; color: var(--color-text-muted); margin-bottom: 1rem; display: block;"></i>
                    <h3 style="color: var(--color-text-secondary); margin-bottom: 0.5rem;">No Deals Found</h3>
                    <p style="color: var(--color-text-muted);">Try adjusting your filters or check back later!</p>
                </div>
            @endif
        </div>
    </div></x-layouts.app>
