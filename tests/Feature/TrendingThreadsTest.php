<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Redis;
use Tests\TestCase;

class TrendingThreadsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp()
    {
    	parent::setUp();

    	//Redis::del(config('filesystems.trending_threads'));

    }

    /** @test */
    public function it_increments_a_treads_score_each_time_it_is_read()
    {
        $this->markTestSkipped('must be revisited. No Redis');
    	$this->assertEmpty(Redis::zrevrange(config('filesystems.trending_threads'), 0, -1));
    	$thread = create('App\Thread');

    	$this->call('GET', $thread->path());

    	$trending = Redis::zrevrange(config('filesystems.trending_threads'), 0, -1);

    	$this->assertCount(1, $trending);

    	$this->assertEquals($thread->title, json_decode($trending[0])->title);
    } 
}
