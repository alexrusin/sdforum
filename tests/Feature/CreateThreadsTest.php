<?php

namespace Tests\Feature;

use App\Thread;
use App\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateThreadsTest extends TestCase
{
	use RefreshDatabase;

   /** @test */

   function unauthenticated_user_cannot_create_threads()
   {
   		$this->expectException(AuthenticationException::class);

   		$thread = make(Thread::class);
   	  
   	  	$this->post('/threads', $thread->toArray());
   }
   
   /** @test */
   function an_authenticated_user_can_create_new_forum_threads()
   {
   	 
   	  $this->signIn();
   	  
   	  $thread = make(Thread::class);
   	  
   	  $this->post('/threads', $thread->toArray());

   	  
   	  $this->get($thread->path())->assertSee($thread->title)
   	  		->assertSee($thread->body);
   } 

   /** @test */
   function guests_cannot_see_create_thread_page()
   {
      $this->withExceptionHandling()
            ->get('/threads/create')
            ->assertRedirect('/login');
   }
}
