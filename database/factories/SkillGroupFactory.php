<?php

use Faker\Generator as Faker;

$factory->define(App\Models\SkillGroup::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
    ];
});
