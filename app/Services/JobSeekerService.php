<?php

namespace App\Services;

use App\Helpers\DataArrayHelper;
use App\Http\Requests\Request;
use App\Traits\FetchJobSeekers;
use ImgUploader;

class JobSeekerService
{
    use FetchJobSeekers;

    public function getJobSeekers($request , $limit , $page)
    {

        $search = $request->query('search', '');
        $functional_area_ids = $request->query('functional_area_id', array());
        $country_ids = $request->query('country_id', array());
        $state_ids = $request->query('state_id', array());
        $city_ids = $request->query('city_id', array());
        $career_level_ids = $request->query('career_level_id', array());
        $gender_ids = $request->query('gender_id', array());
        $industry_ids = $request->query('industry_ids', array());
        $job_experience_ids = $request->query('job_experience_id', array());
        $current_salary = $request->query('current_salary', '');
        $expected_salary = $request->query('expected_salary', '');
        $salary_currency = $request->query('salary_currency', '');
        $order_by = $request->query('order_by', 'id');
        $limit =  intval($limit);
        $page =  intval($page);

        $jobSeekerIdsArray = $this->fetchIdsArray(
            $search, $industry_ids, $functional_area_ids,
            $country_ids, $state_ids, $city_ids,
            $career_level_ids, $gender_ids, $job_experience_ids,
            $current_salary, $expected_salary, $salary_currency,
            'users.id');

        $seoArray = $this->getSEO(
            $functional_area_ids, $country_ids, $state_ids,
            $city_ids, $career_level_ids, $gender_ids,
            $job_experience_ids);

        $result =
            [
                'job_seekers' => $this->fetchJobSeekers(
                    $search, $industry_ids, $functional_area_ids,
                    $country_ids, $state_ids, $city_ids,
                    $career_level_ids, $gender_ids, $job_experience_ids,
                    $current_salary, $expected_salary, $salary_currency,
                    $order_by, $limit,$page),

                'job_seeker_ids' => $jobSeekerIdsArray,

                'skill_ids' => $this->fetchSkillIdsArray($jobSeekerIdsArray),

                'country_ids' => $this->fetchIdsArray(
                    $search, $industry_ids, $functional_area_ids,
                    $country_ids, $state_ids, $city_ids,
                    $career_level_ids, $gender_ids, $job_experience_ids,
                    $current_salary, $expected_salary, $salary_currency,
                    'users.country_id'),

                'state_ids' => $this->fetchIdsArray(
                    $search, $industry_ids, $functional_area_ids,
                    $country_ids, $state_ids, $city_ids,
                    $career_level_ids, $gender_ids, $job_experience_ids,
                    $current_salary, $expected_salary, $salary_currency,
                    'users.state_id'),

                'city_ids' => $this->fetchIdsArray(
                    $search, $industry_ids, $functional_area_ids,
                    $country_ids, $state_ids, $city_ids,
                    $career_level_ids, $gender_ids, $job_experience_ids,
                    $current_salary, $expected_salary, $salary_currency,
                    'users.city_id'),

                'industry_ids' => $this->fetchIdsArray(
                    $search, $industry_ids, $functional_area_ids,
                    $country_ids, $state_ids, $city_ids,
                    $career_level_ids, $gender_ids, $job_experience_ids,
                    $current_salary, $expected_salary, $salary_currency,
                    'users.industry_id'),

                'function_area_ids' => $this->fetchIdsArray(
                    $search, $industry_ids, $functional_area_ids,
                    $country_ids, $state_ids, $city_ids,
                    $career_level_ids, $gender_ids, $job_experience_ids,
                    $current_salary, $expected_salary, $salary_currency,
                    'users.functional_area_id'),

                'career_level_ids' => $this->fetchIdsArray(
                    $search, $industry_ids, $functional_area_ids,
                    $country_ids, $state_ids, $city_ids,
                    $career_level_ids, $gender_ids, $job_experience_ids,
                    $current_salary, $expected_salary, $salary_currency,
                    'users.career_level_id'),

                'gender_ids' => $this->fetchIdsArray(
                    $search, $industry_ids, $functional_area_ids,
                    $country_ids, $state_ids, $city_ids,
                    $career_level_ids, $gender_ids, $job_experience_ids,
                    $current_salary, $expected_salary, $salary_currency,
                    'users.gender_id'),

                'job_experience_ids' => $this->fetchIdsArray(
                    $search, $industry_ids, $functional_area_ids,
                    $country_ids, $state_ids, $city_ids,
                    $career_level_ids, $gender_ids, $job_experience_ids,
                    $current_salary, $expected_salary, $salary_currency,
                    'users.job_experience_id'),

                'seo_array' => $seoArray,

                'currencies' => DataArrayHelper::currenciesArray(),

                'seo_obj' => (object) array(
                    'seo_title' => $seoArray['description'],
                    'seo_description' => $seoArray['description'],
                    'seo_keywords' => $seoArray['keywords'],
                    'seo_other' => ''
                ),
        ];
        return $result;
    }


}