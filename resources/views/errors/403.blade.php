<!DOCTYPE html>
<html lang="en" x-data="{ darkMode: localStorage.getItem('theme') !== 'light' }" x-init="$watch('darkMode', val => localStorage.setItem('theme', val ? 'dark' : 'light'))" :class="{ 'dark': darkMode }" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>403 - Access Denied | Los Santos Radio</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script></head>
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
