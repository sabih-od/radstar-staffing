<?php

namespace App\Http\Controllers;

use Auth;
use DB;
use Input;
use File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\UploadedFile;
use ImgUploader;
use Carbon\Carbon;
use Redirect;
use Newsletter;
use App\User;
use App\Subscription;
use App\ApplicantMessage;
use App\Company;
use App\FavouriteCompany;
use App\Gender;
use App\MaritalStatus;
use App\Country;
use App\State;
use App\City;
use App\JobExperience;
use App\JobApply;
use App\CareerLevel;
use App\Industry;
use App\Alert;
use App\FunctionalArea;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Controllers\Controller;
use App\Traits\CommonUserFunctions;
use App\Traits\ProfileSummaryTrait;
use App\Traits\ProfileCvsTrait;
use App\Traits\ProfileProjectsTrait;
use App\Traits\ProfileExperienceTrait;
use App\Traits\ProfileEducationTrait;
use App\Traits\ProfileSkillTrait;
use App\Traits\ProfileLanguageTrait;
use App\Traits\Skills;
use App\Http\Requests\Front\UserFrontFormRequest;
use App\Helpers\DataArrayHelper;

class UserController extends Controller
{

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
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth', ['only' => ['myProfile', 'updateMyProfile', 'viewPublicProfile']]);
        $this->middleware('auth', ['except' => ['showApplicantProfileEducation', 'showApplicantProfileProjects', 'showApplicantProfileExperience', 'showApplicantProfileSkills', 'showApplicantProfileLanguages', 'addToFavouriteCompany', 'removeFromFavouriteCompany']]);
    }

    public function viewPublicProfile($id)
    {

        $user = User::findOrFail($id);
        $profileCv = $user->getDefaultCv();

        //documents
        $drug_test_form_url = $user->getMedia('drug_test_forms')->count() > 0 ? $user->getMedia('drug_test_forms')->first()->getUrl() : null;
        $education_verification_form_url = $user->getMedia('education_verification_forms')->count() > 0 ? $user->getMedia('education_verification_forms')->first()->getUrl() : null;
        $employment_history_record_url = $user->getMedia('employment_history_records')->count() > 0 ? $user->getMedia('employment_history_records')->first()->getUrl() : null;
        $release_authorization_record_url = $user->getMedia('release_authorization_records')->count() > 0 ? $user->getMedia('release_authorization_records')->first()->getUrl() : null;
        $hipaa_url = $user->getMedia('hipaas')->count() > 0 ? $user->getMedia('hipaas')->first()->getUrl() : null;
        $physician_health_statement_url = $user->getMedia('physician_health_statements')->count() > 0 ? $user->getMedia('physician_health_statements')->first()->getUrl() : null;
        $photo_id_url = $user->getMedia('photo_ids')->count() > 0 ? $user->getMedia('photo_ids')->first()->getUrl() : null;
        $us_passport_url = $user->getMedia('us_passports')->count() > 0 ? $user->getMedia('us_passports')->first()->getUrl() : null;

        return view('user.applicant_profile', compact(
            'drug_test_form_url',
            'education_verification_form_url',
            'employment_history_record_url',
            'release_authorization_record_url',
            'hipaa_url',
            'physician_health_statement_url',
            'photo_id_url',
            'us_passport_url',
        ))
                        ->with('user', $user)
                        ->with('profileCv', $profileCv)
                        ->with('page_title', $user->getName())
                        ->with('form_title', 'Contact ' . $user->getName());
    }

    public function myProfile()
    {
        $genders = DataArrayHelper::langGendersArray();
        $maritalStatuses = DataArrayHelper::langMaritalStatusesArray();
        $nationalities = DataArrayHelper::langNationalitiesArray();
        $countries = DataArrayHelper::langCountriesArray();
        $jobExperiences = DataArrayHelper::langJobExperiencesArray();
        $careerLevels = DataArrayHelper::langCareerLevelsArray();
        $industries = DataArrayHelper::langIndustriesArray();
        $functionalAreas = DataArrayHelper::langFunctionalAreasArray();

        $upload_max_filesize = UploadedFile::getMaxFilesize() / (1048576);
        $user = User::findOrFail(Auth::user()->id);

        //documents
        $drug_test_form_url = $user->getMedia('drug_test_forms')->count() > 0 ? $user->getMedia('drug_test_forms')->first()->getUrl() : null;
        $education_verification_form_url = $user->getMedia('education_verification_forms')->count() > 0 ? $user->getMedia('education_verification_forms')->first()->getUrl() : null;
        $employment_history_record_url = $user->getMedia('employment_history_records')->count() > 0 ? $user->getMedia('employment_history_records')->first()->getUrl() : null;
        $release_authorization_record_url = $user->getMedia('release_authorization_records')->count() > 0 ? $user->getMedia('release_authorization_records')->first()->getUrl() : null;
        $hipaa_url = $user->getMedia('hipaas')->count() > 0 ? $user->getMedia('hipaas')->first()->getUrl() : null;
        $physician_health_statement_url = $user->getMedia('physician_health_statements')->count() > 0 ? $user->getMedia('physician_health_statements')->first()->getUrl() : null;
        $photo_id_url = $user->getMedia('photo_ids')->count() > 0 ? $user->getMedia('photo_ids')->first()->getUrl() : null;
        $us_passport_url = $user->getMedia('us_passports')->count() > 0 ? $user->getMedia('us_passports')->first()->getUrl() : null;

        return view('user.edit_profile', compact(
            'drug_test_form_url',
            'education_verification_form_url',
            'employment_history_record_url',
            'release_authorization_record_url',
            'hipaa_url',
            'physician_health_statement_url',
            'photo_id_url',
            'us_passport_url',
        ))
                        ->with('genders', $genders)
                        ->with('maritalStatuses', $maritalStatuses)
                        ->with('nationalities', $nationalities)
                        ->with('countries', $countries)
                        ->with('jobExperiences', $jobExperiences)
                        ->with('careerLevels', $careerLevels)
                        ->with('industries', $industries)
                        ->with('functionalAreas', $functionalAreas)
                        ->with('user', $user)
                        ->with('upload_max_filesize', $upload_max_filesize);
    }

    public function updateMyProfile(UserFrontFormRequest $request)
    {
        $user = User::findOrFail(Auth::user()->id);
        /*         * **************************************** */
        if ($request->hasFile('image')) {
            $is_deleted = $this->deleteUserImage($user->id);
            $image = $request->file('image');
            $fileName = ImgUploader::UploadImage('user_images', $image, $request->input('name'), 300, 300, false);
            $user->image = $fileName;
        }

		if ($request->hasFile('cover_image')) {
			$is_deleted = $this->deleteUserCoverImage($user->id);
            $cover_image = $request->file('cover_image');
            $fileName_cover_image = ImgUploader::UploadImage('user_images', $cover_image, $request->input('name'), 1140, 250, false);
            $user->cover_image = $fileName_cover_image;
        }



        /*         * ************************************** */
        $user->first_name = $request->input('first_name');
        $user->middle_name = $request->input('middle_name');
        $user->last_name = $request->input('last_name');
        /*         * *********************** */
        $user->name = $user->getName();
        /*         * *********************** */
        $user->email = $request->input('email');
        if (!empty($request->input('password'))) {
            $user->password = Hash::make($request->input('password'));
        }
        $user->father_name = $request->input('father_name');
        $user->date_of_birth = $request->input('date_of_birth');
        $user->gender_id = $request->input('gender_id');
        $user->marital_status_id = $request->input('marital_status_id');
        $user->nationality_id = $request->input('nationality_id');
        $user->national_id_card_number = $request->input('national_id_card_number');
        $user->country_id = $request->input('country_id');
        $user->state_id = $request->input('state_id');
        $user->city_id = $request->input('city_id');
        $user->phone = $request->input('phone');
        $user->mobile_num = $request->input('mobile_num');
        $user->job_experience_id = $request->input('job_experience_id');
        $user->career_level_id = $request->input('career_level_id');
        $user->industry_id = $request->input('industry_id');
        $user->functional_area_id = $request->input('functional_area_id');
        $user->current_salary = $request->input('current_salary');
        $user->expected_salary = $request->input('expected_salary');
        $user->salary_currency = $request->input('salary_currency');
        $user->video_link = $request->video_link;
        $user->street_address = $request->input('street_address');
		$user->is_subscribed = $request->input('is_subscribed', 0);

        $user->update();

        $this->updateUserFullTextSearch($user);
		/*************************/
		Subscription::where('email', 'like', $user->email)->delete();
		if((bool)$user->is_subscribed)
		{
			$subscription = new Subscription();
			$subscription->email = $user->email;
			$subscription->name = $user->name;
			$subscription->save();

			/*************************/
			Newsletter::subscribeOrUpdate($subscription->email, ['FNAME'=>$subscription->name]);
			/*************************/
		}
		else
		{
			/*************************/
			Newsletter::unsubscribe($user->email);
			/*************************/
		}

        flash(__('You have updated your profile successfully'))->success();
        return \Redirect::route('my.profile');
    }

    public function addToFavouriteCompany(Request $request, $company_slug)
    {
        $data['company_slug'] = $company_slug;
        $user = Auth::user() ?? Auth::guard('company')->user();
        $data['user_id'] = Auth::user()->id ?? Auth::guard('company')->user()->id;
        $company = Company::where('slug', $company_slug)->firstOrFail();
        $favourite_company = FavouriteCompany::create($data);
        flash(__('Company has been added in favorites list'))->success();

        $pusher_emitter_res = emit_pusher_notification(
            $company->id,
            'employer',
            'icon',
            'New Follower',
            ($user->name ?? '') . ' has started following you.',
            'favourite_company',
            $favourite_company->id
        );

        return \Redirect::route('company.detail', $company_slug);
    }

    public function removeFromFavouriteCompany(Request $request, $company_slug)
    {
        $user_id = Auth::user()->id ?? Auth::guard('company')->user()->id;
        FavouriteCompany::where('company_slug', 'like', $company_slug)->where('user_id', $user_id)->delete();

        flash(__('Company has been removed from favorites list'))->success();
        return \Redirect::route('company.detail', $company_slug);
    }

    public function myFollowings()
    {
        $user = User::findOrFail(Auth::user()->id);
        $companiesSlugArray = $user->getFollowingCompaniesSlugArray();
        $companies = Company::whereIn('slug', $companiesSlugArray)->get();

        return view('user.following_companies')
                        ->with('user', $user)
                        ->with('companies', $companies);
    }

    public function myMessages()
    {
        $user = User::findOrFail(Auth::user()->id);
        $messages = ApplicantMessage::where('user_id', '=', $user->id)
                ->orderBy('is_read', 'asc')
                ->orderBy('created_at', 'desc')
                ->get();

        return view('user.applicant_messages')
                        ->with('user', $user)
                        ->with('messages', $messages);
    }

    public function applicantMessageDetail($message_id)
    {
        $user = User::findOrFail(Auth::user()->id);
        $message = ApplicantMessage::findOrFail($message_id);
        $message->update(['is_read' => 1]);

        return view('user.applicant_message_detail')
                        ->with('user', $user)
                        ->with('message', $message);
    }

    public function myAlerts()
    {
        $alerts = Alert::where('email', Auth::user()->email)
            ->orderBy('created_at', 'desc')
            ->get();
        //dd($alerts);
        return view('user.applicant_alerts')
            ->with('alerts', $alerts);
    }
    public function delete_alert($id)
    {
        $alert = Alert::findOrFail($id);
        $alert->delete();
        $arr = array('msg' => 'A Alert has been successfully deleted. ', 'status' => true);
        return Response()->json($arr);
    }
    public function ResumeFetch($id) {
        $user = User::findOrFail($id);
         $profileCv = $user->getDefaultCv();
         return view('user.resume')
                         ->with('user', $user)
                         ->with('profileCv', $profileCv)
                         ->with('page_title', $user->getName())
                         ->with('form_title', 'Contact ' . $user->getName());
    }

    public function uploadDocuments (Request $request)
    {
        $user = User::find(Auth::id());

        $this->updateMedia($request, $user, 'drug_test_form');
        $this->updateMedia($request, $user, 'education_verification_form');
        $this->updateMedia($request, $user, 'employment_history_record');
        $this->updateMedia($request, $user, 'release_authorization_record');
        $this->updateMedia($request, $user, 'hipaa');
        $this->updateMedia($request, $user, 'physician_health_statement');
        $this->updateMedia($request, $user, 'photo_id');
        $this->updateMedia($request, $user, 'us_passport');

        flash(__('Documents have been uploaded successfully!'))->success();
        return \Redirect::to(route('my.profile') . '#cvs');
//        return \Redirect::route('my.profile');
    }

    public function updateMedia($request, $user, $media_name) {
        if ($request->has($media_name)) {
            $user->clearMediaCollection($media_name . 's');
            $user->addMediaFromRequest($media_name)->toMediaCollection($media_name . 's');
        }
    }

}
