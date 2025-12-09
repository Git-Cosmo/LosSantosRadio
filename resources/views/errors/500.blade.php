<!DOCTYPE html>
<html lang="en" x-data="{ darkMode: localStorage.getItem('theme') !== 'light' }" x-init="$watch('darkMode', val => localStorage.setItem('theme', val ? 'dark' : 'light'))" :class="{ 'dark': darkMode }" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>500 - Server Error | Los Santos Radio</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script></head>
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
