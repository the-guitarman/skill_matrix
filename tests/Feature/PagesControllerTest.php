<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\{Skill, SkillGroup, User};

class PagesControllerTest extends TestCase
{
    public function testIndex()
    {
        $this->loginRequired('get', 'root');

        $user = factory(User::class)->create();

        $response = 
            $this->actingAs($user)
                ->from(route('login'))
                ->get(route('root'))
                ->assertStatus(200)
                ->assertSee('Übersicht')
                ->assertSee('Alle Skills');

        $skillGroups = SkillGroup::with('skills')->orderBy('name', 'asc');
        foreach($skillGroups as $skillGroup) {
            $response->assertSee(e($skillGroup->name));
            foreach($skillGroups->skills() as $skill) {
                $response->assertSee(e($skill->name));
            }
        }
    }
}
