<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SearchTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
    	parent::setUp();

    	config(['scout.mysql.mode' => 'LIKE']);
    }

    /** @test */
    public function a_user_can_search_threads()
    {
    	$search = 'foobar';
    	create('App\Thread', [], 2);
    	create('App\Thread', ['body' => "A thread with {$search} term"], 2);

    	$results = $this->getJson("/threads/search?q={$search}")->json();
        //$this->assertTrue(true);
    	$this->assertCount(2, $results['data']);
    } 
}
