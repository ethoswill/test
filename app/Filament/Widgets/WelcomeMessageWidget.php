<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Support\Carbon;

class WelcomeMessageWidget extends Widget
{
    protected static bool $isDiscovered = false;

    protected static string $view = 'filament.widgets.welcome-message-widget';

    protected int | string | array $columnSpan = 'full';

    public function getWelcomeMessage(): string
    {
        $user = auth()->user();
        $firstName = $user->first_name ?? $user->name;
        $hour = now()->hour;
        $dayOfWeek = now()->dayOfWeek;
        
        // Time-based greeting
        $timeGreeting = match(true) {
            $hour >= 5 && $hour < 12 => 'Good morning',
            $hour >= 12 && $hour < 17 => 'Good afternoon',
            $hour >= 17 && $hour < 22 => 'Good evening',
            default => 'Hello',
        };
        
        // Day-based messages
        $dailyMessages = [
            0 => "Hope you're having a great Sunday, {$firstName}! Let's make this week productive.",
            1 => "Happy Monday, {$firstName}! Ready to tackle this week?",
            2 => "Hello {$firstName}! Tuesday vibes - you've got this!",
            3 => "Welcome back {$firstName}! Halfway through the week already!",
            4 => "Good to see you {$firstName}! Thursday is looking good.",
            5 => "Happy Friday, {$firstName}! Almost there!",
            6 => "Saturday greetings, {$firstName}! Hope your weekend is off to a great start.",
        ];
        
        $message = $dailyMessages[$dayOfWeek] ?? "Welcome {$firstName}! Let's have a productive day.";
        
        return "{$timeGreeting}! {$message}";
    }

    public function getWeatherMood(): string
    {
        $dayOfWeek = now()->dayOfWeek;
        
        $moods = [
            0 => '☀️',
            1 => '💼',
            2 => '🚀',
            3 => '⭐',
            4 => '🎯',
            5 => '🎉',
            6 => '🏖️',
        ];
        
        return $moods[$dayOfWeek] ?? '👋';
    }

    public function getMotivationalTip(): string
    {
        $tips = [
            "Remember: Today's progress is tomorrow's success!",
            "Small steps lead to big achievements. Keep going!",
            "Your dedication is what makes the difference.",
            "Every task completed brings you closer to your goals.",
            "Stay focused and you'll accomplish amazing things!",
            "Believe in yourself and your capabilities.",
            "You're doing great - keep up the excellent work!",
        ];
        
        return $tips[now()->dayOfWeek % count($tips)];
    }

    public function getFormattedDate(): string
    {
        $now = now();
        return $now->format('l F Y');
    }
}

