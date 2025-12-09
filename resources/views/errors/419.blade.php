<!DOCTYPE html>
<html lang="en" x-data="{ darkMode: localStorage.getItem('theme') !== 'light' }" x-init="$watch('darkMode', val => localStorage.setItem('theme', val ? 'dark' : 'light'))" :class="{ 'dark': darkMode }" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>419 - Session Expired | Los Santos Radio</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script></head>
<body>
    <div class="background-elements">
        <div class="clock-element sand-timer" style="top: 20%; left: 15%;"><i class="fas fa-hourglass-half" aria-hidden="true"></i></div>
        <div class="clock-element" style="top: 30%; right: 20%;"><i class="fas fa-clock" aria-hidden="true"></i></div>
        <div class="clock-element sand-timer" style="bottom: 25%; left: 20%; animation-delay: 1.5s;"><i class="fas fa-hourglass-end" aria-hidden="true"></i></div>
        <div class="clock-element" style="bottom: 15%; right: 25%;"><i class="fas fa-stopwatch" aria-hidden="true"></i></div>
    </div>
    
    <div class="error-container">
        <div class="timer-display">
            <i class="fas fa-clock"></i>
            Session timed out
        </div>
        <div class="error-icon">
            <i class="fas fa-hourglass-end" aria-hidden="true"></i>
        </div>
        <div class="error-code">419</div>
        <h1 class="error-title">Your Session Hit a Break!</h1>
        <p class="error-message">
            Looks like the DJ took an extended intermission! Your session has expired for security reasons. 
            Just like a good song, all good things must pause. Let's get you back on track!
        </p>
        <div class="error-actions">
            <a href="{{ url('/') }}" class="btn btn-primary">
                <i class="fas fa-home"></i> Back to Homepage
            </a>
            <button onclick="location.reload()" class="btn btn-secondary">
                <i class="fas fa-redo"></i> Refresh Page
            </button>
        </div>
        <p class="fun-text">⏰ Error 419: Commercial Break Timeout ⏰</p>
    </div>
</body>
</html>
