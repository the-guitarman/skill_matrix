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

    public function testShowsAllSkills()
    {
        $this->loginRequired('get', 'skill-groups.skills.index', ['skill_group_id' => $this->skillGroup->id]);

        $this->actingAs($this->user)
            ->from(route('root'))
            ->get(route('skill-groups.skills.index', ['skill_group_id' => $this->skillGroup->id]))
            ->assertStatus(200)
            ->assertSee(e($this->skillGroup->name));
    }

    public function testShowsCreateASkill()
    {
        $this->loginRequired('get', 'skill-groups.skills.create', ['skill_group_id' => $this->skillGroup->id]);

        $this->actingAs($this->user)
            ->from(route('skill-groups.skills.index', ['skill_group_id' => $this->skillGroup->id]))
            ->get(route('skill-groups.skills.create', ['skill_group_id' => $this->skillGroup->id]))
            ->assertStatus(200)
            ->assertSee(e('Skill anlegen'));
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

    public function testStoresASkill()
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

    public function testShowsEditASkill()
    {
        $skill = factory(Skill::class)->create();

        $this->loginRequired('get', 'skill-groups.skills.edit', ['skill_group_id' => $skill->skill_group_id, 'id' => $skill->id]);

        $response = $this->actingAs($this->user)
            ->from(route('skill-groups.show', ['id' => $skill->skill_group_id]))
            ->get(route('skill-groups.skills.edit', ['skill_group_id' => $skill->skill_group_id, 'id' => $skill->id]))
            ->assertStatus(200)
            ->assertSee(e('Skill ändern'));

        $this->responseHasTag($response, 'input', [
            'id' => 'skill_name',
            'name' => 'skill[name]',
            'value' => e($skill->name),
            'placeholder' => "Name",
            'class' => 'form-control',
            'type' => 'text',
        ]);
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
