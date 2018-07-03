<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use App\Models\{Skill, SkillGroup, User, UserSkill};
use Tests\TestCase;
use Faker\Factory;

class MySkillControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected $user = null;
    protected $skillGroup = null;

    public function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
        $this->skillGroup = factory(SkillGroup::class)->create();
    }

    public function testShowsAllSkillGroups()
    {
        $this->loginRequired('get', 'skills.my.index');

        $user = factory(User::class)->create();

        $response = 
            $this->actingAs($user)
                ->from(route('login'))
                ->get(route('skills.my.index'))
                ->assertStatus(200)
                ->assertSee('Übersicht')
                ->assertSee('Meine Skills');

        $skillGroups = SkillGroup::with('skills')->orderBy('name', 'asc');
        foreach($skillGroups as $skillGroup) {
            $response->assertSee(e($skillGroup->name));
            foreach($skillGroups->skills() as $skill) {
                $response->assertSee(e($skill->name));
            }
        }
    }

    public function testShowsCreateASkill()
    {
        $skill = factory(Skill::class)->create();

        $this->loginRequired('get', 'skills.my.create', ['skill_id' => $skill->id]);

        $this->actingAs($this->user)
            ->from(route('skills.my.index'))
            ->get(route('skills.my.create', ['skill_id' => $skill->id]))
            ->assertStatus(200)
            ->assertSee(e('Skill eintragen'));
    }





    public function testShowsEditASkillGroup()
    {
        $skill = factory(Skill::class)->create();
        $this->user->skills()->attach($skill->id, ['grade' => 3]);

        $this->loginRequired('get', 'skills.my.edit', ['skill_id' => $skill->id]);

        $this->actingAs($this->user)
            ->from(route('skills.my.index'))
            ->get(route('skills.my.edit', ['skill_id' => $skill->id]))
            ->assertStatus(200)
            ->assertSee(e('Skill ändern'));
    }






    public function testDeletesASkill()
    {
        $skill = factory(Skill::class)->create();

        $this->loginRequired('delete', 'skills.my.destroy', ['skill_id' => $skill->id]);

        $allUsersCount = User::count();
        $allSkillsCount = Skill::count();
        $allSkillGroupsCount = SkillGroup::count();
        $allUserSkillsCount = UserSkill::count();

        $this->actingAs($this->user)
            ->from(route('skills.my.index'))
            ->delete(route('skills.my.destroy', ['skill_id' => $skill->id]))
            ->assertStatus(302)
            ->assertRedirect(route('skills.my.index'))
            ->assertSessionHas('flash_notice', 'Ihr Skill '.$skill->name.' wurde gelöscht.')
        ;

        $this->assertEquals($allUsersCount, User::count());
        $this->assertEquals($allSkillsCount, Skill::count());
        $this->assertEquals($allSkillGroupsCount, SkillGroup::count());
        $this->assertEquals($allUserSkillsCount, UserSkill::count());
    }
/*

    public function testTriesToStoreASkill()
    {
        $this->loginRequired('post', 'skill-groups.skills.store', ['skill_group_id' => $this->skillGroup->id]);

        $this->actingAs($this->user)
            ->from(route('skill-groups.skills.create', ['skill_group_id' => $this->skillGroup->id]))
            ->post(route('skill-groups.skills.store', ['skill_group_id' => $this->skillGroup->id]), [
                'skill' => [
                    'skill_group_id' => null,
                    'name' => null,
                ]
            ])
            ->assertStatus(302)
            ->assertRedirect(route('skill-groups.skills.create', ['skill_group_id' => $this->skillGroup->id]))
            ->assertSessionHasErrors([
                'skill.skill_group_id' => 'Wählen Sie die Skill Group aus.',
                'skill.name' => 'Der Name des Skills wird benötigt.',
            ])
        ;

        $this->actingAs($this->user)
            ->from(route('skill-groups.skills.create', ['skill_group_id' => $this->skillGroup->id]))
            ->post(route('skill-groups.skills.store', ['skill_group_id' => $this->skillGroup->id,]), [
                'skill' => [
                    'skill_group_id' => 99999,
                    'name' => 'S',
                ]
            ])
            ->assertStatus(302)
            ->assertRedirect(route('skill-groups.skills.create', ['skill_group_id' => $this->skillGroup->id]))
            ->assertSessionHasErrors([
                'skill.skill_group_id' => 'Wählen Sie die Skill Group aus.',
                'skill.name' => 'Geben Sie mindestens 2 Zeichen ein.',
            ])
        ;
    }

    public function testtriesToStoreAnExistingSkill()
    {
        $this->loginRequired('post', 'skill-groups.skills.store', ['skill_group_id' => $this->skillGroup->id]);

        $skill = factory(Skill::class)->create();
        $allSkillsCount = Skill::count();
        $attributes = ['skill_group_id' => $skill->skill_group_id, 'name' => $skill->name];

        $this->actingAs($this->user)
            ->from(route('skill-groups.skills.create', ['skill_group_id' => $this->skillGroup->id]))
            ->post(route('skill-groups.skills.store', ['skill_group_id' => $this->skillGroup->id]), [
                'skill' => $attributes
            ])
            ->assertStatus(302)
            ->assertRedirect(route('skill-groups.skills.create', ['skill_group_id' => $this->skillGroup->id]))
            ->assertSessionHasErrors([
                'skill.name' => 'Der Name des Skills ist bereits vergeben.'
            ])
        ;

        $this->assertEquals($allSkillsCount, Skill::count());
    }

    public function testStoresASkillGroup()
    {
        $this->loginRequired('post', 'skill-groups.skills.store', ['skill_group_id' => $this->skillGroup->id]);

        $newSkillGroup = factory(SkillGroup::class)->create();

        $allSkillCount = Skill::count();
        $attributes = ['skill_group_id' => $newSkillGroup->id, 'name' => 'New-Skill'];

        $this->actingAs($this->user)
            ->from(route('skill-groups.skills.create', ['skill_group_id' => $this->skillGroup->id]))
            ->post(route('skill-groups.skills.store', ['skill_group_id' => $this->skillGroup->id]), [
                'skill' => $attributes
            ])
            ->assertStatus(302)
            ->assertRedirect(route('skill-groups.show', ['id' => $newSkillGroup->id]))
            ->assertSessionHas('flash_notice', 'Der Skill ' . $attributes['name'] . ' wurde angelegt.')
        ;

        $this->assertEquals($allSkillCount + 1, Skill::count());
    }

    public function testTriesToUpdateAnExistingSkill()
    {
        $skill_1 = factory(Skill::class)->create();
        $skill_2 = factory(Skill::class)->create();

        $this->loginRequired('put', 'skill-groups.skills.update', ['skill_group_id' => $skill_1->skill_group_id, 'id' => $skill_1->id]);

        $allSkillsCount = Skill::count();

        $this->actingAs($this->user)
            ->from(route('skill-groups.skills.edit', ['skill_group_id' => $skill_1->skill_group_id, 'id' => $skill_1->id]))
            ->put(route('skill-groups.skills.update', ['skill_group_id' => $skill_1->skill_group_id, 'id' => $skill_1->id]), [
                'skill' => [
                    'skill_group_id' => 99999,
                    'name' => $skill_2->name,
                ]
            ])
            ->assertStatus(302)
            ->assertRedirect(route('skill-groups.skills.edit', ['skill_group_id' => $skill_1->skill_group_id, 'id' => $skill_1->id]))
            ->assertSessionHasErrors([
                'skill.skill_group_id' => 'Wählen Sie die Skill Group aus.',
                'skill.name' => 'Der Name des Skills ist bereits vergeben.',
            ])
        ;

        $this->assertEquals($allSkillsCount, Skill::count());
    }

    public function testUpdatesAnExistingSkill()
    {
        $skill = factory(Skill::class)->create();

        $this->loginRequired('put', 'skill-groups.skills.update', ['skill_group_id' => $skill->skill_group_id, 'id' => $skill->id]);

        $allSkillsCount = Skill::count();

        $faker = Factory::create();
        $name = 'New-Name';

        $this->actingAs($this->user)
            ->from(route('skill-groups.skills.edit', ['skill_group_id' => $skill->skill_group_id, 'id' => $skill->id]))
            ->put(route('skill-groups.skills.update', ['skill_group_id' => $skill->skill_group_id, 'id' => $skill->id]), [
                'skill' => [
                    'skill_group_id' => $skill->skill_group_id,
                    'name' => $name,
                ]
            ])
            ->assertStatus(302)
            ->assertRedirect(route('skill-groups.show', ['skill_group_id' => $skill->skill_group_id]))
            ->assertSessionHas('flash_notice', 'Der Skill ' . $name . ' wurde gespeichert.')
        ;

        $this->assertEquals($allSkillsCount, Skill::count());
    }
*/
}
