<!DOCTYPE html>
<html lang="en" x-data="{ darkMode: localStorage.getItem('theme') !== 'light' }" x-init="$watch('darkMode', val => localStorage.setItem('theme', val ? 'dark' : 'light'))" :class="{ 'dark': darkMode }" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>404 - Page Not Found | Los Santos Radio</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script></head>
<body>
    <div class="background-elements">
        <div class="floating-note" style="left: 10%; animation-delay: 0s;"><i class="fas fa-music" aria-hidden="true"></i></div>
        <div class="floating-note" style="left: 25%; animation-delay: 2s;"><i class="fas fa-headphones" aria-hidden="true"></i></div>
        <div class="floating-note" style="left: 40%; animation-delay: 4s;"><i class="fas fa-radio" aria-hidden="true"></i></div>
        <div class="floating-note" style="left: 55%; animation-delay: 6s;"><i class="fas fa-music" aria-hidden="true"></i></div>
        <div class="floating-note" style="left: 70%; animation-delay: 8s;"><i class="fas fa-microphone" aria-hidden="true"></i></div>
        <div class="floating-note" style="left: 85%; animation-delay: 10s;"><i class="fas fa-volume-up" aria-hidden="true"></i></div>
        <div class="radio-waves" style="top: 50%; left: 50%; transform: translate(-50%, -50%);"></div>
        <div class="radio-waves" style="top: 50%; left: 50%; transform: translate(-50%, -50%); animation-delay: 1s;"></div>
        <div class="radio-waves" style="top: 50%; left: 50%; transform: translate(-50%, -50%); animation-delay: 2s;"></div>
    </div>
    
    <div class="error-container">
        <div class="error-icon">
            <i class="fas fa-compact-disc fa-spin" aria-hidden="true"></i>
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
            <a href="{{ route('requests.index') }}" class="btn btn-secondary">
                <i class="fas fa-music"></i> Request a Song
            </a>
        </div>
        <p class="fun-text">🎵 Error 404: Beat Not Found 🎵</p>
    </div>
</body>
</html>
