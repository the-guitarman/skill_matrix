<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Faker\Factory;
use App\Models\Skill;

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
    }
}
