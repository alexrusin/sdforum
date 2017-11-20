<?php

namespace Tests\Feature;

use App\Reply;
use App\Thread;
use App\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ParticipateInForumTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */

    function unauthenticated_users_may_not_add_replies()
    {
    	$this->withExceptionHandling()
            ->post('/threads/some-channel/1/replies', [])
            ->assertRedirect('/login');
    }

    /** @test */ 

    function an_authenticated_user_may_participate_in_forum_threads()
    {
    	$this->be($user = factory(User::class)->create());

    	$thread = factory(Thread::class)->create();

    	$reply = factory(Reply::class)->make();

    	$this->post($thread->path() .'/replies', $reply->toArray());

    	$this->get($thread->path())
    	  	 ->assertSee($reply->body);
    }

    /** @test */

    function a_reply_requires_a_body()
    {
        $this->withExceptionHandling()->signIn();
        $thread = create(Thread::class);
        $reply = make(Reply::class, ['body' => null]);

        $this->post($thread->path() . '/replies', $reply->toArray())
            ->assertSessionHasErrors('body');
    }
}
