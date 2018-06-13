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

/*
    public function testShowsASkillGroup()
    {
        $skillGroup = factory(SkillGroup::class)->create();

        $this->loginRequired('get', 'skill-groups.skills.show', ['id' => $skillGroup->id]);

        $this->user = factory(User::class)->create();

        $this->actingAs($this->user)
            ->from(route('skill-groups.skills.index'))
            ->get(route('skill-groups.skills.show', $skillGroup->id))
            ->assertStatus(200)
            ->assertSee($skillGroup->name);
    }

    public function testShowsCreateASkillGroup()
    {
        $this->loginRequired('get', 'skill-groups.skills.create');

        $this->user = factory(User::class)->create();

        $this->actingAs($this->user)
            ->from(route('skill-groups.skills.index'))
            ->get(route('skill-groups.skills.create'))
            ->assertStatus(200)
            ->assertSee('Skill Group anlegen');
    }

    public function testTriesToStoreASkillGroup()
    {
        $this->loginRequired('post', 'skill-groups.skills.store', []);

        $this->user = factory(User::class)->create();

        $this->actingAs($this->user)
            ->from(route('skill-groups.skills.create'))
            ->post(route('skill-groups.skills.store'), [
                'skill_group' => [
                    'name' => null,
                ]
            ])
            ->assertStatus(302)
            ->assertRedirect(route('skill-groups.skills.create'))
            ->assertSessionHasErrors([
                'skill_group.name' => 'Der Name der Skill Group wird benötigt.',
            ])
        ;

        $this->actingAs($this->user)
            ->from(route('skill-groups.skills.create'))
            ->post(route('skill-groups.skills.store'), [
                'skill_group' => [
                    'name' => 'S',
                ]
            ])
            ->assertStatus(302)
            ->assertRedirect(route('skill-groups.skills.create'))
            ->assertSessionHasErrors([
                'skill_group.name' => 'Geben Sie mindestens 2 Zeichen ein.',
            ])
        ;
    }

    public function testtriesToStoreAnExistingSkillGroup()
    {
        $this->loginRequired('post', 'skill-groups.skills.store', []);

        $this->user = factory(User::class)->create();
        $skillGroup = factory(SkillGroup::class)->create();
        $allSkillGroupsCount = SkillGroup::count();
        $attributes = ['name' => $skillGroup->name];

        $this->actingAs($this->user)
            ->from(route('skill-groups.skills.create'))
            ->post(route('skill-groups.skills.store'), [
                'skill_group' => $attributes
            ])
            ->assertStatus(302)
            ->assertRedirect(route('skill-groups.skills.create'))
            ->assertSessionHasErrors([
                'skill_group.name' => 'Der Name der Skill Group ist bereits vergeben.'
            ])
        ;

        $this->assertEquals($allSkillGroupsCount, SkillGroup::count());
    }

    public function testStoresASkillGroup()
    {
        $this->loginRequired('post', 'skill-groups.skills.store', []);

        $this->user = factory(User::class)->create();
        $allSkillGroupsCount = SkillGroup::count();
        $attributes = ['name' => 'New-Skill-Group'];

        $this->actingAs($this->user)
            ->from(route('skill-groups.skills.create'))
            ->post(route('skill-groups.skills.store'), [
                'skill_group' => $attributes
            ])
            ->assertStatus(302)
            ->assertRedirect(route('skill-groups.skills.index'))
            ->assertSessionHas('flash_notice', 'Die Skill Group ' . $attributes['name'] . ' wurde angelegt.')
        ;

        $this->assertEquals($allSkillGroupsCount + 1, SkillGroup::count());
    }

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

    public function testTriesToDeleteASkillGroupWithSkills()
    {
        $this->user = factory(User::class)->create();
        $skillGroup = factory(SkillGroup::class)->create();
        $skill = factory(Skill::class)->create([
            'skill_group_id' => $skillGroup->id
        ]);

        $this->assertCount(1, $skillGroup->skills()->get());

        $this->loginRequired('delete', 'skill-groups.skills.destroy', ['id' => $skillGroup->id]);

        $allSkillGroupsCount = SkillGroup::count();

        $this->actingAs($this->user)
            ->from(route('skill-groups.skills.show', $skillGroup->id))
            ->delete(route('skill-groups.skills.destroy', $skillGroup->id))
            ->assertStatus(302)
            ->assertRedirect(route('skill-groups.skills.index'))
            ->assertSessionHas('flash_error', 'Die Skill Group '.$skillGroup->name.' konnte nicht gelöscht werden.')
        ;

        $this->assertEquals($allSkillGroupsCount, SkillGroup::count());
        $this->assertEquals($allSkillGroupsCount, SkillGroup::withTrashed()->count());
    }

    public function testDeletesASkillGroupWithoutSkills()
    {
        $this->user = factory(User::class)->create();
        $skillGroup = factory(SkillGroup::class)->create();

        $this->assertCount(0, $skillGroup->skills()->get());

        $this->loginRequired('delete', 'skill-groups.skills.destroy', ['id' => $skillGroup->id]);

        $allSkillGroupsCount = SkillGroup::count();

        $this->actingAs($this->user)
            ->from(route('skill-groups.skills.show', $skillGroup->id))
            ->delete(route('skill-groups.skills.destroy', $skillGroup->id))
            ->assertStatus(302)
            ->assertRedirect(route('skill-groups.skills.index'))
            ->assertSessionHas('flash_notice', 'Die Skill Group '.$skillGroup->name.' wurde gelöscht.')
        ;

        $this->assertEquals($allSkillGroupsCount - 1, SkillGroup::count());
        $this->assertEquals($allSkillGroupsCount, SkillGroup::withTrashed()->count());
    }
*/
}
