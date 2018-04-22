<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LockThreadsTest extends TestCase
{
	use RefreshDatabase;

	/** @test */
	public function non_administrators_may_not_lock_threads()
	{
		$this->withExceptionHandling();
		$this->signIn();

		$thread = create('App\Thread', ['user_id' => auth()->id()]);

		$this->post(route('locked-threads.store', $thread))->assertStatus(403);

		$this->assertFalse(!! $thread->fresh()->locked);
	} 

	/** @test */
	public function administrators_may_lock_threads()
	{
		$this->withExceptionHandling();
		$this->signIn(factory('App\User')->states('administrator')->create());

		$thread = create('App\Thread', ['user_id' => auth()->id()]);

		$this->post(route('locked-threads.store', $thread))->assertStatus(200);

		$this->assertTrue(!! $thread->fresh()->locked);

	} 

   /** @test */
   public function once_locked_thread_may_not_receive_replies()
   {
   		$this->signIn();
   		$thread = create('App\Thread');

   		$thread->update(['locked' => true]);

   		$this->post($thread->path() . '/replies', [
   			'body' => 'Foobar',
   			'user_id' => create('App\User')->id
   		])->assertStatus(422);
   } 
}
