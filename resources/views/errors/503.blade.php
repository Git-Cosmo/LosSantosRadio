<!DOCTYPE html>
<html lang="en" x-data="{ darkMode: localStorage.getItem('theme') !== 'light' }" x-init="$watch('darkMode', val => localStorage.setItem('theme', val ? 'dark' : 'light'))" :class="{ 'dark': darkMode }" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>503 - Service Unavailable | Los Santos Radio</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        :root {
            --color-bg-primary: #ffffff;
            --color-bg-secondary: #f6f8fa;
            --color-bg-tertiary: #eaeef2;
            --color-border: #d0d7de;
            --color-text-primary: #1f2328;
            --color-text-secondary: #656d76;
            --color-accent: #1a7f37;
        }
        html.dark {
            --color-bg-primary: #0d1117;
            --color-bg-secondary: #161b22;
            --color-bg-tertiary: #21262d;
            --color-border: #30363d;
            --color-text-primary: #e6edf3;
            --color-text-secondary: #8b949e;
            --color-accent: #3fb950;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: var(--color-bg-primary);
            color: var(--color-text-primary);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }
        .error-container {
            text-align: center;
            padding: 2rem;
            position: relative;
            z-index: 1;
        }
        .error-code {
            font-size: 10rem;
            font-weight: 800;
            background: linear-gradient(135deg, var(--color-accent), #4ade80, #22c55e);
            background-size: 200% 200%;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: gradientShift 4s ease infinite;
            line-height: 1;
        }
        @keyframes gradientShift {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }
        .error-icon {
            font-size: 4rem;
            color: var(--color-accent);
            margin-bottom: 1rem;
            animation: wrench 2s ease-in-out infinite;
        }
        @keyframes wrench {
            0%, 100% { transform: rotate(0deg); }
            25% { transform: rotate(-30deg); }
            75% { transform: rotate(30deg); }
        }
        .error-title {
            font-size: 2rem;
            font-weight: 700;
            margin: 1rem 0;
            color: var(--color-text-primary);
        }
        .error-message {
            font-size: 1.125rem;
            color: var(--color-text-secondary);
            max-width: 500px;
            margin: 0 auto 2rem;
            line-height: 1.6;
        }
        .error-actions {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            font-size: 1rem;
            font-weight: 500;
            border-radius: 8px;
            text-decoration: none;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }
        .btn-primary {
            background: linear-gradient(135deg, #58a6ff, #a855f7);
            color: white;
            box-shadow: 0 4px 15px rgba(88, 166, 255, 0.3);
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 25px rgba(88, 166, 255, 0.4);
        }
        .btn-secondary {
            background: var(--color-bg-tertiary);
            color: var(--color-text-primary);
            border: 1px solid var(--color-border);
        }
        .btn-secondary:hover {
            background: var(--color-bg-secondary);
        }
        .background-elements {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            overflow: hidden;
        }
        .gear {
            position: absolute;
            font-size: 6rem;
            opacity: 0.05;
            color: var(--color-accent);
            animation: rotate 10s linear infinite;
        }
        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        .fun-text {
            font-size: 0.875rem;
            color: var(--color-text-secondary);
            margin-top: 2rem;
            font-style: italic;
        }
        .maintenance-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background: linear-gradient(135deg, var(--color-accent), #4ade80);
            color: white;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 600;
            margin-bottom: 1rem;
            animation: pulse 2s ease-in-out infinite;
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }
        .progress-container {
            margin-top: 1.5rem;
            text-align: center;
        }
        .progress-dots {
            display: flex;
            justify-content: center;
            gap: 0.5rem;
        }
        .progress-dot {
            width: 12px;
            height: 12px;
            background: var(--color-accent);
            border-radius: 50%;
            animation: dotPulse 1.5s ease-in-out infinite;
        }
        .progress-dot:nth-child(2) { animation-delay: 0.2s; }
        .progress-dot:nth-child(3) { animation-delay: 0.4s; }
        @keyframes dotPulse {
            0%, 100% { transform: scale(1); opacity: 0.5; }
            50% { transform: scale(1.3); opacity: 1; }
        }
        .progress-text {
            display: block;
            margin-top: 0.75rem;
            font-size: 0.875rem;
            color: var(--color-text-secondary);
        }
    </style>
</head>
<body>
    <div class="background-elements">
        <div class="gear" style="top: 10%; left: 10%;"><i class="fas fa-cog"></i></div>
        <div class="gear" style="top: 60%; left: 5%; animation-direction: reverse;"><i class="fas fa-cog"></i></div>
        <div class="gear" style="top: 20%; right: 15%;"><i class="fas fa-cog"></i></div>
        <div class="gear" style="bottom: 15%; right: 10%; animation-direction: reverse;"><i class="fas fa-cog"></i></div>
    </div>
    
    <div class="error-container">
        <div class="maintenance-badge">
            <i class="fas fa-tools"></i>
            Maintenance in Progress
        </div>
        <div class="error-icon">
            <i class="fas fa-wrench"></i>
        </div>
        <div class="error-code">503</div>
        <h1 class="error-title">Station Under Maintenance!</h1>
        <p class="error-message">
            Our engineers are tuning up the broadcast equipment! Like changing records between sets, 
            we're making some upgrades to give you an even better listening experience. We'll be back on air shortly!
        </p>
        <div class="error-actions">
            <button onclick="location.reload()" class="btn btn-primary">
                <i class="fas fa-redo"></i> Check Again
            </button>
        </div>
        <div class="progress-container">
            <div class="progress-dots">
                <div class="progress-dot"></div>
                <div class="progress-dot"></div>
                <div class="progress-dot"></div>
            </div>
            <span class="progress-text">Working on getting back online...</span>
        </div>
        <p class="fun-text">🔧 Error 503: Station Upgrade in Progress 🔧</p>
    </div>
</body>
</html>
