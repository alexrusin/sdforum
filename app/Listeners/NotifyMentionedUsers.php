<?php

namespace App\Listeners;

use App\Events\ThreadReceivedNewReply;
use App\Notifications\YouWereMentioned;
use App\User;

class NotifyMentionedUsers
{
    /**
     * Handle the event.
     *
     * @param  ThreadReceivedNewReply  $event
     * @return void
     */
    public function handle(ThreadReceivedNewReply $event)
    {
        preg_match_all('/@([\w\-]+)/', $event->reply->body, $matches);

        User::whereIn('name', $matches[1])
            ->get()
            ->each(function ($user) use ($event) {
                $user->notify(new YouWereMentioned($event->reply));
            });
    }
}
