<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Faker\Factory;
use App\Models\SkillGroup;

class SkillGroupTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testAssociations()
    {
        $skillGroup = factory(SkillGroup::class)->create();
        $this->assertEquals(0, $skillGroup->skills()->count());
        $this->assertEquals(0, $skillGroup->users()->count());
    }
}
