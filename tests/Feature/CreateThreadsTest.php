<?php

namespace Tests\Feature;

use App\Channel;
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
   		$this->withExceptionHandling()
               ->post('/threads')
               ->assertRedirect('/login');

         $this->withExceptionHandling()
            ->get('/threads/create')
            ->assertRedirect('/register');
   }
   
   /** @test */
   function an_authenticated_user_can_create_new_forum_threads()
   {
   	  $this->signIn();
   	  
        $thread = make(Thread::class);
   	  $response = $this->post('/threads', $thread->toArray());

   	  $this->get($response->headers->get('Location'))
   	        ->assertSee($thread->title)
   	  		  ->assertSee($thread->body);
   } 

   /** @test */
   function a_thread_requires_a_title()
   {
      $this->publishThread(['title' => null])
            ->assertSessionHasErrors('title');   
   }

   /** @test */
   function a_thread_requires_a_body()
   {
      $this->publishThread(['body' => null])
         ->assertSessionHasErrors('body');

   }

   /** @test */
   function a_thread_requires_a_channel()
   {
      factory(Channel::class, 2)->create();

      $this->publishThread(['channel_id' => null])
            ->assertSessionHasErrors('channel_id');

      $this->publishThread(['channel_id' => 999])
            ->assertSessionHasErrors('channel_id');   
   }

   function publishThread($overrides = [] )
   {
      $this->withExceptionHandling()->signIn();
      
      $thread = make(Thread::class, $overrides);

      return $this->post('/threads', $thread->toArray());

   }
}
