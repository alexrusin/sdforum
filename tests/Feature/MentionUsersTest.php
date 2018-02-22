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

    /** @test */
    public function it_can_fetch_all_mentioned_users_starting_from_given_characters()
    {
        create('App\User', ['name' => 'JohnDoe']);
        create('App\User', ['name' => 'JohnDoe2']);
        create('App\User', ['name' => 'JaneDoe']);

        $response = $this->json('GET', '/api/users', ['name' => 'john']);

        $this->assertCount(2, $response->json());
    } 
}
