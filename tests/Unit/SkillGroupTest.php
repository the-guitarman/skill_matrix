<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Faker\Factory;
use App\Models\{Skill, SkillGroup};

class SkillGroupTest extends TestCase
{
    public function testAssociations()
    {
        $skillGroup = factory(SkillGroup::class)->create();
        $this->assertEquals(0, $skillGroup->skills()->count());
        $this->assertEquals(0, $skillGroup->users()->count());
    }

    public function testSoftDeletingASkillGroup()
    {
        $skill = factory(Skill::class)->create();
        $skillGroup = $skill->skillGroup;
        $allSkillCountWithTrashed = Skill::withTrashed()->count();
        $allSkillGroupCount = SkillGroup::count();
        $allSkillGroupCountWithTrashed = SkillGroup::withTrashed()->count();

        $skillGroup->delete();

        $this->assertEquals($allSkillCountWithTrashed, Skill::withTrashed()->count());
        $this->assertEquals($allSkillGroupCount - 1, SkillGroup::count());
        $this->assertEquals($allSkillGroupCountWithTrashed, SkillGroup::withTrashed()->count());
    }

    public function testForceDeletingASkillGroup()
    {
        $skill = factory(Skill::class)->create();
        $skillGroup = $skill->skillGroup;
        $allSkillCountWithTrashed = Skill::withTrashed()->count();
        $allSkillGroupCount = SkillGroup::count();
        $allSkillGroupCountWithTrashed = SkillGroup::withTrashed()->count();
        $allSkillsOfSkillGroupCountWithTrashed = $skillGroup->skills()->withTrashed()->count();

        $skillGroup->forceDelete();

        $this->assertEquals($allSkillCountWithTrashed - $allSkillsOfSkillGroupCountWithTrashed, Skill::withTrashed()->count());
        $this->assertEquals($allSkillGroupCount - 1, SkillGroup::count());
        $this->assertEquals($allSkillGroupCountWithTrashed - 1, SkillGroup::withTrashed()->count());
    }
}
