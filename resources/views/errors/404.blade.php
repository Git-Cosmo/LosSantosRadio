<!DOCTYPE html>
<html lang="en" x-data="{ darkMode: localStorage.getItem('theme') !== 'light' }" x-init="$watch('darkMode', val => localStorage.setItem('theme', val ? 'dark' : 'light'))" :class="{ 'dark': darkMode }" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>404 - Page Not Found | Los Santos Radio</title>
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
            background: linear-gradient(135deg, var(--color-accent), #a855f7, #ec4899);
            background-size: 200% 200%;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: gradientShift 4s ease infinite;
            line-height: 1;
            text-shadow: 0 0 60px rgba(88, 166, 255, 0.3);
        }
        @keyframes gradientShift {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }
        .error-icon {
            font-size: 4rem;
            color: var(--color-accent);
            margin-bottom: 1rem;
            animation: float 3s ease-in-out infinite;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-15px); }
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
            background: linear-gradient(135deg, var(--color-accent), #a855f7);
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
            border-color: var(--color-accent);
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
        .floating-note {
            position: absolute;
            font-size: 2rem;
            opacity: 0.1;
            animation: floatUp 15s linear infinite;
            color: var(--color-accent);
        }
        @keyframes floatUp {
            0% { transform: translateY(100vh) rotate(0deg); opacity: 0; }
            10% { opacity: 0.1; }
            90% { opacity: 0.1; }
            100% { transform: translateY(-100px) rotate(360deg); opacity: 0; }
        }
        .radio-waves {
            position: absolute;
            width: 300px;
            height: 300px;
            border: 2px solid var(--color-accent);
            border-radius: 50%;
            opacity: 0;
            animation: radioWave 4s ease-out infinite;
        }
        @keyframes radioWave {
            0% { transform: scale(0.5); opacity: 0.3; }
            100% { transform: scale(2); opacity: 0; }
        }
        .fun-text {
            font-size: 0.875rem;
            color: var(--color-text-secondary);
            margin-top: 2rem;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="background-elements">
        <div class="floating-note" style="left: 10%; animation-delay: 0s;"><i class="fas fa-music"></i></div>
        <div class="floating-note" style="left: 25%; animation-delay: 2s;"><i class="fas fa-headphones"></i></div>
        <div class="floating-note" style="left: 40%; animation-delay: 4s;"><i class="fas fa-radio"></i></div>
        <div class="floating-note" style="left: 55%; animation-delay: 6s;"><i class="fas fa-music"></i></div>
        <div class="floating-note" style="left: 70%; animation-delay: 8s;"><i class="fas fa-microphone"></i></div>
        <div class="floating-note" style="left: 85%; animation-delay: 10s;"><i class="fas fa-volume-up"></i></div>
        <div class="radio-waves" style="top: 50%; left: 50%; transform: translate(-50%, -50%);"></div>
        <div class="radio-waves" style="top: 50%; left: 50%; transform: translate(-50%, -50%); animation-delay: 1s;"></div>
        <div class="radio-waves" style="top: 50%; left: 50%; transform: translate(-50%, -50%); animation-delay: 2s;"></div>
    </div>
    
    <div class="error-container">
        <div class="error-icon">
            <i class="fas fa-compact-disc fa-spin"></i>
        </div>
        <div class="error-code">404</div>
        <h1 class="error-title">This Track Doesn't Exist!</h1>
        <p class="error-message">
            Looks like this page got lost in the mix. The DJ must have scratched it off the playlist. 
            Don't worry though, we've got plenty more tunes to explore!
        </p>
        <div class="error-actions">
            <a href="{{ url('/') }}" class="btn btn-primary">
                <i class="fas fa-home"></i> Back to Homepage
            </a>
            <a href="{{ url('/requests') }}" class="btn btn-secondary">
                <i class="fas fa-music"></i> Request a Song
            </a>
        </div>
        <p class="fun-text">🎵 Error 404: Beat Not Found 🎵</p>
    </div>
</body>
</html>
