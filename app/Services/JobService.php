<?php

namespace App\Services;

use App\Traits\FetchJobs;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Helpers\DataArrayHelper;


class JobService
{
    use FetchJobs;

    public function assignJobValues($request , $company)
    {
        return [
            'company_id' => $company->id,
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'benefits' => $request->input('benefits'),
            'country_id' => $request->input('country_id'),
            'state_id' => $request->input('state_id'),
            'city_id' => $request->input('city_id'),
            'is_freelance' => $request->input('is_freelance'),
            'career_level_id' => $request->input('career_level_id'),
            'salary_from' => (int) $request->input('salary_from'),
            'salary_to' => (int) $request->input('salary_to'),
            'salary_currency' => $request->input('salary_currency'),
            'hide_salary' => $request->input('hide_salary'),
            'functional_area_id' => $request->input('functional_area_id'),
            'job_type_id' => $request->input('job_type_id'),
            'job_shift_id' => $request->input('job_shift_id'),
            'num_of_positions' => $request->input('num_of_positions'),
            'gender_id' => $request->input('gender_id'),
            'expiry_date' => $request->input('expiry_date'),
            'degree_level_id' => $request->input('degree_level_id'),
            'job_experience_id' => $request->input('job_experience_id'),
            'salary_period_id' => $request->input('salary_period_id'),
        ];
    }

    public function updateFullTextSearch($job)
    {
        $str = '';
        $str .= $job->getCompany('name');
        $str .= ' ' . $job->getCountry('country');
        $str .= ' ' . $job->getState('state');
        $str .= ' ' . $job->getCity('city');
        $str .= ' ' . $job->title;
        $str .= ' ' . $job->description;
        $str .= $job->getJobSkillsStr();
        $str .= ((bool) $job->is_freelance) ? ' freelance remote work from home multiple cities' : '';
        $str .= ' ' . $job->getCareerLevel('career_level');
        $str .= ((bool) $job->hide_salary === false) ? ' ' . $job->salary_from . ' ' . $job->salary_to : '';
        $str .= $job->getSalaryPeriod('salary_period');
        $str .= ' ' . $job->getFunctionalArea('functional_area');
        $str .= ' ' . $job->getJobType('job_type');
        $str .= ' ' . $job->getJobShift('job_shift');
        $str .= ' ' . $job->getGender('gender');
        $str .= ' ' . $job->getDegreeLevel('degree_level');
        $str .= ' ' . $job->getJobExperience('job_experience');
        $job->search = $str;
        $job->update();
    }

    public function setSlug($job)
    {
        return ['slug' => Str::slug($job->title, '-') . '-' . $job->id];
    }

    public function jobRelatedData()
    {
        return $data = [
            'currencies' => DataArrayHelper::currenciesArray(),
            'career_levels' => DataArrayHelper::defaultCareerLevelsArray(),
            'functional_areas' => DataArrayHelper::defaultFunctionalAreasArray(),
            'job_types' => DataArrayHelper::defaultJobTypesArray(),
            'job_shifts' => DataArrayHelper::defaultJobShiftsArray(),
            'genders' => DataArrayHelper::defaultGendersArray(),
            'job_experiences' => DataArrayHelper::defaultJobExperiencesArray(),
            'job_skills' => DataArrayHelper::defaultJobSkillsArray(),
            'degree_levels' => DataArrayHelper::defaultDegreeLevelsArray(),
            'salary_periods' => DataArrayHelper::defaultSalaryPeriodsArray(),
        ];
    }

    public function getJobRelatedData($job)
    {
        $search = '';
        $job_titles = array();
        $company_ids = array();
        $industry_ids = array();
        $job_skill_ids = (array) $job->getJobSkillsArray();
        $functional_area_ids = (array) $job->getFunctionalArea('functional_area_id');
        $country_ids = (array) $job->getCountry('country_id');
        $state_ids = (array) $job->getState('state_id');
        $city_ids = (array) $job->getCity('city_id');
        $is_freelance = $job->is_freelance;
        $career_level_ids = (array) $job->getCareerLevel('career_level_id');
        $job_type_ids = (array) $job->getJobType('job_type_id');
        $job_shift_ids = (array) $job->getJobShift('job_shift_id');
        $gender_ids = (array) $job->getGender('gender_id');
        $degree_level_ids = (array) $job->getDegreeLevel('degree_level_id');
        $job_experience_ids = (array) $job->getJobExperience('job_experience_id');
        $salary_from = 0;
        $salary_to = 0;
        $salary_currency = '';
        $is_featured = 2;
        $order_by = 'id';
        $limit = 4;

        $relatedJobs = $this->fetchJobs($search, $job_titles, $company_ids, $industry_ids, $job_skill_ids, $functional_area_ids, $country_ids, $state_ids, $city_ids, $is_freelance, $career_level_ids, $job_type_ids, $job_shift_ids, $gender_ids, $degree_level_ids, $job_experience_ids, $salary_from, $salary_to, $salary_currency, $is_featured, $order_by, $limit);
        /*         * ***************************************** */

        $seoArray = $this->getSEO((array) $job->functional_area_id, (array) $job->country_id, (array) $job->state_id, (array) $job->city_id, (array) $job->career_level_id, (array) $job->job_type_id, (array) $job->job_shift_id, (array) $job->gender_id, (array) $job->degree_level_id, (array) $job->job_experience_id);
        /*         * ************************************************** */
        $seo = (object) array(
            'seo_title' => $job->title,
            'seo_description' => $seoArray['description'],
            'seo_keywords' => $seoArray['keywords'],
            'seo_other' => ''
        );
        return
            [
                'job_details' => $job,
                'related_jobs' => $relatedJobs,
                'seo' => $seo
            ];
    }

}