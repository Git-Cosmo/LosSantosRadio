@extends('layouts.app')

@section('title', 'Analytics Dashboard')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-4xl font-bold mb-8">Analytics Dashboard</h1>
    
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
            <h3 class="text-lg font-semibold mb-2">Online Now</h3>
            <p class="text-3xl font-bold text-blue-600">{{ $onlineCount }}</p>
        </div>
        
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
            <h3 class="text-lg font-semibold mb-2">Total Sessions</h3>
            <p class="text-3xl font-bold text-green-600">{{ $statistics['total_sessions'] }}</p>
        </div>
        
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
            <h3 class="text-lg font-semibold mb-2">Registered Users</h3>
            <p class="text-3xl font-bold text-purple-600">{{ $statistics['total_users'] }}</p>
        </div>
        
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
            <h3 class="text-lg font-semibold mb-2">Guest Visitors</h3>
            <p class="text-3xl font-bold text-orange-600">{{ $statistics['total_guests'] }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
            <h3 class="text-xl font-bold mb-4">By Device</h3>
            @foreach($statistics['by_device'] as $device => $count)
            <div class="mb-2">
                <div class="flex justify-between mb-1">
                    <span>{{ ucfirst($device) }}</span>
                    <span class="font-bold">{{ $count }}</span>
                </div>
            </div>
            @endforeach
        </div>
        
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
            <h3 class="text-xl font-bold mb-4">Top Browsers</h3>
            @foreach($statistics['top_browsers'] as $browser => $count)
            <div class="mb-2">
                <div class="flex justify-between mb-1">
                    <span>{{ $browser }}</span>
                    <span class="font-bold">{{ $count }}</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
