<?php

namespace App\Services;

use App\Helpers\APIResponse;
use App\Repositories\Users\Auth\UserRepository;
use App\User;
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
}