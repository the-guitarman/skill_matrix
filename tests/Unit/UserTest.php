<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Faker\Factory;
use App\Models\{Skill, UserSkill};

class UserTest extends TestCase
{
    public function testDetachingSkills()
    {
        $userSkill = factory(UserSkill::class)->create();
        $user = $userSkill->user;
        $allSkillCount = Skill::count();
        $allUserSkillCount = UserSkill::count();
        $userSkillCount = $user->skills()->count();

        $user->delete();

        $this->assertEquals($allSkillCount, Skill::count());
        $this->assertEquals($allUserSkillCount - $userSkillCount, UserSkill::count());
    }
}
