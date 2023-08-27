<?php

namespace App\Jobs;

use App\Models\User;
use App\Notifications\UserActivationLink as NotificationsUserActivationLink;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

class UserActivationLink implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private User $user;
    /**
     * Create a new job instance.
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $url = URL::temporarySignedRoute(
            'user.activate',
            now()->addDays(1),
            ['hash' => $this->user->remember_token]
        );

        $this->user->notify(new NotificationsUserActivationLink($url, $this->user));
    }
}
