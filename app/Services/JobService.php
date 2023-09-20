<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Helpers\DataArrayHelper;


class JobService
{

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
            'companies' => DataArrayHelper::companiesArray(),
            'countries' => DataArrayHelper::defaultCountriesArray(),
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

}