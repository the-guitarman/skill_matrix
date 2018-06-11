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

        $skillGroup_1 = SkillGroup::create(['name' => '(Programmier-)Sprachen']);
        $skill_1_1 = $skillGroup_1->skills()->create(['name' => 'PHP']);
        $skill_1_2 = $skillGroup_1->skills()->create(['name' => 'JavaScript']);
        $skill_1_3 = $skillGroup_1->skills()->create(['name' => 'SQL']);

        $skillGroup_2 = SkillGroup::create(['name' => 'Datenbanken']);
        $skill_2_4 = $skillGroup_2->skills()->create(['name' => 'MySQL']);
        $skill_2_5 = $skillGroup_2->skills()->create(['name' => 'Postgres']);

        if (User::count() === 0) {
            $user = User::create([
                'login' => 'tesla', 
                'password' => bcrypt('password'), 
                'name' => 'Nikola Tesla',
                'remember_token' => str_random(10),
            ]);
            $user->skills()->attach($skill_1_1->id, ['grade' => 2]);
            $user->skills()->attach($skill_1_2->id, ['grade' => 2]);
            $user->skills()->attach($skill_1_3->id, ['grade' => 2]);
            $user->skills()->attach($skill_2_4->id, ['grade' => 2]);
            $user->skills()->attach($skill_2_5->id, ['grade' => 3]);
            $user->skills()->detach($skill_2_5->id);
        }
    }
}
