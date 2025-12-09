@extends('admin.layouts.app')

@section('title', 'Theme Settings')

@section('content')
    <div class="admin-header">
        <h1>🎨 Theme Settings</h1>
        <p style="color: var(--color-text-secondary); margin-top: 0.5rem;">
            Manage global appearance themes for all users
        </p>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.theme.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label class="form-label">Active Theme</label>
                    <p style="color: var(--color-text-secondary); font-size: 0.875rem; margin-bottom: 1rem;">
                        Select a theme overlay to apply site-wide. These themes add festive visual effects without changing the core design.
                    </p>

                    <div class="theme-options" id="themeOptions">
                        {{-- No Theme --}}
                        <label class="theme-option">
                            <input type="radio" name="theme" value="none" 
                                   {{ $activeTheme === 'none' ? 'checked' : '' }} 
                                   onchange="showThemeLoadingFeedback(this)">
                            <div class="theme-card">
                                <div class="theme-preview" style="background: linear-gradient(135deg, #1a1a2e, #16213e);">
                                    <i class="fas fa-ban" style="font-size: 2rem; color: var(--color-text-muted);"></i>
                                </div>
                                <div class="theme-info">
                                    <h4 class="theme-title">No Theme</h4>
                                    <p class="theme-desc">Default appearance without overlay effects</p>
                                </div>
                            </div>
                        </label>

                        {{-- Christmas Theme --}}
                        <label class="theme-option">
                            <input type="radio" name="theme" value="christmas" 
                                   {{ $activeTheme === 'christmas' ? 'checked' : '' }} 
                                   onchange="showThemeLoadingFeedback(this)">
                            <div class="theme-card">
                                <div class="theme-preview" style="background: linear-gradient(135deg, #1a472a, #2d5016);">
                                    <i class="fas fa-tree" style="font-size: 2rem; color: #10b981;"></i>
                                    <i class="fas fa-snowflake" style="font-size: 1rem; color: white; position: absolute; top: 10px; right: 10px;"></i>
                                </div>
                                <div class="theme-info">
                                    <h4 class="theme-title">🎄 Christmas</h4>
                                    <p class="theme-desc">Festive snow effects, string lights, and holiday decorations</p>
                                </div>
                            </div>
                        </label>

                        {{-- New Year Theme --}}
                        <label class="theme-option">
                            <input type="radio" name="theme" value="newyear" 
                                   {{ $activeTheme === 'newyear' ? 'checked' : '' }} 
                                   onchange="showThemeLoadingFeedback(this)">
                            <div class="theme-card">
                                <div class="theme-preview" style="background: linear-gradient(135deg, #4c1d95, #7c2d12);">
                                    <i class="fas fa-glass-cheers" style="font-size: 2rem; color: #f59e0b;"></i>
                                    <i class="fas fa-star" style="font-size: 1rem; color: #ec4899; position: absolute; top: 10px; right: 10px;"></i>
                                </div>
                                <div class="theme-info">
                                    <h4 class="theme-title">🎉 New Year</h4>
                                    <p class="theme-desc">Celebratory fireworks, confetti, and party effects</p>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>

                <noscript>
                    <button type="submit" class="btn btn-primary" style="margin-top: 1rem;">
                        <i class="fas fa-save"></i> Save Changes
                    </button>
                </noscript>
            </form>
        </div>
    </div>

    <div class="card" style="margin-top: 1.5rem;">
        <div class="card-header">
            <h3>Theme Details</h3>
        </div>
        <div class="card-body">
            <div class="theme-details">
                <div class="detail-item">
                    <h4>🎄 Christmas Theme</h4>
                    <ul>
                        <li>Animated snowfall effect across the entire page</li>
                        <li>Colorful string lights at the top of the page</li>
                        <li>Festive corner decorations (tree, star, gift, bell)</li>
                        <li>Red and green accent colors on interactive elements</li>
                        <li>Does not affect core functionality or layout</li>
                    </ul>
                </div>

                <div class="detail-item">
                    <h4>🎉 New Year Theme</h4>
                    <ul>
                        <li>Periodic firework displays across the screen</li>
                        <li>Animated confetti falling effect</li>
                        <li>Rainbow color animations on key elements</li>
                        <li>Party banner with celebration message</li>
                        <li>Purple and pink party color scheme</li>
                    </ul>
                </div>

                <div class="detail-item">
                    <h4>📝 Important Notes</h4>
                    <ul>
                        <li>Themes are applied globally to all users (no user override)</li>
                        <li>Changes take effect immediately across the site</li>
                        <li>Themes respect <code>prefers-reduced-motion</code> for accessibility</li>
                        <li>Performance optimized with requestAnimationFrame</li>
                        <li>Themes can be changed at any time without affecting data</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    

    {{-- Theme Loading Overlay --}}
    <div class="theme-loading-overlay" id="themeLoadingOverlay">
        <div class="theme-loading-content">
            <div class="theme-loading-spinner"></div>
            <p style="color: var(--color-text-primary); font-weight: 600; margin-bottom: 0.5rem;">
                Applying Theme
            </p>
            <p style="color: var(--color-text-secondary); font-size: 0.875rem;">
                Please wait...
            </p>
        </div>
    </div>

    <script>
        function showThemeLoadingFeedback(input) {
            const overlay = document.getElementById('themeLoadingOverlay');
            overlay.classList.add('active');
            
            // Add a small delay for better UX
            setTimeout(() => {
                input.form.submit();
            }, 300);
        }
    </script>
@endsection
