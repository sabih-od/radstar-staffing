<?php

namespace Database\Seeders;

use App\Package;
use Illuminate\Database\Seeder;

class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Package::truncate();

        //for employer
        Package::create([
            'package_title' => 'Employer - Basic',
            'package_price' => 0.00,
            'package_num_days' => 3650,
            'package_num_listings' => 30,
            'package_for' => 'employer'
        ]);

        //for candidate
        Package::create([
            'package_title' => 'Candidate - Basic',
            'package_price' => 0.00,
            'package_num_days' => 3650,
            'package_num_listings' => 30,
            'package_for' => 'job_seeker'
        ]);
    }
}
