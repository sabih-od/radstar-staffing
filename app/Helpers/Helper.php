<?php

use App\Events\NewNotification;
use App\Models\Notification;
use App\Package;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

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

function add_candidate_package () {
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

function emit_pusher_notification ($user_id, $user_type, $icon, $title, $content, $topic, $topic_id) {
    try {
        Log::info('emit_pusher_notification: START');
        $notification = Notification::create([
            'user_id' => $user_id,
            'user_type' => $user_type,
            'icon' => $icon,
            'title' => $title,
            'content' => $content,
            'topic' => $topic,
            'topic_id' => $topic_id,
        ]);

        //emit pusher notification
        event(new NewNotification($notification));

        Log::info('emit_pusher_notification: SUCCESS; ' . $notification->toArray());
        return true;
    } catch (\Exception $e) {
        Log::info('emit_pusher_notification: FAILED; ' . $e->getMessage());
        return false;
    }
}

function send_mail ($from, $to, $subject, $html) {
    // To send HTML mail, the Content-type header must be set
    $headers = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

    // Create email headers
    $headers .= 'From: ' . $from . "\r\n" .
        'Reply-To: ' . $from . "\r\n" .
        'X-Mailer: PHP/' . phpversion();

    // Sending email
    Mail::send([], [], function ($message) use ($to, $subject, $html) {
        $message->to($to)
            ->subject($subject)
            ->setBody($html, 'text/html'); // for HTML rich messages
    });

    if (Mail::failures()) {
        return false;
    }

    return true;
}
