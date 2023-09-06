<?php

use App\Package;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

function add_company_package () {
    if (!($package = Package::find(1)) || !(Auth::guard('company')->check()) || !($company = Auth::guard('company')->user())) {
        return false;
    }

    $now = Carbon::now();
    $company->package_id = $package->id;
    $company->package_start_date = $now;
    $company->package_end_date = $now->addDays($package->package_num_days);
    $company->jobs_quota = $package->package_num_listings;
    $company->availed_jobs_quota = 0;
    $company->payment_method = 'Stripe';
    $company->update();

    return true;
}

function add_job_seeker_package () {
    if (!($package = Package::find(2)) || !(Auth::check()) || !($user = Auth::user())) {
        return false;
    }

    $now = Carbon::now();
    $user->package_id = $package->id;
    $user->package_start_date = $now;
    $user->package_end_date = $now->addDays($package->package_num_days);
    $user->jobs_quota = $package->package_num_listings;
    $user->availed_jobs_quota = 0;
    $user->update();

    return true;
}

function user_has_uploaded_all_documents ($user) {
    if (
        $user->getMedia('drug_test_forms')->count() < 1 ||
        $user->getMedia('education_verification_forms')->count() < 1 ||
        $user->getMedia('employment_history_records')->count() < 1 ||
        $user->getMedia('release_authorization_records')->count() < 1 ||
        $user->getMedia('hipaas')->count() < 1 ||
        $user->getMedia('physician_health_statements')->count() < 1 ||
        $user->getMedia('photo_ids')->count() < 1 ||
        $user->getMedia('us_passports')->count() < 1
    ) {
        return false;
    }

    return true;
}
