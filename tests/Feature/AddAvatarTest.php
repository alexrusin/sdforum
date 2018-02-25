<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AddAvatarTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function only_members_can_have_avatars()
    {
    	$this->withExceptionHandling();
    	$this->json('POST', '/api/users/1/avatar')
			->assertStatus(401);
    } 

    /** @test */
    public function a_valid_avatar_must_be_provided()
    {
    	$this->withExceptionHandling()->signIn();

    	$this->json('POST', '/api/users/' . auth()->id() . '/avatar', 
    	[
    		'avatar' => 'not-an-image'
    	])->assertStatus(422);
    } 

    /** @test */
    // public function a_user_may_add_avatar_to_their_profile() 
    // {
    // 	$this->signIn();

    // 	Storage::fake('public');

    // 	$this->json('POST', '/api/users/' . auth()->id() . '/avatar', 
    // 	[
    // 		'avatar' => $file = UploadedFile::fake()->image('avatar.jpg')
    // 	]);


    // 	$this->assertEquals('avatars/' . $file->hashName(), auth()->user()->getOriginal()['avatar_path']);

    // 	Storage::disk('public')->assertExists('avatars/' . $file->hashName());
    // }

}
