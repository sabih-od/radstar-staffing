<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SubscriptionService
{
    public function getFields($company)
    {
        return $data = [
            'email' => $company->email,
            'name' => $company->name,
        ];
    }
}