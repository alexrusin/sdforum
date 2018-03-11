<?php

namespace Tests\Feature;

use App\Activity;
use App\Channel;
use App\Reply;
use App\Thread;
use App\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateThreadsTest extends TestCase
{
	use DatabaseMigrations;

   /** @test */
   function unauthenticated_user_cannot_create_threads()
   {
   		$this->withExceptionHandling()
               ->post(route('threads'))
               ->assertRedirect('/login');

         $this->withExceptionHandling()
            ->get('/threads/create')
            ->assertRedirect('/login');
   }
   
   /** @test */
   function a_user_can_create_new_forum_threads()
   {
   	  $this->signIn();
   	  
        $thread = make(Thread::class);
   	  $response = $this->post(route('threads'), $thread->toArray());

   	  $this->get($response->headers->get('Location'))
   	        ->assertSee($thread->title)
   	  		  ->assertSee($thread->body);
   } 

   /** @test */
   public function authenticated_users_must_first_confirm_email_before_creating_threads()
   {
      $user = factory('App\User')->states('unconfirmed')->create();

      $this->signIn($user);

      $thread = make('App\Thread');

      $this->post('/threads', $thread->toArray())
            ->assertRedirect(route('threads'))
            ->assertSessionHas('flash');
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

   /** @test */
   public function unauthorized_users_may_not_delete_threads() 
   {  
      $this->withExceptionHandling();
      $thread = create(Thread::class);
      $this->delete($thread->path())->assertRedirect(route('login'));

      $this->signIn();
      $this->delete($thread->path())->assertStatus(403);


   }

   /** @test */
   public function authorised_users_can_delete_thread() 
   {
      $this->signIn();
      $thread = create(Thread::class, ['user_id' => auth()->id()]);
      $reply = create(Reply::class, ['thread_id' => $thread->id]);

      $response = $this->json('DELETE', $thread->path());

      $response->assertStatus(204);

      $this->assertDatabaseMissing('threads', ['id' => $thread->id]);
      $this->assertDatabaseMissing('replies', ['id' => $reply->id]);
      
      $this->assertEquals(0, Activity::count());
   }

  
   protected function publishThread($overrides = [] )
   {
      $this->withExceptionHandling()->signIn();
      
      $thread = make(Thread::class, $overrides);

      return $this->post(route('threads'), $thread->toArray());

   }
}
