<?php

namespace Tests\Feature;

use App\Channel;
use App\Reply;
use App\Thread;
use App\User;
use GuzzleHttp\Client;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReadThreadsTest extends TestCase
{
	use DatabaseMigrations;

	public function setUp()
	{
		parent::setUp();
		$this->thread = factory('App\Thread')->create();

	}
   
   /** @test */
    public function a_user_can_browse_threads()
    {
    	
    	$response = $this->get($this->thread->path());
        $response->assertSee($this->thread->title);

       

    }

    /** @test */

    public function a_user_can_see_a_single_thread()
	{
		
		$response = $this->get($this->thread->path());
        $response->assertSee($this->thread->title);
	}

	/** @test */
	public function a_user_can_read_replies_that_are_associated_with_a_thread()
	{
		$reply = factory(Reply::class)->create(['thread_id' => $this->thread->id]);
		$this->get($this->thread->path())
			->assertSee($reply->body);
	}

	/** @test */
	public function a_user_can_filter_threads_according_to_a_channel() 
	{
		$channel = create(Channel::class);
		$threadInChannel = create(Thread::class, ['channel_id' => $channel->id]);
		$threadNotInChannel = create(Thread::class);

		$this->get('/threads/' .$channel->slug)
			 ->assertSee($threadInChannel->title)
			 ->assertDontSee($threadNotInChannel->title);

	}

	/** @test */
	public function a_user_can_filter_threads_by_any_username() 
	{
		$this->signIn(create(User::class, ['name' => 'JohnDoe']));

		$threadByJohn = create(Thread::class, ['user_id' => auth()->id()]);
		$threadNotByJohn = create(Thread::class);

		$this->get('threads?by=JohnDoe')
			->assertSee($threadByJohn->title)
			->assertDontSee($threadNotByJohn->title);
	}

}	

