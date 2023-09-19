<?php







/*



  |--------------------------------------------------------------------------



  | Web Routes



  |--------------------------------------------------------------------------



  |



  | Here is where you can register web routes for your application. These



  | routes are loaded by the RouteServiceProvider within a group which



  | contains the "web" middleware group. Now create something great!



  |



 */


use App\Package;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;

$real_path = realpath(__DIR__) . DIRECTORY_SEPARATOR . 'front_routes' . DIRECTORY_SEPARATOR;



/* * ******** IndexController ************ */



Route::get('/', 'IndexController@index')->name('index');

Route::get('/check-time', 'IndexController@checkTime')->name('check-time');

Route::post('set-locale', 'IndexController@setLocale')->name('set.locale');



/* * ******** HomeController ************ */



Route::get('home', 'HomeController@index')->name('home');



/* * ******** TypeAheadController ******* */



Route::get('typeahead-currency_codes', 'TypeAheadController@typeAheadCurrencyCodes')->name('typeahead.currency_codes');



/* * ******** FaqController ******* */



Route::get('faq', 'FaqController@index')->name('faq');



/* * ******** CronController ******* */



Route::get('check-package-validity', 'CronController@checkPackageValidity');



/* * ******** Verification ******* */



Route::get('email-verification/error', 'Auth\RegisterController@getVerificationError')->name('email-verification.error');



Route::get('email-verification/check/{token}', 'Auth\RegisterController@getVerification')->name('email-verification.check');



Route::get('company-email-verification/error', 'Company\Auth\RegisterController@getVerificationError')->name('company.email-verification.error');



Route::get('company-email-verification/check/{token}', 'Company\Auth\RegisterController@getVerification')->name('company.email-verification.check');



/* * ***************************** */



// Sociallite Start



// OAuth Routes



Route::get('login/jobseeker/{provider}', 'Auth\LoginController@redirectToProvider');



Route::get('login/jobseeker/{provider}/callback', 'Auth\LoginController@handleProviderCallback');



Route::get('login/employer/{provider}', 'Company\Auth\LoginController@redirectToProvider');



Route::get('login/employer/{provider}/callback', 'Company\Auth\LoginController@handleProviderCallback');



// Sociallite End



/* * ***************************** */



Route::post('tinymce-image_upload-front', 'TinyMceController@uploadImage')->name('tinymce.image_upload.front');







Route::get('cronjob/send-alerts', 'AlertCronController@index')->name('send-alerts');







Route::post('subscribe-newsletter', 'SubscriptionController@getSubscription')->name('subscribe.newsletter');







/* * ******** OrderController ************ */



include_once($real_path . 'order.php');



/* * ******** CmsController ************ */



include_once($real_path . 'cms.php');



/* * ******** JobController ************ */



include_once($real_path . 'job.php');



/* * ******** ContactController ************ */



include_once($real_path . 'contact.php');



/* * ******** CompanyController ************ */



include_once($real_path . 'company.php');



/* * ******** AjaxController ************ */



include_once($real_path . 'ajax.php');



/* * ******** UserController ************ */



include_once($real_path . 'site_user.php');



/* * ******** User Auth ************ */



Auth::routes();



/* * ******** Company Auth ************ */



include_once($real_path . 'company_auth.php');



/* * ******** Admin Auth ************ */



include_once($real_path . 'admin_auth.php');











Route::get('blog', 'BlogController@index')->name('blogs');



Route::get('blog/search', 'BlogController@search')->name('blog-search');



Route::get('blog/{slug}', 'BlogController@details')->name('blog-detail');



Route::get('/blog/category/{blog}', 'BlogController@categories')->name('blog-category');



Route::get('/company-change-message-status', 'CompanyMessagesController@change_message_status')->name('company-change-message-status');

Route::get('/seeker-change-message-status', 'Job\SeekerSendController@change_message_status')->name('seeker-change-message-status');



Route::get('/sitemap', 'SitemapController@index');



Route::get('/sitemap/companies', 'SitemapController@companies');





Route::get('job8', 'Job8Controller@job8')->name('job8');

Route::get('cronjob/delete-jobs', 'Job8Controller@delete_jobs')->name('delete-jobs');

Route::get('cronjob/amend-jobs', 'Job8Controller@amend_jobs')->name('amend-jobs');

Route::get('cronjob/set-count-industry', 'Job8Controller@set_count_industry')->name('set_count_industry');

Route::get('cronjob/set-total-count', 'Job8Controller@set_total_count')->name('set_total_count');

Route::get('cronjob/set-total-country', 'Job8Controller@set_count_country')->name('set_count_country');

Route::get('cronjob/set-total-companies', 'Job8Controller@set_count_company')->name('set_count_company');

Route::get('cronjob/set-total-jobType', 'Job8Controller@set_count_jobType')->name('set_count_jobType');

Route::get('cronjob/remove-duplicates', 'Job8Controller@remove_duplicates')->name('remove_duplicates');

Route::get('cronjob/set-count-company', 'Job8Controller@set_count_company')->name('set_count_company');

Route::get('cronjob/remove-duplicate-companies', 'Job8Controller@remove_duplicates')->name('remove-duplicate-companies');

Route::get('cronjob/recover-companies', 'Job8Controller@recover_companies')->name('recover-companies');

Route::get('cronjob/recover-jobs', 'Job8Controller@recover_jobs')->name('recover-jobs');



Route::get('set-location', 'Job8Controller@set_location')->name('set_location');



Route::post('ajax_upload_file','FilerController::class@upload')->name('filer.image-upload');

Route::post('ajax_remove_file','FilerController::class@fileDestroy')->name('filer.image-remove');





Route::get('/clear-cache', function () {



  $exitCode = Artisan::call('config:clear');



  $exitCode = Artisan::call('cache:clear');



  $exitCode = Artisan::call('config:cache');



  return 'DONE'; //Return anything



});








//use App\Traits\CompanyPackageTrait;


Route::get('temp', function () {
//    $factory = (new Factory)->withServiceAccount([
//        "type" => "service_account",
//        "project_id" => "radstar-staffing-96786",
//        "private_key_id" => "4ac247d2d945f42280fab026772a89dacf3019e8",
//        "private_key" => "-----BEGIN PRIVATE KEY-----\nMIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQDzIJbiUPNOGnYB\n/aPLi6CBywlVANLcODQlpFYe19XUfEHtbM+zCORxUWk9fG14pnFPf+YkF9CYMBmL\n6/CfeMIGZ35n1bc1c1bRt2MUTG0Dp8B45f/uQXEOjp0koRuzd6paIckCiAEPrUkA\n3yzVGjgkfaitilygg3Jr8UeV2IuD7ovAWk+ukOzsVCIx4NCcy+jLUUvrg6DbB19k\nHT/SK72KTMax7+MQXyyOvazUsWM1WP2CA6Ln9LnUuGQ6YKpvua+4dkGm8YDzbR2y\n7DC3ebiZVaUlBDuiKEieJ2cQ9/hcq4nmRRHFMEd/5tXyX00N1EpX58VkTdSiBASD\nRNL1wenfAgMBAAECggEAYJX0SyFUnxUVB3JvhTfJgnaaFPpYSmNLmB6ilesxyBG7\nESrWkm34buokMGiDhtg8kJQjZfhOBn+pTnRjab8L+YZY6cA14daZyYOcqV45OqgE\nZyMcGtdFpj5SwE/+lLv34YmldMt5/HPfWijPAzPA1QJUpeifJqdBqA843JcjybuO\n44KLJ7ASdnlPJQkyIWLYlZRohPiiJmH7EIup4b04MH5UnSZuZYdCD0QenWK7cODB\nuHrZVRu4tWApZ1An9yKBIdUNVo4Bx6dWdyu6/t1BU7ykujIo5DuJ2nlnna5ll/B0\nCzQK54EKgcSU3EfFbu2pq5r7P8vctTUeM4HlJUAYhQKBgQD/YYd7nPmu8tnqgXoR\n6DWRyMzL9d8cFCBxrZvfbpWMV15JPzr/E52K2c43rm3Wb6zQHpoMJJwLz7TuETtQ\nPwXtW3wA79EMI9D0HGVyY10TAj28pDRasmpeC/91SM275WDIlPhw+3Sfh7C2+Ljv\nfrIZ6kxxwGGAYfnIpESv3cCOWwKBgQDzt3TYdi/sV777Rz8ua03eOGzzEUuyzIPo\nmYGkiSMKs6ROUy1xvIC9XbKZX4k9tgWXGkLdU/w9Ojb7eR1qIFD1FP7ItLHYOmWu\n3Fd51TVPc18SrCLZ7UPDckVX6yPOzFq/jRJWruZp5HxwQP6uUXn6zsHtML3R/EjL\npiCIz9mxzQKBgDbQxVboG8PMhq/KONxtHkp7clH5JXmObGRaIlH0F493FVrdgplL\nqY4rMBNNkm/rqolFeEVQ+lmirLBI7JVN4cTP1S8SSqmzal9rVO8XmtvAqGW8TSyG\njURAiQWwqdBB7ONA7o65uo+ffXPYsUFezXW4j83+wC7hWM8TS1cAXxtvAoGBAIQ/\nJm5XI4YRzxY3APfFTkmpQKVc20C4bVOICKsppxQliqDdzakL6qfW8hT7nFMaNEpb\n+7Bx5EutDSzD+cweoQ98RwzN0DtO5OJPuj/oC7eDGTHeqkKq1rx1g19Dvvh2Nz/9\n4tearHkFfOjEu+4HVDNegiic7EPHrBClor3aW3x5AoGBAPZVqiZ7isE38MJDrQt7\nOatYq9aLsjKCBSPe811m5cSUwUADamBBzcL2QLMCmaQvUfOTWHOUqlNteEgT0KmJ\nVQ7ue4g6WrMBIePbG1L8e313dqfg35n3zbDVpxjPrnjAhyj030qkBxYuo0ia2iKf\nmhE1FTDj9mkZaPtT31Ps+Axo\n-----END PRIVATE KEY-----\n",
//        "client_email" => "firebase-adminsdk-kmbx5@radstar-staffing-96786.iam.gserviceaccount.com",
//        "client_id" => "114623164035150255279",
//        "auth_uri" => "https://accounts.google.com/o/oauth2/auth",
//        "token_uri" => "https://oauth2.googleapis.com/token",
//        "auth_provider_x509_cert_url" => "https://www.googleapis.com/oauth2/v1/certs",
//        "client_x509_cert_url" => "https://www.googleapis.com/robot/v1/metadata/x509/firebase-adminsdk-kmbx5%40radstar-staffing-96786.iam.gserviceaccount.com",
//        "universe_domain" => "googleapis.com"
//    ]);
//    $messaging = $factory->createMessaging();
//    $message = CloudMessage::new()
////        ->withNotification(\App\Models\Notification::find(10)->toArray())
//        ->withData(['key' => 'value'])
//        ->withTarget('topic', 'test');
//    dd($messaging->send($message));
});
