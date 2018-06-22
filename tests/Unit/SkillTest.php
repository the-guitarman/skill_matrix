<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Faker\Factory;
use App\Models\{Skill, UserSkill};

class SkillTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
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
}
