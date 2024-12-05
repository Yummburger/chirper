<?php

namespace App\Listeners;

use App\Events\ChirpCreated;
use App\Models\User;
use App\Notifications\NewChirp;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class SendChirpCreatedNotifications implements ShouldQueue
{
    public function handle(ChirpCreated $event): void
    {
        // Log the event details
        Log::info('Chirp Created Event Received', [
            'chirp_id' => $event->chirp->id,
            'user_id' => $event->chirp->user_id
        ]);

        // Count users who will receive notification
        $userCount = User::whereNot('id', $event->chirp->user_id)->count();
        Log::info("Preparing to send notifications to {$userCount} users");

        foreach (User::whereNot('id', $event->chirp->user_id)->cursor() as $user) {
            try {
                Log::info("Sending notification to user", [
                    'user_id' => $user->id,
                    'user_email' => $user->email
                ]);

                $user->notify(new NewChirp($event->chirp));
            } catch (\Exception $e) {
                Log::error('Notification sending failed', [
                    'user_id' => $user->id,
                    'error' => $e->getMessage()
                ]);
            }
        }
    }
}
