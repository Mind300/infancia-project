<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// ======================= Auth User ========================= //
Route::group(['middleware' => 'api'], function () {
    // Authentication
    Route::group(['prefix' => 'auth'], function () {
        Route::post('login', 'Auth\AuthController@login');
        Route::post('register', 'Auth\AuthController@register');
        Route::post('/forgot-password', 'Auth\AuthController@forgetPassowrd')->name('password.email');
        Route::post('/reset-password', 'Auth\AuthController@resetPassword')->name('password.reset');

        // Authentication Must User Login
        Route::group(['middleware' => 'auth:api'], function () {
            Route::post('me', 'Auth\AuthController@me');
            Route::post('auth-role', 'Auth\AuthController@authRole');
            Route::post('refresh', 'Auth\AuthController@refresh');
            Route::post('logout', 'Auth\AuthController@logout');
        });
    });
});

// ======================= Guest User ========================= //
Route::group(['prefix' => 'guest'], function () {
    // Nurseris
    Route::apiResource('nurseries', 'Guest\GuestController');
});

// ===================== SuperAdmin User ====================== //
// Users
Route::group(['middleware' => ['auth:api']], function () {
    Route::group(['prefix' => 'accounts'], function () {
        Route::apiResource('users', 'Users\UsersController');
        Route::post('users/{user}', 'Users\UsersController@update')->name('users.update');
    });
});

// Nurseris
Route::apiResource('nurseries', 'Nurseries\NurseriesController');
Route::apiResource('nursery-album', 'Nurseries\GalleryController');
Route::get('nurseies-albums/{nursery_id}', 'Nurseries\GalleryController@index');
Route::post('nursery-album/add-photo', 'Nurseries\GalleryController@addPhotos');
Route::delete('nursery-album/delete-photo/{album_id}/{media_id}', 'Nurseries\GalleryController@deletePhoto');

// Reviews Resource
Route::apiResource('/reviews', 'Nurseries\ReviewsController');
// Faq Resource
Route::apiResource('/faq', 'Nurseries\FaqController');
Route::apiResource('/schedules', 'Schedules\SchedulesController');
Route::get('/schedules/{class_id}/{day}', 'Schedules\SchedulesController@show');

// Policies
Route::apiResource('policies', 'Nurseries\PolicyController');

// Roles
Route::apiResource('roles', 'Roles\RoleController');

// ============= Nursery User & Related Employees ============= //
Route::group(['middleware' => ['auth:api']], function () {
    // Classes Resource
    Route::apiResource('classes', 'Classes\ClassesController');
    Route::post('classTest', 'Classes\ClassesController@test');
    Route::get('kidsclass/{date}/{class_id}', 'Classes\ClassesController@kidsClassFetch');
    Route::post('absent', 'Classes\ClassesController@absent');

    // Kids Resource
    Route::apiResource('kids', 'Kids\KidsController');
    Route::post('kids/{kid}', 'Kids\KidsController@update')->name('kids.update');
    Route::get('birthday/{accessMonth}', 'Kids\KidsController@birthdayKids');

    // Subjects Resource
    Route::apiResource('subjects', 'Subjects\SubjectsController');
    Route::get('classes-subject/{id}', 'Subjects\SubjectsController@classSubject');
    Route::post('assign-subject', 'Subjects\SubjectsController@assignSubject');
    Route::delete('remove-subject/{assign_id}', 'Subjects\SubjectsController@removeSubject');

    // Meals Resource
    Route::apiResource('meals', 'Meals\MealsController');

    // Follow-Up Resource
    Route::apiResource('followup', 'FollowUp\FollowUpController');
    Route::get('followup/{kid_id}/{date}', 'FollowUp\FollowUpController@show');

    // Newletters Resource
    Route::apiResource('newsletters', 'Newsletters\NewslettersController');
    Route::post('newsletters/likeOrUnlike', 'Newsletters\NewslettersController@likeOrUnlike');

    // Parent Request Resource
    Route::apiResource('/request', 'ParentRequests\RequestsController');
    Route::get('parent/request', 'ParentRequests\RequestsController@indexParent');

    // Parent Request Resource
    Route::apiResource('/parent', 'Parent\ParentController');

    
});

Route::get('/sendmail', 'Test\TestController@sendmail');