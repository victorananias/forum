<?php

use Faker\Generator as Faker;
use App\User;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'username' => preg_replace('/[^A-z0-9_-]/', '', $faker->unique()->userName),
        'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
        'remember_token' => str_random(10),
    ];
});

$factory->state(App\User::class, 'verified', function () {
    return [
        'email_verified_at' => \Carbon\Carbon::now()
    ];
});

$factory->state(App\User::class, 'administrator', function () {
    return [
        'is_admin' => true
    ];
});

$factory->define(App\Thread::class, function (Faker $faker) {
    $title = $faker->sentence;
    $slug = str_slug($title);
    $channels = \App\Channel::all();
    return [
        'title' => $title,
        'slug' => $slug,
        'body' => $faker->paragraph,
        'user_id' => function () {
            return factory(App\User::class)->create()->id;
        },
        'channel_id' => $faker->randomElement($channels)->id,
        'locked' => false
    ];
});

$factory->define(App\Channel::class, function (Faker $faker) {
    $word = $faker->word;
    return [
        'name' => $word,
        'slug' => $word
    ];
});

$factory->define(App\Reply::class, function (Faker $faker) {
    return [
        'body' => $faker->paragraph,
        'thread_id' => function () {
            return factory(App\Thread::class)->create()->id;
        },
        'user_id' => function () {
            return factory(App\User::class)->create()->id;
        },
    ];
});

$factory->define(\Illuminate\Notifications\DatabaseNotification::class, function (Faker $faker) {
    return [
        'id' => \Ramsey\Uuid\Uuid::uuid4()->toString(),
        'type' => 'App\Notifications\ThreadWasUpdated',
        'notifiable_id' => function () {
            return auth()->id() ?: factory(User::class)->create()->id;
        },
        'notifiable_type' => 'App\User',
        'data' => ['foo' => 'bar ']
    ];
});
