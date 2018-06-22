<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Faker\Factory;
use App\Models\{Skill, UserSkill};

class UserSkillTest extends TestCase
{

    public function testAssociations()
    {
        $userSkill = factory(UserSkill::class)->create();
        $this->assertNotEmpty($userSkill->user);
        $this->assertNotEmpty($userSkill->skill);
    }
}
