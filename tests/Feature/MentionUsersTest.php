<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MentionUsersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function mention_one_user_in_a_reply_are_notified()
    {
    	$john = create('App\User', ['name' => 'JohnDoe']);

    	$this->signIn($john);

    	$jane = create('App\User', ['name' => 'JaneDoe']);

    	$thread = create('App\Thread');

    	$reply = make('App\Reply', [
    		'body' => '@JaneDoe look at this'
    	]);

    	$this->json('post', $thread->path() . '/replies', $reply->toArray());

    	$this->assertCount(1, $jane->notifications);
    } 

    /** @test */
    public function mention_users_in_a_reply_are_notified()
    {
    	$john = create('App\User', ['name' => 'JohnDoe']);

    	$this->signIn($john);

    	$jane = create('App\User', ['name' => 'JaneDoe']);
    	$jeff = create('App\User', ['name' => 'JeffDoe']);

    	$thread = create('App\Thread');

    	$reply = make('App\Reply', [
    		'body' => '@JaneDoe look at @JeffDoe'
    	]);

    	$this->json('post', $thread->path() . '/replies', $reply->toArray());

    	$this->assertCount(1, $jane->notifications);
    	$this->assertCount(1, $jeff->notifications);
    } 
}
