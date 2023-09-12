<?php

/* * ******  Job Start ********** */

use App\Http\Controllers\Admin\JobController;
use Illuminate\Support\Facades\Route;

Route::get('list-jobs', array_merge(['uses' => 'Admin\JobController@indexJobs'], $all_users))->name('list.jobs');
Route::get('create-job', array_merge(['uses' => 'Admin\JobController@createJob'], $all_users))->name('create.job');
Route::post('store-job', array_merge(['uses' => 'Admin\JobController@storeJob'], $all_users))->name('store.job');
Route::get('edit-job/{id}', array_merge(['uses' => 'Admin\JobController@editJob'], $all_users))->name('edit.job');
Route::put('update-job/{id}', array_merge(['uses' => 'Admin\JobController@updateJob'], $all_users))->name('update.job');
Route::delete('delete-job', array_merge(['uses' => 'Admin\JobController@deleteJob'], $all_users))->name('delete.job');
Route::get('fetch-jobs', array_merge(['uses' => 'Admin\JobController@fetchJobsData'], $all_users))->name('fetch.data.jobs');
Route::put('make-active-job', array_merge(['uses' => 'Admin\JobController@makeActiveJob'], $all_users))->name('make.active.job');
Route::put('make-not-active-job', array_merge(['uses' => 'Admin\JobController@makeNotActiveJob'], $all_users))->name('make.not.active.job');
Route::put('make-featured-job', array_merge(['uses' => 'Admin\JobController@makeFeaturedJob'], $all_users))->name('make.featured.job');
Route::put('make-not-featured-job', array_merge(['uses' => 'Admin\JobController@makeNotFeaturedJob'], $all_users))->name('make.not.featured.job');


Route::get('fetch-shortlisted-candidates/{job_id}', [JobController::class, 'fetch_shortlisted_candidates'])->name('admin.fetch_shortlisted_candidates');
Route::get('fetch-hired-candidates/{job_id}', [JobController::class, 'fetch_hired_candidates'])->name('admin.fetch_hired_candidates');
Route::get('fetch-rejected-candidates/{job_id}', [JobController::class, 'fetch_rejected_candidates'])->name('admin.fetch_rejected_candidates');
/* * ****** End Job ********** */
