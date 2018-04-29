<?php

namespace Tests\Feature;

use App\Activity;
use App\Channel;
use App\Reply;
use App\Rules\Recaptcha;
use App\Thread;
use App\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateThreadsTest extends TestCase
{
	use DatabaseMigrations;

   public function setUp() 
   {
      parent::setup();

      app()->singleton(Recaptcha::class, function() {
         return \Mockery::mock(Recaptcha::class, function($mockery) {
               $mockery->shouldReceive('passes')->andReturnTrue();
         });
      });
   }

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
   	  $response = $this->post(route('threads'), $thread->toArray() + ['g-recaptcha-response' => 'token']);

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
   public function a_thread_requires_a_unique_slug()
   {
      $this->signIn();
      $thread = create('App\Thread', ['title' => 'Foo Title']);

      $this->assertEquals($thread->fresh()->slug, 'foo-title');

      $thread = $this->postJson(route('threads'), $thread->toArray() + ['g-recaptcha-response' => 'token'])->json();

      $this->assertEquals("foo-title-{$thread['id']}", $thread['slug']);
   } 

   /** @test */
   public function a_thread_requiers_recaptcha_verification()
   {
      unset(app()[Recaptcha::class]);

      $this->publishThread(['g-recaptcha-response' => 'test'])
         ->assertSessionHasErrors('g-recaptcha-response');
   } 

   /** @test */
   public function a_thread_with_a_title_that_ends_in_a_number_should_generate_the_proper_slug() 
   {
      $this->signIn();
      $thread = create('App\Thread', ['title' => 'Some Title 24']);
      $thread = $this->postJson(route('threads'), $thread->toArray() + ['g-recaptcha-response' => 'token'])->json();
      $this->assertEquals("some-title-24-{$thread['id']}", $thread['slug']);
   }

   /** @test */

   public function a_thread_requires_a_title_and_body_to_be_updated() 
   {
      $this->withExceptionHandling();
      $this->signIn();
      $thread = create('App\Thread', ['user_id' => auth()->id()]);

      $response = $this->patch($thread->path(), [
         'title' => 'Changed',
      ])->assertSessionHasErrors('body');

      $this->patch($thread->path(), [
         'body' => 'Changed',
      ])->assertSessionHasErrors('title');

   }

   /** @test */
   public function unauthorized_users_may_not_update_threads()
   {

      $this->withExceptionHandling();

      $this->signIn();
      $thread = create('App\Thread', ['user_id' => create('App\User')->id]);

      $this->patchJson($thread->path(), [
         'title' => 'Changed',
         'body' => 'Changed body'
      ])->assertStatus(403);
   } 

   /** @test */
   public function a_thread_can_be_updated_by_its_creator()
   {
      $this->signIn();
      $thread = create('App\Thread', ['user_id' => auth()->id()]);

      $this->patchJson($thread->path(), [
         'title' => 'Changed',
         'body' => 'Changed body'
      ]);

      tap($thread->fresh(), function($thread){
         $this->assertEquals('Changed', $thread->title);
         $this->assertEquals('Changed body', $thread->body);
      });

      
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
