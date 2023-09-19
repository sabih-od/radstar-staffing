<?php

use App\Events\NewNotification;
use App\Models\Notification;
use App\Package;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;

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

function emit_firebase_notification ($user_id, $user_type, $icon, $title, $content, $topic, $topic_id) {
    try {
        Log::info('emit_firebase_notification: START');
        $notification = Notification::create([
            'user_id' => $user_id,
            'user_type' => $user_type,
            'icon' => $icon,
            'title' => $title,
            'content' => $content,
            'topic' => $topic,
            'topic_id' => $topic_id,
        ]);

        //emit firebase notification
        $factory = (new Factory)->withServiceAccount([
            "type" => "service_account",
            "project_id" => "radstar-staffing-96786",
            "private_key_id" => "4ac247d2d945f42280fab026772a89dacf3019e8",
            "private_key" => "-----BEGIN PRIVATE KEY-----\nMIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQDzIJbiUPNOGnYB\n/aPLi6CBywlVANLcODQlpFYe19XUfEHtbM+zCORxUWk9fG14pnFPf+YkF9CYMBmL\n6/CfeMIGZ35n1bc1c1bRt2MUTG0Dp8B45f/uQXEOjp0koRuzd6paIckCiAEPrUkA\n3yzVGjgkfaitilygg3Jr8UeV2IuD7ovAWk+ukOzsVCIx4NCcy+jLUUvrg6DbB19k\nHT/SK72KTMax7+MQXyyOvazUsWM1WP2CA6Ln9LnUuGQ6YKpvua+4dkGm8YDzbR2y\n7DC3ebiZVaUlBDuiKEieJ2cQ9/hcq4nmRRHFMEd/5tXyX00N1EpX58VkTdSiBASD\nRNL1wenfAgMBAAECggEAYJX0SyFUnxUVB3JvhTfJgnaaFPpYSmNLmB6ilesxyBG7\nESrWkm34buokMGiDhtg8kJQjZfhOBn+pTnRjab8L+YZY6cA14daZyYOcqV45OqgE\nZyMcGtdFpj5SwE/+lLv34YmldMt5/HPfWijPAzPA1QJUpeifJqdBqA843JcjybuO\n44KLJ7ASdnlPJQkyIWLYlZRohPiiJmH7EIup4b04MH5UnSZuZYdCD0QenWK7cODB\nuHrZVRu4tWApZ1An9yKBIdUNVo4Bx6dWdyu6/t1BU7ykujIo5DuJ2nlnna5ll/B0\nCzQK54EKgcSU3EfFbu2pq5r7P8vctTUeM4HlJUAYhQKBgQD/YYd7nPmu8tnqgXoR\n6DWRyMzL9d8cFCBxrZvfbpWMV15JPzr/E52K2c43rm3Wb6zQHpoMJJwLz7TuETtQ\nPwXtW3wA79EMI9D0HGVyY10TAj28pDRasmpeC/91SM275WDIlPhw+3Sfh7C2+Ljv\nfrIZ6kxxwGGAYfnIpESv3cCOWwKBgQDzt3TYdi/sV777Rz8ua03eOGzzEUuyzIPo\nmYGkiSMKs6ROUy1xvIC9XbKZX4k9tgWXGkLdU/w9Ojb7eR1qIFD1FP7ItLHYOmWu\n3Fd51TVPc18SrCLZ7UPDckVX6yPOzFq/jRJWruZp5HxwQP6uUXn6zsHtML3R/EjL\npiCIz9mxzQKBgDbQxVboG8PMhq/KONxtHkp7clH5JXmObGRaIlH0F493FVrdgplL\nqY4rMBNNkm/rqolFeEVQ+lmirLBI7JVN4cTP1S8SSqmzal9rVO8XmtvAqGW8TSyG\njURAiQWwqdBB7ONA7o65uo+ffXPYsUFezXW4j83+wC7hWM8TS1cAXxtvAoGBAIQ/\nJm5XI4YRzxY3APfFTkmpQKVc20C4bVOICKsppxQliqDdzakL6qfW8hT7nFMaNEpb\n+7Bx5EutDSzD+cweoQ98RwzN0DtO5OJPuj/oC7eDGTHeqkKq1rx1g19Dvvh2Nz/9\n4tearHkFfOjEu+4HVDNegiic7EPHrBClor3aW3x5AoGBAPZVqiZ7isE38MJDrQt7\nOatYq9aLsjKCBSPe811m5cSUwUADamBBzcL2QLMCmaQvUfOTWHOUqlNteEgT0KmJ\nVQ7ue4g6WrMBIePbG1L8e313dqfg35n3zbDVpxjPrnjAhyj030qkBxYuo0ia2iKf\nmhE1FTDj9mkZaPtT31Ps+Axo\n-----END PRIVATE KEY-----\n",
            "client_email" => "firebase-adminsdk-kmbx5@radstar-staffing-96786.iam.gserviceaccount.com",
            "client_id" => "114623164035150255279",
            "auth_uri" => "https://accounts.google.com/o/oauth2/auth",
            "token_uri" => "https://oauth2.googleapis.com/token",
            "auth_provider_x509_cert_url" => "https://www.googleapis.com/oauth2/v1/certs",
            "client_x509_cert_url" => "https://www.googleapis.com/robot/v1/metadata/x509/firebase-adminsdk-kmbx5%40radstar-staffing-96786.iam.gserviceaccount.com",
            "universe_domain" => "googleapis.com"
        ]);
        $messaging = $factory->createMessaging();
        $message = CloudMessage::new()
//        ->withNotification($notification)
            ->withData(['notification' => $notification])
            ->withTarget('topic', $topic);
        $messaging->send($message);

        Log::info('emit_firebase_notification: SUCCESS; ' . $notification->toArray());
        return true;
    } catch (\Exception $e) {
        Log::info('emit_firebase_notification: FAILED; ' . $e->getMessage());
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
