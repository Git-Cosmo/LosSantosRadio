<!DOCTYPE html>
<html lang="en" x-data="{ darkMode: localStorage.getItem('theme') !== 'light' }" x-init="$watch('darkMode', val => localStorage.setItem('theme', val ? 'dark' : 'light'))" :class="{ 'dark': darkMode }" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>403 - Access Denied | Los Santos Radio</title>
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
            --color-accent: #9a6700;
        }
        html.dark {
            --color-bg-primary: #0d1117;
            --color-bg-secondary: #161b22;
            --color-bg-tertiary: #21262d;
            --color-border: #30363d;
            --color-text-primary: #e6edf3;
            --color-text-secondary: #8b949e;
            --color-accent: #d29922;
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
            background: linear-gradient(135deg, var(--color-accent), #fbbf24, #f59e0b);
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
            animation: bounce 2s ease-in-out infinite;
        }
        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
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
        .lock-icon {
            position: absolute;
            font-size: 3rem;
            opacity: 0.05;
            color: var(--color-accent);
            animation: floatAround 20s ease-in-out infinite;
        }
        @keyframes floatAround {
            0%, 100% { transform: translate(0, 0) rotate(0deg); }
            25% { transform: translate(20px, -20px) rotate(10deg); }
            50% { transform: translate(-10px, -40px) rotate(-5deg); }
            75% { transform: translate(-30px, -10px) rotate(5deg); }
        }
        .fun-text {
            font-size: 0.875rem;
            color: var(--color-text-secondary);
            margin-top: 2rem;
            font-style: italic;
        }
        .vip-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background: linear-gradient(135deg, var(--color-accent), #fbbf24);
            color: white;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <div class="background-elements">
        <div class="lock-icon" style="top: 15%; left: 15%;"><i class="fas fa-lock" aria-hidden="true"></i></div>
        <div class="lock-icon" style="top: 25%; right: 20%; animation-delay: 2s;"><i class="fas fa-shield-alt" aria-hidden="true"></i></div>
        <div class="lock-icon" style="bottom: 20%; left: 25%; animation-delay: 4s;"><i class="fas fa-user-lock" aria-hidden="true"></i></div>
        <div class="lock-icon" style="bottom: 30%; right: 15%; animation-delay: 6s;"><i class="fas fa-ban" aria-hidden="true"></i></div>
    </div>
    
    <div class="error-container">
        <div class="vip-badge">
            <i class="fas fa-star"></i> VIP Area
        </div>
        <div class="error-icon">
            <i class="fas fa-door-closed" aria-hidden="true"></i>
        </div>
        <div class="error-code">403</div>
        <h1 class="error-title">Backstage Pass Required!</h1>
        <p class="error-message">
            Whoa there! This area is reserved for the crew. You'll need special access to enter the DJ booth. 
            If you think you should be here, try logging in or contact our station manager.
        </p>
        <div class="error-actions">
            <a href="{{ url('/') }}" class="btn btn-primary">
                <i class="fas fa-home"></i> Back to Homepage
            </a>
            <a href="{{ route('login') }}" class="btn btn-secondary">
                <i class="fas fa-sign-in-alt"></i> Sign In
            </a>
        </div>
        <p class="fun-text">🎫 Error 403: No Backstage Pass Detected 🎫</p>
    </div>
</body>
</html>
