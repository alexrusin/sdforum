<?php

namespace Tests\Feature;

use App\Favorite;
use App\Reply;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FavoritesTest extends TestCase
{

	use DatabaseMigrations;

	/** @test */
	public function guests_can_not_favorite_anything() 
	{	
		$this->withExceptionHandling()
			->post('/replies/1/favorites')
			->assertRedirect('/login');
	}
    
    /** @test */
    public function an_authenticated_user_can_favoriet_any_reply() 
    {
    	$this->signIn();
    	$reply = create(Reply::class);

    	$this->post('/replies/' . $reply->id .'/favorites');
    	$this->assertCount(1, $reply->favorites);
    }

    /** @test */
    public function an_authenticated_user_may_only_favorite_a_reply_once() 
    {
    	$this->signIn();
    	$reply = create(Reply::class);

    	$this->post('/replies/' . $reply->id .'/favorites');

    	$this->post('/replies/' . $reply->id .'/favorites');
    	
    	$this->assertCount(1, $reply->favorites);
    }

    /** @test */
    public function an_authenticated_user_can_unfavorite_reply()
    {
        $this->signIn();
        $reply = create(Reply::class);

        $reply->favorite();

        $this->delete('/replies/' . $reply->id .'/favorites');
        
        $this->assertCount(0, $reply->favorites);

    }
}
