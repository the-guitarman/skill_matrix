<?php

use Faker\Generator as Faker;
use App\Models\{Skill, SkillGroup};

$factory->define(Skill::class, function (Faker $faker) {
    return [
        'skill_group_id' => factory(SkillGroup::class)->create()->id,
        'name' => $faker->name,
    ];
});
