<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfilesTest extends TestCase
{
	use RefreshDatabase;
    
    /** @test */
    public function a_user_has_a_profile() 
    {
    	$user = create(User::class);
    	$this->get("/profiles/$user->name")
    		->assertSee($user->name);
    }

    /** @test */
    public function profiles_display_all_threads_created_by_the_user() 
    {	
    	$this->signIn();
        $user = auth()->user();
    	$thread = create('App\Thread', ['user_id' => $user->id]);
    	$this->get("/profiles/$user->name")
    		->assertSee($thread->title)
    		->assertSee($thread->body);
    }
}
