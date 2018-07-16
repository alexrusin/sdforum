<?php

use App\User;
use Ramsey\Uuid\Uuid;
use Faker\Generator as Faker;
use App\Notifications\ThreadWasUpdated;
use Illuminate\Notifications\DatabaseNotification;

$factory->define(DatabaseNotification::class, function (Faker $faker) {
    return [
       'id' => Uuid::uuid4()->toString(),
       'type' => ThreadWasUpdated::class,
       'notifiable_id' => auth()->id() ?: factory(User::class)->create()->id,
       'notifiable_type' => User::class,
       'data' => ['foo' => 'bar']
    ];
});
