<?php

namespace Tests\Unit;

use App\Channel;
use App\Notifications\ThreadWasUpdated;
use App\Thread;
use App\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ThreadTest extends TestCase
{
	use DatabaseMigrations;
    
    protected $thread;

	public function setUp()
	{
		parent::setUp();
		$this->thread = factory(Thread::class)->create();
	}

    /** @test */
    function a_thread_has_replies()
    {
    	$this->thread = factory(Thread::class)->create();

    	$this->assertInstanceOf(Collection::class, $this->thread->replies);
    }

    /** @test */
    function a_thread_can_make_a_string_path()
    {
        $thread = create(Thread::class);

        $this->assertEquals('/threads/'. $thread->channel->slug .'/' . $thread->id, $thread->path());
    }

    /** @test */
    function a_thread_has_a_creator()
    {
    	$this->thread = factory(Thread::class)->create();

    	$this->assertInstanceOf(User::class, $this->thread->creator);
    }

    /** @test */

    function a_thread_can_add_a_reply()
    {
    	$this->thread->addReply([
    		'body' => 'Foobar',
    		'user_id' => 1
    	]);

    	$this->assertCount(1, $this->thread->replies);
    }

    /** @test */
    function a_thread_notifies_all_registred_subscribers_when_a_reply_is_added()
    {
        Notification::fake();
       
        $this->signIn()
            ->thread
            ->subscribe()
            ->addReply([
            'body' => 'Foobar',
            'user_id' => create('App\User')->id
        ]);
        Notification::assertSentTo(auth()->user(), ThreadWasUpdated::class);
    } 

    /** @test */
    function a_thread_belongs_to_a_channel()
    {
       $thread = create(Thread::class);
       
       $this->assertInstanceOf(Channel::class, $thread->channel); 
    }

    /** @test */
    function a_thread_can_be_subscribed_to()
    {
        $thread = create('App\Thread');

        $thread->subscribe($userId = 1);

        $this->assertEquals(1, $thread->subscriptions()->where('user_id', $userId)->count());
    }

    /** @test */
    function a_thread_can_be_unsubscribed_from()
    {
        $thread = create('App\Thread');

        $thread->subscribe($userId = 1);

        $thread->unsubscribe($userId);

        $this->assertEquals(0, $thread->subscriptions()->where('user_id', $userId)->count());

    } 

    /** @test */
    function it_knows_if_the_authenticated_user_is_subscribed_to_it()
    {
        $thread = create('App\Thread');
        $this->signIn();
        $this->assertFalse($thread->isSubscribedTo);

        $thread->subscribe();

        $this->assertTrue($thread->isSubscribedTo);
    } 

    /** @test */
    function a_thread_can_check_if_authenticated_user_has_read_all_replies()
    {
        $this->signIn();

        $thread = create('App\Thread');

        tap(auth()->user(), function($user) use ($thread) {

            $this->assertTrue($thread->hasUpdatesFor($user));

            $user->read($thread);
            
            
            $this->assertFalse($thread->hasUpdatesFor(auth()->user()));

        });
        
    } 

    /** @test */
    public function a_thread_records_each_visit()
    {
        $thread = make('App\Thread', ['id' => 9999]);

        $thread->resetVisits();
        $this->assertSame(0, $thread->visits());

        $thread->recordVisit();
        $this->assertEquals(1, $thread->visits());

        $thread->recordVisit();
        $this->assertEquals(2, $thread->visits());
    } 


}

