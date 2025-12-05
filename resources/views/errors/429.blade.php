<!DOCTYPE html>
<html lang="en" x-data="{ darkMode: localStorage.getItem('theme') !== 'light' }" x-init="$watch('darkMode', val => localStorage.setItem('theme', val ? 'dark' : 'light'))" :class="{ 'dark': darkMode }" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>429 - Too Many Requests | Los Santos Radio</title>
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
            --color-accent: #0969da;
        }
        html.dark {
            --color-bg-primary: #0d1117;
            --color-bg-secondary: #161b22;
            --color-bg-tertiary: #21262d;
            --color-border: #30363d;
            --color-text-primary: #e6edf3;
            --color-text-secondary: #8b949e;
            --color-accent: #58a6ff;
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
            background: linear-gradient(135deg, var(--color-accent), #38bdf8, #22d3d1);
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
            animation: speedometer 0.5s ease-in-out infinite;
        }
        @keyframes speedometer {
            0%, 100% { transform: rotate(-20deg); }
            50% { transform: rotate(20deg); }
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
        .speed-lines {
            position: absolute;
            width: 100px;
            height: 2px;
            background: linear-gradient(90deg, transparent, var(--color-accent), transparent);
            animation: dash 1s ease-in-out infinite;
            opacity: 0.3;
        }
        @keyframes dash {
            0% { transform: translateX(-100%); opacity: 0; }
            50% { opacity: 0.3; }
            100% { transform: translateX(100vw); opacity: 0; }
        }
        .fun-text {
            font-size: 0.875rem;
            color: var(--color-text-secondary);
            margin-top: 2rem;
            font-style: italic;
        }
        .cooldown-bar {
            margin-top: 1.5rem;
            width: 300px;
            max-width: 100%;
            height: 6px;
            background: var(--color-bg-tertiary);
            border-radius: 3px;
            overflow: hidden;
            display: inline-block;
        }
        .cooldown-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--color-accent), #22d3d1);
            border-radius: 3px;
            animation: cooldown 30s linear forwards;
            width: 0%;
        }
        @keyframes cooldown {
            from { width: 0%; }
            to { width: 100%; }
        }
        .cooldown-text {
            display: block;
            margin-top: 0.5rem;
            font-size: 0.75rem;
            color: var(--color-text-muted);
        }
    </style>
</head>
<body>
    <div class="background-elements">
        <div class="speed-lines" style="top: 20%; animation-delay: 0s;"></div>
        <div class="speed-lines" style="top: 35%; animation-delay: 0.2s;"></div>
        <div class="speed-lines" style="top: 50%; animation-delay: 0.4s;"></div>
        <div class="speed-lines" style="top: 65%; animation-delay: 0.6s;"></div>
        <div class="speed-lines" style="top: 80%; animation-delay: 0.8s;"></div>
    </div>
    
    <div class="error-container">
        <div class="error-icon">
            <i class="fas fa-tachometer-alt"></i>
        </div>
        <div class="error-code">429</div>
        <h1 class="error-title">Slow Down, Speedy!</h1>
        <p class="error-message">
            You're requesting tracks faster than a DJ can spin! Our servers need a moment to catch their breath. 
            Take a quick break, grab a drink, and come back in a few seconds.
        </p>
        <div class="error-actions">
            <a href="{{ url('/') }}" class="btn btn-primary">
                <i class="fas fa-home"></i> Back to Homepage
            </a>
            <button onclick="location.reload()" class="btn btn-secondary">
                <i class="fas fa-redo"></i> Try Again
            </button>
        </div>
        <div class="cooldown-bar">
            <div class="cooldown-fill"></div>
        </div>
        <span class="cooldown-text">Please wait before trying again...</span>
        <p class="fun-text">🏎️ Error 429: BPM Too High, Please Cool Down 🏎️</p>
    </div>
</body>
</html>
