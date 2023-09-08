<?php

namespace Database\Seeders;

use App\JobSkill;
use Illuminate\Database\Seeder;

class JobSkillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        JobSkill::firstOrCreate([
            'job_skill' => 'Annual Competency Exam',
            'is_default' => 1,
            'is_active' => 1,
            'sort_order' => 20,
            'lang' => 'en',
        ]);
    }
}
