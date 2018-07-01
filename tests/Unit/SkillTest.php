<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Faker\Factory;
use App\Models\{Skill, UserSkill};

class SkillTest extends TestCase
{
    public function testAssociations()
    {
        $skill = factory(Skill::class)->create();
        $this->assertNotEmpty($skill->skillGroup()->get());
        $this->assertEquals(0, $skill->users()->count());

        $userSkill = factory(UserSkill::class)->create([
            'skill_id' => $skill->id
        ]);
        $this->assertEquals(1, $skill->users()->count());

        $user = $skill->users()->first();
        $this->assertTrue($user->user_skill->grade >= 1);
        $this->assertTrue($user->user_skill->grade <= 6);
    }

    public function testSoftDeletingASkill()
    {
        $userSkill = factory(UserSkill::class)->create();
        $skill = $userSkill->skill;
        $allSkillCount = Skill::count();
        $allSkillCountWithTrashed = Skill::withTrashed()->count();
        $allUserSkillCount = UserSkill::count();
        $userSkillCount = $skill->users->count();

        $skill->delete();

        $this->assertEquals($allSkillCount - 1, Skill::count());
        $this->assertEquals($allSkillCountWithTrashed, Skill::withTrashed()->count());
        $this->assertEquals($allUserSkillCount, UserSkill::count());
    }

    public function testForceDeletingASkill()
    {
        $userSkill = factory(UserSkill::class)->create();
        $skill = $userSkill->skill;
        $allSkillCount = Skill::count();
        $allSkillCountWithTrashed = Skill::withTrashed()->count();
        $allUserSkillCount = UserSkill::count();
        $userSkillCount = $skill->users->count();

        $skill->forceDelete();

        $this->assertEquals($allSkillCount - 1, Skill::count());
        $this->assertEquals($allSkillCountWithTrashed - 1, Skill::withTrashed()->count());
        $this->assertEquals($allUserSkillCount - $userSkillCount, UserSkill::count());
    }
}
