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
}
