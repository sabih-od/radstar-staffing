<?php

namespace App\Services;

use App\Helpers\APIResponse;
use App\Helpers\DataArrayHelper;
use App\Repositories\Users\Auth\UserRepository;
use App\User;
use Illuminate\Http\UploadedFile;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Traits\PHPCustomMail;



class UserService
{
    use PHPCustomMail;

    /**
     * @var UserRepository
     */

    protected $userRepository;

    /**
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function authenticateUser(array $credentials)
    {
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('MyApp')->accessToken;
            $selectedFields = $user->only('first_name', 'email', 'phone', 'date_of_birth');
            return [
                'success' => true,
                'message' => 'Logged In Successfully',
                'data' => $selectedFields,
                'access_token' => $token
            ];
        } else {
            return [
                'success' => false,
                'error' => 'Unauthorized',
            ];
        }
    }

    public function logoutUser($user)
    {
        $user->token()->revoke();
        return [
            'success' => true,
            'message' => 'Logout successfully',
        ];
    }

    public function resetEmail($email, $type = null)
    {
        try {
            $user = $this->userRepository->findByField('email', $email)->first();

            if (!$user) {
                throw new \Exception('User Not Found');
            }
            $encryptedId = encrypt($user->id);

            $otp = (string)rand(1111, 9999);
            $user = $this->userRepository->storeOTP($user->id, $otp);

            $to = $user->email;
            $from = 'noreply@gmail.com';
            $subject = "Forgot Password";
            $message = view('vendor.user.otp-email', compact('otp', 'encryptedId', 'type'));

            $this->customMail($from, $to, $subject, $message);

            return true;

        } catch (\Exception $e) {
            Log::error('Error in ResetEmail: ' . $e->getMessage());
            return $e;
        }
    }

    public function optExist($userId)
    {
        return $this->userRepository->findByField('id', $userId)->first();
    }

    public function verifyOtp($email, $otp)
    {

        try {
            $user = $this->userRepository->findByField('email', $email)->first();
            if ($user) {
                $encryptedId = encrypt($user->id);
                if ($user->otp == $otp || $user->otp_expire > Carbon::now()) {
                    $this->userRepository->resetOTP($user->id, null, null);
                    return APIResponse::success('OTP successfully verify.', $encryptedId, 200);
                } else {
                    throw new \Exception('Otp is expired please regenerate');
                }
            }
        } catch (\Exception $e) {
            Log::error('Error in ResetEmail: ' . $e->getMessage());
            return $e;
        }
    }


    public function resetPassword($email, $password)
    {
        $user = $this->userRepository->findByField('email', $email)->first();
        if ($user) {
            $userPassword = Hash::make($password);
            $this->userRepository->resetPassword($user->id, $userPassword);

            return APIResponse::success('Password reset Successfully', [], 200);
        } else {
            throw new \Exception('User does not exist');
        }
    }

    public function profile($userId){

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
            $user = User::findOrFail($userId);

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
}