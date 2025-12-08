<x-admin.layouts.app title="Theme Settings">
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

    <style>
        .theme-options {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
        }

        .theme-option {
            cursor: pointer;
        }

        .theme-option input[type="radio"] {
            display: none;
        }

        .theme-card {
            border: 2px solid var(--color-border);
            border-radius: 12px;
            overflow: hidden;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: var(--color-bg-tertiary);
            position: relative;
        }

        .theme-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, transparent 0%, var(--color-accent) 100%);
            opacity: 0;
            transition: opacity 0.3s ease;
            pointer-events: none;
            z-index: 0;
        }

        .theme-option input[type="radio"]:checked + .theme-card {
            border-color: var(--color-accent);
            box-shadow: 0 0 0 3px rgba(88, 166, 255, 0.2), 0 8px 24px rgba(0, 0, 0, 0.15);
            transform: scale(1.02);
        }

        .theme-option input[type="radio"]:checked + .theme-card::before {
            opacity: 0.05;
        }

        .theme-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
            border-color: var(--color-accent);
        }

        .theme-preview,
        .theme-info {
            position: relative;
            z-index: 1;
        }

        .theme-preview {
            height: 120px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .theme-info {
            padding: 1rem;
        }

        .theme-title {
            font-size: 1.125rem;
            font-weight: 600;
            color: var(--color-text-primary);
            margin-bottom: 0.5rem;
        }

        .theme-desc {
            color: var(--color-text-secondary);
            font-size: 0.875rem;
            line-height: 1.5;
        }

        .theme-details {
            display: grid;
            gap: 1.5rem;
        }

        .detail-item h4 {
            font-size: 1rem;
            font-weight: 600;
            color: var(--color-text-primary);
            margin-bottom: 0.75rem;
        }

        .detail-item ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .detail-item ul li {
            padding: 0.5rem 0;
            padding-left: 1.5rem;
            position: relative;
            color: var(--color-text-secondary);
            font-size: 0.875rem;
            line-height: 1.6;
        }

        .detail-item ul li:before {
            content: "•";
            position: absolute;
            left: 0.5rem;
            color: var(--color-accent);
            font-weight: bold;
        }

        .detail-item code {
            background: var(--color-bg-tertiary);
            padding: 0.125rem 0.375rem;
            border-radius: 4px;
            font-family: 'Monaco', 'Menlo', monospace;
            font-size: 0.8125rem;
        }

        .theme-loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 10000;
        }

        .theme-loading-overlay.active {
            display: flex;
        }

        .theme-loading-content {
            background: var(--color-bg-secondary);
            border: 1px solid var(--color-border);
            border-radius: 12px;
            padding: 2rem;
            text-align: center;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
        }

        .theme-loading-spinner {
            width: 40px;
            height: 40px;
            border: 4px solid var(--color-border);
            border-top-color: var(--color-accent);
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
            margin: 0 auto 1rem;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        @media (max-width: 768px) {
            .theme-options {
                grid-template-columns: 1fr;
            }
        }
    </style>

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
</x-admin.layouts.app>
