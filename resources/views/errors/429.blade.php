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
    
</head>
<body>
    <div class="background-elements">
        <div class="speed-lines" style="top: 20%; animation-delay: 0s;" aria-hidden="true"></div>
        <div class="speed-lines" style="top: 35%; animation-delay: 0.2s;" aria-hidden="true"></div>
        <div class="speed-lines" style="top: 50%; animation-delay: 0.4s;" aria-hidden="true"></div>
        <div class="speed-lines" style="top: 65%; animation-delay: 0.6s;" aria-hidden="true"></div>
        <div class="speed-lines" style="top: 80%; animation-delay: 0.8s;" aria-hidden="true"></div>
    </div>
    
    <div class="error-container">
        <div class="error-icon">
            <i class="fas fa-tachometer-alt" aria-hidden="true"></i>
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
