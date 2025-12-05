<!DOCTYPE html>
<html lang="en" x-data="{ darkMode: localStorage.getItem('theme') !== 'light' }" x-init="$watch('darkMode', val => localStorage.setItem('theme', val ? 'dark' : 'light'))" :class="{ 'dark': darkMode }" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>500 - Server Error | Los Santos Radio</title>
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
            --color-accent: #cf222e;
        }
        html.dark {
            --color-bg-primary: #0d1117;
            --color-bg-secondary: #161b22;
            --color-bg-tertiary: #21262d;
            --color-border: #30363d;
            --color-text-primary: #e6edf3;
            --color-text-secondary: #8b949e;
            --color-accent: #f85149;
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
            background: linear-gradient(135deg, var(--color-accent), #ff6b6b, #ffa502);
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
            animation: shake 0.5s ease-in-out infinite;
        }
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px) rotate(-5deg); }
            75% { transform: translateX(5px) rotate(5deg); }
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
        .spark {
            position: absolute;
            width: 4px;
            height: 4px;
            background: var(--color-accent);
            border-radius: 50%;
            animation: sparkle 2s ease-in-out infinite;
        }
        @keyframes sparkle {
            0%, 100% { opacity: 0; transform: scale(0); }
            50% { opacity: 1; transform: scale(1); }
        }
        .broken-vinyl {
            position: absolute;
            font-size: 8rem;
            opacity: 0.05;
            color: var(--color-accent);
            animation: spin 20s linear infinite;
        }
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        .fun-text {
            font-size: 0.875rem;
            color: var(--color-text-secondary);
            margin-top: 2rem;
            font-style: italic;
        }
        .status-bar {
            margin-top: 1.5rem;
            padding: 1rem;
            background: var(--color-bg-tertiary);
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
        }
        .status-dot {
            width: 10px;
            height: 10px;
            background: var(--color-accent);
            border-radius: 50%;
            animation: pulse 1.5s ease-in-out infinite;
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.3; }
        }
        .status-text {
            font-size: 0.875rem;
            color: var(--color-text-secondary);
        }
    </style>
</head>
<body>
    <div class="background-elements">
        <div class="broken-vinyl" aria-hidden="true" style="top: 20%; left: 10%;"><i class="fas fa-compact-disc"></i></div>
        <div class="broken-vinyl" aria-hidden="true" style="top: 60%; right: 10%; animation-direction: reverse;"><i class="fas fa-compact-disc"></i></div>
        <div class="spark" aria-hidden="true" style="top: 30%; left: 20%; animation-delay: 0s;"></div>
        <div class="spark" aria-hidden="true" style="top: 50%; left: 70%; animation-delay: 0.3s;"></div>
        <div class="spark" aria-hidden="true" style="top: 70%; left: 30%; animation-delay: 0.6s;"></div>
        <div class="spark" aria-hidden="true" style="top: 40%; right: 25%; animation-delay: 0.9s;"></div>
        <div class="spark" aria-hidden="true" style="top: 80%; right: 40%; animation-delay: 1.2s;"></div>
    </div>
    
    <div class="error-container">
        <div class="error-icon">
            <i class="fas fa-exclamation-triangle" aria-hidden="true"></i>
        </div>
        <div class="error-code">500</div>
        <h1 class="error-title">Technical Difficulties!</h1>
        <p class="error-message">
            Our mixing board just hit a snag! The DJ booth is experiencing some unexpected interference. 
            Our engineers are on it like a bass drop at midnight. Please try again in a moment.
        </p>
        <div class="error-actions">
            <a href="{{ url('/') }}" class="btn btn-primary">
                <i class="fas fa-home"></i> Back to Homepage
            </a>
            <button onclick="location.reload()" class="btn btn-secondary">
                <i class="fas fa-redo"></i> Try Again
            </button>
        </div>
        <div class="status-bar">
            <div class="status-dot"></div>
            <span class="status-text">Our team has been notified</span>
        </div>
        <p class="fun-text">🔧 Error 500: The Beat Machine Needs Maintenance 🔧</p>
    </div>
</body>
</html>
