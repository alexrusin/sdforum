<?php

use App\Channel;
use App\Thread;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Redis;

class ThreadsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $projects = [
			'General',
			'Binary Divider',
			'Digital Integrator'
		];

		factory(Thread::class, count($projects))->create();

		$channels = Channel::all();

		foreach ($channels as $channel) {
			$channel->name = array_first($projects);
			$channel->slug = kebab_case(array_first($projects));
			$channel->save();
			array_shift($projects);
		}

		User::create([
			'name' => 'Alex',
			'email' => 'alex@alex.com',
			'password' => bcrypt('password'),
			'confirmed' => true
		]);

		Redis::del(config('filesystems.trending_threads'));
    }
}
