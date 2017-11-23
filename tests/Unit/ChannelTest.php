<?php

namespace Tests\Unit;

use App\Channel;
use App\Thread;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChannelTest extends TestCase
{
	use DatabaseMigrations;
    /** @test */
    public function a_channel_consists_of_threads() 
    {
    	$channel = create(Channel::class);
    	$thread = create(Thread::class, ['channel_id' => $channel->id]);

    	$this->assertTrue($channel->threads->contains($thread));	
    }
}
