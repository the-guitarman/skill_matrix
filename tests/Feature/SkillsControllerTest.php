<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use App\Models\{Skill, SkillGroup, User};
use Tests\TestCase;
use Faker\Factory;

class SkillControllerTest extends TestCase
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
        $this->loginRequired('get', 'skill-groups.skills.index', ['skill_group_id' => $this->skillGroup->id]);

        $this->actingAs($this->user)
            ->from(route('root'))
            ->get(route('skill-groups.skills.index', ['skill_group_id' => $this->skillGroup->id]))
            ->assertStatus(200)
            ->assertSee($this->skillGroup->name);
    }

    public function testShowsCreateASkill()
    {
        $this->loginRequired('get', 'skill-groups.skills.create', ['skill_group_id' => $this->skillGroup->id]);

        $this->actingAs($this->user)
            ->from(route('skill-groups.skills.index', ['skill_group_id' => $this->skillGroup->id]))
            ->get(route('skill-groups.skills.create', ['skill_group_id' => $this->skillGroup->id]))
            ->assertStatus(200)
            ->assertSee('Skill anlegen');
    }

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

/*

    public function testShowsEditASkillGroup()
    {
        $this->user = factory(User::class)->create();
        $skillGroup = factory(SkillGroup::class)->create();

        $this->loginRequired('get', 'skill-groups.skills.edit', ['id' => $skillGroup->id]);

        $this->actingAs($this->user)
            ->from(route('skill-groups.skills.index'))
            ->get(route('skill-groups.skills.edit', ['id' => $skillGroup->id]))
            ->assertStatus(200)
            ->assertSee('Skill Group ändern');
    }

    public function testTriesToUpdateAnExistingSkillGroup()
    {
        $this->user = factory(User::class)->create();
        $skillGroup_1 = factory(SkillGroup::class)->create();
        $skillGroup_2 = factory(SkillGroup::class)->create();

        $this->loginRequired('put', 'skill-groups.skills.update', ['id' => $skillGroup_1->id]);

        $allSkillGroupsCount = SkillGroup::count();

        $this->actingAs($this->user)
            ->from(route('skill-groups.skills.edit', $skillGroup_1->id))
            ->put(route('skill-groups.skills.update', $skillGroup_1->id), [
                'skill_group' => [
                    'name' => $skillGroup_2->name,
                ]
            ])
            ->assertStatus(302)
            ->assertRedirect(route('skill-groups.skills.edit', $skillGroup_1->id))
            ->assertSessionHasErrors([
                'skill_group.name' => 'Der Name der Skill Group ist bereits vergeben.',
            ])
        ;

        $this->assertEquals($allSkillGroupsCount, SkillGroup::count());
    }

    public function testUpdatesAnExistingSkillGroup()
    {
        $this->user = factory(User::class)->create();
        $skillGroup = factory(SkillGroup::class)->create();

        $this->loginRequired('put', 'skill-groups.skills.update', ['id' => $skillGroup->id]);

        $allskillGroupsCount = SkillGroup::count();

        $faker = Factory::create();
        $name = 'New-Name';

        $this->actingAs($this->user)
            ->from(route('skill-groups.skills.edit', $skillGroup->id))
            ->put(route('skill-groups.skills.update', $skillGroup->id), [
                'skill_group' => [
                    'name' => $name,
                ]
            ])
            ->assertStatus(302)
            ->assertRedirect(route('skill-groups.skills.index'))
            ->assertSessionHas('flash_notice', 'Die Skill Group ' . $name . ' wurde gespeichert.')
        ;

        $this->assertEquals($allskillGroupsCount, SkillGroup::count());
    }
*/

    public function testDeletesASkill()
    {
        $skill = factory(Skill::class)->create();

        $this->loginRequired('delete', 'skill-groups.skills.destroy', ['skill_group_id' => $skill->skill_group_id, 'id' => $skill->id]);

        $allSkillsCount = Skill::count();

        $this->actingAs($this->user)
            ->from(route('skill-groups.show', ['id' => $skill->skill_group_id]))
            ->delete(route('skill-groups.skills.destroy', ['skill_group_id' => $skill->skill_group_id, 'id' => $skill->id]))
            ->assertStatus(302)
            ->assertRedirect(route('skill-groups.show', ['id' => $skill->skill_group_id]))
            ->assertSessionHas('flash_notice', 'Der Skill '.$skill->name.' wurde gelöscht.')
        ;

        $this->assertEquals($allSkillsCount - 1, Skill::count());
        $this->assertEquals($allSkillsCount, Skill::withTrashed()->count());
    }
}
