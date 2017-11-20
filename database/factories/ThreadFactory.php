<?php

use App\Channel;
use App\Reply;
use App\Thread;
use App\User;
use Faker\Generator as Faker;

/* @var Illuminate\Database\Eloquent\Factory $factory */

$factory->define(Thread::class, function (Faker $faker) {
    return [
    	'user_id' => function() {
    		return factory(User::class)->create()->id;
    	},
        'channel_id' => function() {
            return factory(Channel::class)->create()->id;
        },
        'title' => $faker->sentence,
        'body' => $faker->paragraph,
    ];
});

$factory->define(Reply::class, function (Faker $faker) {
    return [
    	'thread_id' => function() {
    		return factory(Thread::class)->create()->id;
    	},
    	'user_id' => function() {
    		return factory(User::class)->create()->id;
    	},
        
        'body' => $faker->paragraph,
    ];
});
