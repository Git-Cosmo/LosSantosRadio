<!DOCTYPE html>
<html lang="en" x-data="{ darkMode: localStorage.getItem('theme') !== 'light' }" x-init="$watch('darkMode', val => localStorage.setItem('theme', val ? 'dark' : 'light'))" :class="{ 'dark': darkMode }" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>503 - Service Unavailable | Los Santos Radio</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script></head>
<body>
    <div class="background-elements">
        <div class="gear" style="top: 10%; left: 10%;"><i class="fas fa-cog" aria-hidden="true"></i></div>
        <div class="gear" style="top: 60%; left: 5%; animation-direction: reverse;"><i class="fas fa-cog" aria-hidden="true"></i></div>
        <div class="gear" style="top: 20%; right: 15%;"><i class="fas fa-cog" aria-hidden="true"></i></div>
        <div class="gear" style="bottom: 15%; right: 10%; animation-direction: reverse;"><i class="fas fa-cog" aria-hidden="true"></i></div>
    </div>
    
    <div class="error-container">
        <div class="maintenance-badge">
            <i class="fas fa-tools"></i>
            Maintenance in Progress
        </div>
        <div class="error-icon">
            <i class="fas fa-wrench" aria-hidden="true"></i>
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
