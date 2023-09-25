<?php

namespace App\Services\Users;

use App\Helpers\DataArrayHelper;
use App\ProfileCv;
use App\Repositories\Companies\Subscription\SubscriptionRepository;
use App\Repositories\Users\Auth\UserRepository;
use App\Services\SubscriptionService;
use App\Traits\CommonUserFunctions;
use App\Traits\ProfileCvsTrait;
use App\Traits\ProfileEducationTrait;
use App\Traits\ProfileExperienceTrait;
use App\Traits\ProfileLanguageTrait;
use App\Traits\ProfileProjectsTrait;
use App\Traits\ProfileSkillTrait;
use App\Traits\ProfileSummaryTrait;
use App\Traits\Skills;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use App\Traits\PHPCustomMail;
use ImgUploader;
use Newsletter;



class UserProfileService
{
    use PHPCustomMail;
    use CommonUserFunctions;
    use ProfileSummaryTrait;
    use ProfileCvsTrait;
    use ProfileProjectsTrait;
    use ProfileExperienceTrait;
    use ProfileEducationTrait;
    use ProfileSkillTrait;
    use ProfileLanguageTrait;
    use Skills;

    /**
     * @var UserRepository
     */

    protected $userRepository;
    protected $subscriptionService;
    protected $subscriptionRepository;

    /**
     * @param UserRepository $userRepository
     */
    public function __construct(
        UserRepository $userRepository,
        SubscriptionService $subscriptionService,
        SubscriptionRepository $subscriptionRepository
    )
    {
        $this->userRepository = $userRepository;
        $this->subscriptionService = $subscriptionService;
        $this->subscriptionRepository = $subscriptionRepository;
    }

    public function profile($cadidate){

        try {

            $genders = DataArrayHelper::langGendersArray();
            $maritalStatuses = DataArrayHelper::langMaritalStatusesArray();
            $nationalities = DataArrayHelper::langNationalitiesArray();
            $countries = DataArrayHelper::langCountriesArray();
            $jobExperiences = DataArrayHelper::langJobExperiencesArray();
            $careerLevels = DataArrayHelper::langCareerLevelsArray();
            $industries = DataArrayHelper::langIndustriesArray();
            $functionalAreas = DataArrayHelper::langFunctionalAreasArray();

            $upload_max_filesize = UploadedFile::getMaxFilesize() / (1048576);
            $user = $this->userRepository->find($cadidate->id);

            //documents
            $drug_test_form_url = $user->getMedia('drug_test_forms')->count() > 0 ? $user->getMedia('drug_test_forms')->first()->getUrl() : null;
            $education_verification_form_url = $user->getMedia('education_verification_forms')->count() > 0 ? $user->getMedia('education_verification_forms')->first()->getUrl() : null;
            $employment_history_record_url = $user->getMedia('employment_history_records')->count() > 0 ? $user->getMedia('employment_history_records')->first()->getUrl() : null;
            $release_authorization_record_url = $user->getMedia('release_authorization_records')->count() > 0 ? $user->getMedia('release_authorization_records')->first()->getUrl() : null;
            $hipaa_url = $user->getMedia('hipaas')->count() > 0 ? $user->getMedia('hipaas')->first()->getUrl() : null;
            $physician_health_statement_url = $user->getMedia('physician_health_statements')->count() > 0 ? $user->getMedia('physician_health_statements')->first()->getUrl() : null;
            $photo_id_url = $user->getMedia('photo_ids')->count() > 0 ? $user->getMedia('photo_ids')->first()->getUrl() : null;
            $us_passport_url = $user->getMedia('us_passports')->count() > 0 ? $user->getMedia('us_passports')->first()->getUrl() : null;

            return [
                'user' => $user,
                'genders' => $genders,
                'maritalStatuses' => $maritalStatuses,
                'nationalities' => $nationalities,
                'countries' => $countries,
                'jobExperiences' => $jobExperiences,
                'careerLevels' => $careerLevels,
                'industries' => $industries,
                'drug_test_form_url' => $drug_test_form_url,
                'functionalAreas' => $functionalAreas,
                'education_verification_form_url' => $education_verification_form_url,
                'employment_history_record_url' => $employment_history_record_url,
                'release_authorization_record_url' => $release_authorization_record_url,
                'hipaa_url' => $hipaa_url,
                'physician_health_statement_url' => $physician_health_statement_url,
                'photo_id_url' => $photo_id_url,
                'us_passport_url' => $us_passport_url,
                'upload_max_filesize' => $upload_max_filesize,
            ];
        } catch (Exception $e){
            return $e;
        }
    }

    public function updateProfile($requestData, $candidate)
    {
        try {
            if ($requestData->hasFile('image')) {
                $this->deleteUserImage($candidate->id);
                $image = $requestData->file('image');
                $fileName = ImgUploader::UploadImage('user_images', $image, $requestData->input('name'), 300, 300, false);
            }

            if ($requestData->hasFile('cover_image')) {
                $this->deleteUserCoverImage($candidate->id);
                $cover_image = $requestData->file('cover_image');
                $fileName_cover_image = ImgUploader::UploadImage('user_images', $cover_image, $requestData->input('name'), 1140, 250, false);
            }


            $data =
                [
                    'first_name' => $requestData->first_name,
                    'middle_name' => $requestData->middle_name,
                    'last_name' => $requestData->last_name,
                    'name' => $requestData->first_name.' '.$requestData->middle_name.' '.$requestData->last_name,
                    'password' => !empty($requestData->input('password')) ? Hash::make($requestData->input('password')) : $candidate->password,
                    'father_name' => $requestData->father_name,
                    'date_of_birth' => $requestData->date_of_birth,
                    'gender_id' => $requestData->gender_id,
                    'marital_status_id' => $requestData->marital_status_id,
                    'nationality_id' => $requestData->nationality_id,
                    'national_id_card_number' => $requestData->national_id_card_number,
                    'no_of_offices' => $requestData->no_of_offices,
                    'country_id' => $requestData->country_id,
                    'state_id' => $requestData->state_id,
                    'city_id' => $requestData->city_id,
                    'phone' => $requestData->phone,
                    'mobile_num' => $requestData->mobile_num,
                    'job_experience_id' => $requestData->job_experience_id,
                    'career_level_id' => $requestData->career_level_id,
                    'industry_id' => $requestData->industry_id,
                    'functional_area_id' => $requestData->functional_area_id,
                    'current_salary' => $requestData->current_salary,
                    'expected_salary' => $requestData->expected_salary,
                    'salary_currency' => $requestData->salary_currency,
                    'street_address' => $requestData->street_address,
                    'video_link' => $requestData->video_link,
                    'is_subscribed' => $requestData->is_subscribed,
                    'image' => isset($fileName) ? $fileName : '',
                    'cover_image' => isset($fileName_cover_image) ? $fileName_cover_image : '',
                ];

            $updateCandidate = $this->userRepository->update($data, $candidate->id);
            $this->updateUserFullTextSearch($updateCandidate);

            if((bool)$updateCandidate->is_subscribed) {
                $subscriptionData = $this->subscriptionService->getFields($updateCandidate);
                $subscription = $this->subscriptionRepository->create($subscriptionData);
                Newsletter::subscribeOrUpdate($subscription->email, ['FNAME'=>$subscription->name]);
            } else {
                Newsletter::unsubscribe($updateCandidate->email);
            }

            return $updateCandidate;

        } catch(\Exception $e){
            return $e;
        }
    }

    public function updateProfileSummary($candidate, $summary)
    {
        try {
            $updateSummary = $this->userRepository->summaryUpdate($candidate->id,$summary);

            return $updateSummary;
        } catch(\Exception $e){
            return $e;
        }
    }

    public function ProfileCv($candidate)
    {
        try {
            $profileCv = $this->userRepository->profileCvList($candidate->id);

            return $profileCv;
        } catch(\Exception $e){
            return $e;
        }

    }

    public function addProfileCv($candidate, $request)
    {
        try {
            $profileCv = $this->userRepository->profileCv()->first();
            if (!$profileCv) {
                $profileCv = new ProfileCv();
            }
            $profileCv = $this->assignValues($profileCv, $request, $candidate->id);
            $profileCv->save();

            return $profileCv;
        } catch(\Exception $e){
            return $e;
        }

    }

    public function updateProfileCv($candidate, $request)
    {
        try {

//            $profileCv = ProfileCv::find($candidate->id);
//            $profileCv = $this->assignValues($profileCv, $request, $candidate->id);
//            $profileCv->update();
//
//            return $profileCv;
        } catch(\Exception $e){
            return $e;
        }

    }
}