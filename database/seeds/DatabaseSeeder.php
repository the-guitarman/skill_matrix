<?php

use Illuminate\Database\Seeder;
use App\Models\{SkillGroup, Skill, User};

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);

        $skillGroup_1 = SkillGroup::create(['name' => 'Programmier- Sprachen']);
        $skill_1_1 = $skillGroup_1->skills()->create(['name' => 'Elixir']);
        $skill_1_2 = $skillGroup_1->skills()->create(['name' => 'Ruby']);
        $skill_1_3 = $skillGroup_1->skills()->create(['name' => 'Rust']);
        $skill_1_4 = $skillGroup_1->skills()->create(['name' => 'PHP']);
        $skill_1_5 = $skillGroup_1->skills()->create(['name' => 'JavaScript (JS)']);
        $skill_1_6 = $skillGroup_1->skills()->create(['name' => 'SQL']);

        $skillGroup_2 = SkillGroup::create(['name' => 'JS Erweiterungen']);
        $skill_2_1 = $skillGroup_2->skills()->create(['name' => 'jQuery']);
        $skill_2_2 = $skillGroup_2->skills()->create(['name' => 'Prototype']);
        $skill_2_3 = $skillGroup_2->skills()->create(['name' => 'TypeScript']);

        $skillGroup_3 = SkillGroup::create(['name' => 'Backend Frameworks']);
        $skill_3_1 = $skillGroup_3->skills()->create(['name' => 'RubyOnRails']);
        $skill_3_2 = $skillGroup_3->skills()->create(['name' => 'Laravel']);
        $skill_3_3 = $skillGroup_3->skills()->create(['name' => 'CakePHP']);
        $skill_3_4 = $skillGroup_3->skills()->create(['name' => 'PhoenixFramework']);

        $skillGroup_4 = SkillGroup::create(['name' => 'Frontend Frameworks']);
        $skill_4_1 = $skillGroup_4->skills()->create(['name' => 'EmberJS']);
        $skill_4_2 = $skillGroup_4->skills()->create(['name' => 'React']);

        $skillGroup_5 = SkillGroup::create(['name' => 'Datenbanken']);
        $skill_5_1 = $skillGroup_5->skills()->create(['name' => 'MySQL']);
        $skill_5_2 = $skillGroup_5->skills()->create(['name' => 'Postgres']);
        $skill_5_3 = $skillGroup_5->skills()->create(['name' => 'MS-SQL-Server']);
        $skill_5_4 = $skillGroup_5->skills()->create(['name' => 'MongoDB']);

        if (User::count() === 0) {
            $user = User::create([
                'login' => 'tesla', 
                'password' => bcrypt('password'), 
                'name' => 'Nikola Tesla',
                'remember_token' => str_random(10),
            ]);
            $user->skills()->attach($skill_1_1->id, ['grade' => 6]);
            $user->skills()->attach($skill_1_2->id, ['grade' => 6]);
            $user->skills()->attach($skill_1_3->id, ['grade' => 6]);
            $user->skills()->attach($skill_2_1->id, ['grade' => 6]);
            $user->skills()->attach($skill_2_2->id, ['grade' => 6]);
            $user->skills()->detach($skill_2_3->id);
        }
    }
}
