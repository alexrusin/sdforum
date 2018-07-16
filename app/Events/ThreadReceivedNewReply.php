<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class ThreadReceivedNewReply
{
    public $reply;
    public $thread;

    use Dispatchable, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($reply, $thread)
    {
        //
        $this->reply = $reply;
        $this->thread = $thread;
    }
}
