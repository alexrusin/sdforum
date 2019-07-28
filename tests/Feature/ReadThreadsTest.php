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
    	$this->markTestSkipped('must be revisited. No Redis');
    	$response = $this->get($this->thread->path());
        $response->assertSee($this->thread->title);

       

    }

    /** @test */

    public function a_user_can_see_a_single_thread()
	{
		$this->markTestSkipped('must be revisited. No Redis');
		$response = $this->get($this->thread->path());
        $response->assertSee($this->thread->title);
	}

	/** @test */
	public function a_user_can_filter_threads_according_to_a_channel() 
	{
		$this->markTestSkipped('must be revisited. No Redis');
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
		$this->markTestSkipped('must be revisited. No Redis');
		$this->signIn(create(User::class, ['name' => 'JohnDoe']));

		$threadByJohn = create(Thread::class, ['user_id' => auth()->id()]);
		$threadNotByJohn = create(Thread::class);

		$this->get('/threads?by=JohnDoe')
			->assertSee($threadByJohn->title)
			->assertDontSee($threadNotByJohn->title);
	}

	 /** @test */
	 public function a_user_can_filter_threads_by_popularity() 
	 {
		$this->markTestSkipped('must be revisited. No Redis');
	 	$threadWithTwoReplies = create(Thread::class);
	 	create(Reply::class, ['thread_id' => $threadWithTwoReplies->id], 2);

	 	$threadWithThreeReplies = create(Thread::class);
	 	create(Reply::class, ['thread_id' => $threadWithThreeReplies->id], 3);

	 	$threadWithZeroReplies = $this->thread;
	 	

	 	$response = $this->getJson('/threads?popular=1')->json();

	 	$this->assertEquals([3,2,0], array_column($response['data'], 'replies_count'));
	 }

	 /** @test */
	 function a_user_can_filter_threads_by_those_that_are_unanswered()
	 {
	 	$thread = create(Thread::class);
	 	create(Reply::class, ['thread_id' => $thread->id]);

	 	$response = $this->getJson('/threads?unanswered=1')->json();
	 	$this->assertCount(1, $response['data']);
	 } 

	 /** @test */
	 function a_user_can_request_all_replies_for_a_given_thread()
	 {
	 	$thread = create(Thread::class);
	 	create(Reply::class, ['thread_id' => $thread->id], 21);

	 	$response = $this->getJson($thread->path() .'/replies')->json();

	 	$this->assertCount(20, $response['data']);
	 	$this->assertEquals(21, $response['total']);
	 } 

	 /** @test */
	 public function a_threads_body_is_sanitized_automatically()
	 {
	 	$thread = make('App\Thread', ['body' => '<script>alert("bad")</script><p>This is ok</p>']);

	 	$this->assertEquals('<p>This is ok</p>', $thread->body);
	 } 

}	

