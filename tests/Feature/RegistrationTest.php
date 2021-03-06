<?php

namespace Tests\Feature;

use App\Mail\PleaseConfirmYourEmail;
use App\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
   use RefreshDatabase;

   public function setUp() 
   {
   		parent::setUp();

   		Mail::fake();
   }

   /** @test */
   public function a_confirmation_email_is_sent_upon_registration()
   {

         $this->post(route('register'), [
            'name' => 'John',
            'email' => 'john@john.com',
            'password' => 'password',
            'password_confirmation' =>'password'
         ]);

   		Mail::assertQueued(PleaseConfirmYourEmail::class);

   } 

   /** @test */
   public function user_can_fully_confirm_their_email_address()
   {
   		
   		$this->post('/register', [
   			'name' => 'John',
   			'email' => 'john@john.com',
   			'password' => 'password',
   			'password_confirmation' =>'password'
   		]);

   		$user = User::whereName('John')->first();

   		$this->assertFalse($user->confirmed);
   		$this->assertNotNull($user->confirmation_token);

   		$response = $this->get(route('confirm-email', ['token' => $user->confirmation_token]))->assertRedirect(route('threads'));

         tap($user->fresh(), function($user){
            $this->assertTrue($user->confirmed); 
            $this->assertNull($user->confirmation_token);   
         }); 			
   }

   /** @test */
    public function confirming_an_invalid_token()
    {

      $this->get(route('confirm-email', ['token' => 'invalid']))
            ->assertRedirect(route('threads'))
            ->assertSessionHas('flash', 'Unknown token');

    }  
}
