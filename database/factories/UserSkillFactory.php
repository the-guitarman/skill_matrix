<?php

use Faker\Generator as Faker;
use App\Models\{User, UserSkill, Skill};

$factory->define(UserSkill::class, function (Faker $faker) {
    return [
        'user_id' => factory(User::class)->create()->id,
        'skill_id' => factory(Skill::class)->create()->id,
        'grade' => $faker->numberBetween(1, 6),
    ];
});
