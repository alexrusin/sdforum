<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

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
