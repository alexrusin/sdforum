<?php

namespace Tests\Unit;

use App\Reply;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ReplyTest extends TestCase
{
	use DatabaseMigrations;
    /** @test */

    public function it_has_an_ownwer()
    {
    	$reply = factory(Reply::class)->create();

    	$this->assertInstanceOf(User::class, $reply->owner);
    }

    /** @test */
    public function it_know_if_it_was_just_published()
    {
    	$reply = create('App\Reply');

    	$this->assertTrue($reply->wasJustPublished());

    	$reply->created_at = Carbon::now()->subMonth();

 		$this->assertFalse($reply->wasJustPublished());   	
    } 

    /** @test */
    public function it_wraps_mentioned_username_with_anchor_tags()
    {
        $reply = new \App\Reply([
            'body' => 'Hello @JaneDoe.'
        ]);

        $this->assertEquals('Hello <a href="/profiles/JaneDoe">@JaneDoe</a>.',
            $reply->body);
    } 

    /** @test */
    public function it_knows_if_it_is_the_best_reply()
    {
        $reply = create('App\Reply');

        $this->assertFalse($reply->isBest());

        $reply->thread->update(['best_reply_id' => $reply->id]);

        $this->assertTrue($reply->fresh()->isBest());
    }

    /** @test */
     public function a_replies_body_is_sanitized_automatically()
     {
        $reply = make('App\Reply', ['body' => '<script>alert("bad")</script><p>This is ok</p>']);

        $this->assertEquals('<p>This is ok</p>', $reply->body);
     } 	
}
