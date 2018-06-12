<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use App\Models\{Skill, SkillGroup, User};
use Tests\TestCase;
use Faker\Factory;

class SkillGroupsControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function testShowsAllSkillGroups()
    {
        $this->loginRequired('get', 'skill-groups.index');

        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->from(route('root'))
            ->get(route('skill-groups.index'))
            ->assertStatus(200)
            ->assertSee('Skill Groups');
    }

    public function testShowsASkillGroup()
    {
        $skillGroup = factory(SkillGroup::class)->create();

        $this->loginRequired('get', 'skill-groups.show', ['id' => $skillGroup->id]);

        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->from(route('skill-groups.index'))
            ->get(route('skill-groups.show', $skillGroup->id))
            ->assertStatus(200)
            ->assertSee($skillGroup->name);
    }

    public function testShowsCreateASkillGroup()
    {
        $this->loginRequired('get', 'skill-groups.create');

        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->from(route('skill-groups.index'))
            ->get(route('skill-groups.create'))
            ->assertStatus(200)
            ->assertSee('Skill Group anlegen');
    }

    public function testTriesToStoreASkillGroup()
    {
        $this->loginRequired('post', 'skill-groups.store', []);

        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->from(route('skill-groups.create'))
            ->post(route('skill-groups.store'), [
                'skill_group' => [
                    'name' => null,
                ]
            ])
            ->assertStatus(302)
            ->assertRedirect(route('skill-groups.create'))
            ->assertSessionHasErrors([
                'skill_group.name' => 'Der Name der Skill Group wird benötigt.',
            ])
        ;

        $this->actingAs($user)
            ->from(route('skill-groups.create'))
            ->post(route('skill-groups.store'), [
                'skill_group' => [
                    'name' => 'S',
                ]
            ])
            ->assertStatus(302)
            ->assertRedirect(route('skill-groups.create'))
            ->assertSessionHasErrors([
                'skill_group.name' => 'Geben Sie mindestens 2 Zeichen ein.',
            ])
        ;
    }

    public function testtriesToStoreAnExistingSkillGroup()
    {
        $this->loginRequired('post', 'skill-groups.store', []);

        $user = factory(User::class)->create();
        $skillGroup = factory(SkillGroup::class)->create();
        $allSkillGroupsCount = SkillGroup::count();
        $attributes = ['name' => $skillGroup->name];

        $this->actingAs($user)
            ->from(route('skill-groups.create'))
            ->post(route('skill-groups.store'), [
                'skill_group' => $attributes
            ])
            ->assertStatus(302)
            ->assertRedirect(route('skill-groups.create'))
            ->assertSessionHasErrors([
                'skill_group.name' => 'Der Name der Skill Group ist bereits vergeben.'
            ])
        ;

        $this->assertEquals($allSkillGroupsCount, SkillGroup::count());
    }

    public function testStoresASkillGroup()
    {
        $this->loginRequired('post', 'skill-groups.store', []);

        $user = factory(User::class)->create();
        $allSkillGroupsCount = SkillGroup::count();
        $attributes = ['name' => 'New-Skill-Group'];

        $this->actingAs($user)
            ->from(route('skill-groups.create'))
            ->post(route('skill-groups.store'), [
                'skill_group' => $attributes
            ])
            ->assertStatus(302)
            ->assertRedirect(route('skill-groups.index'))
            ->assertSessionHas('flash_notice', 'Die Skill Group ' . $attributes['name'] . ' wurde angelegt.')
        ;

        $this->assertEquals($allSkillGroupsCount + 1, SkillGroup::count());
    }

    public function testShowsEditASkillGroup()
    {
        $user = factory(User::class)->create();
        $skillGroup = factory(SkillGroup::class)->create();

        $this->loginRequired('get', 'skill-groups.edit', ['id' => $skillGroup->id]);

        $this->actingAs($user)
            ->from(route('skill-groups.index'))
            ->get(route('skill-groups.edit', ['id' => $skillGroup->id]))
            ->assertStatus(200)
            ->assertSee('Skill Group ändern');
    }

    public function testTriesToUpdateAnExistingSkillGroup()
    {
        $user = factory(User::class)->create();
        $skillGroup_1 = factory(SkillGroup::class)->create();
        $skillGroup_2 = factory(SkillGroup::class)->create();

        $this->loginRequired('put', 'skill-groups.update', ['id' => $skillGroup_1->id]);

        $allSkillGroupsCount = SkillGroup::count();

        $this->actingAs($user)
            ->from(route('skill-groups.edit', $skillGroup_1->id))
            ->put(route('skill-groups.update', $skillGroup_1->id), [
                'skill_group' => [
                    'name' => $skillGroup_2->name,
                ]
            ])
            ->assertStatus(302)
            ->assertRedirect(route('skill-groups.edit', $skillGroup_1->id))
            ->assertSessionHasErrors([
                'skill_group.name' => 'Der Name der Skill Group ist bereits vergeben.',
            ])
        ;

        $this->assertEquals($allSkillGroupsCount, SkillGroup::count());
    }

    public function testUpdatesAnExistingSkillGroup()
    {
        $user = factory(User::class)->create();
        $skillGroup = factory(SkillGroup::class)->create();

        $this->loginRequired('put', 'skill-groups.update', ['id' => $skillGroup->id]);

        $allskillGroupsCount = SkillGroup::count();

        $faker = Factory::create();
        $name = 'New-Name';

        $this->actingAs($user)
            ->from(route('skill-groups.edit', $skillGroup->id))
            ->put(route('skill-groups.update', $skillGroup->id), [
                'skill_group' => [
                    'name' => $name,
                ]
            ])
            ->assertStatus(302)
            ->assertRedirect(route('skill-groups.index'))
            ->assertSessionHas('flash_notice', 'Die Skill Group ' . $name . ' wurde gespeichert.')
        ;

        $this->assertEquals($allskillGroupsCount, SkillGroup::count());
    }

    public function testTriesToDeleteASkillGroupWithSkills()
    {
        $user = factory(User::class)->create();
        $skillGroup = factory(SkillGroup::class)->create();
        $skill = factory(Skill::class)->create([
            'skill_group_id' => $skillGroup->id
        ]);

        $this->assertCount(1, $skillGroup->skills()->get());

        $this->loginRequired('delete', 'skill-groups.destroy', ['id' => $skillGroup->id]);

        $allSkillGroupsCount = SkillGroup::count();

        $this->actingAs($user)
            ->from(route('skill-groups.show', $skillGroup->id))
            ->delete(route('skill-groups.destroy', $skillGroup->id))
            ->assertStatus(302)
            ->assertRedirect(route('skill-groups.index'))
            ->assertSessionHas('flash_error', 'Die Skill Group '.$skillGroup->name.' konnte nicht gelöscht werden.')
        ;

        $this->assertEquals($allSkillGroupsCount, SkillGroup::count());
        $this->assertEquals($allSkillGroupsCount, SkillGroup::withTrashed()->count());
    }

    public function testDeletesASkillGroupWithoutSkills()
    {
        $user = factory(User::class)->create();
        $skillGroup = factory(SkillGroup::class)->create();

        $this->assertCount(0, $skillGroup->skills()->get());

        $this->loginRequired('delete', 'skill-groups.destroy', ['id' => $skillGroup->id]);

        $allSkillGroupsCount = SkillGroup::count();

        $this->actingAs($user)
            ->from(route('skill-groups.show', $skillGroup->id))
            ->delete(route('skill-groups.destroy', $skillGroup->id))
            ->assertStatus(302)
            ->assertRedirect(route('skill-groups.index'))
            ->assertSessionHas('flash_notice', 'Die Skill Group '.$skillGroup->name.' wurde gelöscht.')
        ;

        $this->assertEquals($allSkillGroupsCount - 1, SkillGroup::count());
        $this->assertEquals($allSkillGroupsCount, SkillGroup::withTrashed()->count());
    }
}
