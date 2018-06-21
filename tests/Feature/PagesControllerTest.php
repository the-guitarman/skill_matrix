<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\{Skill, SkillGroup, User};

class PagesControllerTest extends TestCase
{
    public function testRootWithoutLogin()
    {
        $response = $this->get('/');

        $response
            ->assertStatus(302)
            ->assertRedirect(route('login'))
        ;
    }

    public function testRootWithLogin()
    {
        $user = factory(User::class)->create();

        $response = 
            $this->actingAs($user)
                ->from(route('login'))
                ->get('/')
                ->assertStatus(200);

        $skillGroups = SkillGroup::with('skills')->orderBy('name', 'asc');
        foreach($skillGroups as $skillGroup) {
            $response->assertSee(htmlspecialchars($skillGroup->name));
            foreach($skillGroups->skills() as $skill) {
                $response->assertSee(htmlspecialchars($skill->name));
            }
        }
    }
}
