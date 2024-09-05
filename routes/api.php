<?php

use App\Http\Controllers\Controller;
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
        Route::post('check/email', 'Auth\AuthController@checkEmail');
        Route::post('otp/send', 'Auth\AuthController@otpSend');
        Route::post('otp/check', 'Auth\AuthController@otpCheck');
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
    // Nurseries
    Route::apiResource('nurseries', 'Guest\GuestController');
});


// ===================== SuperAdmin User ====================== //
Route::post('nursery-set-status', 'Nurseries\NurseriesController@nurserySetStatus');
Route::group(['middleware' => 'role:superAdmin'], function () {
    // Nursery Approved && Nursery Status
    // Roles
    Route::apiResource('roles', 'Roles\RoleController');
});

// ============== Nursery, Teacher, Parent Users =============== //
// Nurseris
Route::apiResource('nurseries', 'Nurseries\NurseriesController');
Route::get('all-nurseries/{status}', 'Nurseries\NurseriesController@index')->name('nurseries.index');
Route::get('nurseies-users', 'Nurseries\NurseriesController@nurseryUsers');
Route::apiResource('nursery-album', 'Nurseries\GalleryController');
Route::get('nurseies-albums/{nursery_id}', 'Nurseries\GalleryController@index');
Route::post('nursery-album/add-photo', 'Nurseries\GalleryController@addPhotos');
Route::delete('nursery-album/delete-photo/{album_id}/{media_id}', 'Nurseries\GalleryController@deletePhoto');
Route::post('nursery-approved', 'Nurseries\NurseriesController@nurseryApproved');
// Reviews Resource
Route::apiResource('/reviews', 'Nurseries\ReviewsController');
Route::get('/reviews-nursery/{nursery_id}', 'Nurseries\ReviewsController@index');
// Faq Resource
Route::apiResource('/faq', 'Nurseries\FaqController');
Route::apiResource('/schedules', 'Schedules\SchedulesController');
Route::get('/schedules/{class_id}/{day}', 'Schedules\SchedulesController@show');
// Policies
Route::apiResource('policies', 'Nurseries\PolicyController');
// Roles
Route::apiResource('roles', 'Roles\RoleController');
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
Route::get('/chat/{receiver}', 'ParentRequests\MessagesController@chatForm');
Route::post('/chat/send-message', 'ParentRequests\MessagesController@sendMessage');
Route::get('/chat/get-request/{nursery_id}', 'ParentRequests\MessagesController@getChatRequest');
Route::post('/chat/closed-chat/{chat_id}', 'ParentRequests\MessagesController@closedChat');
// Payment Request
Route::apiResource('payment-request', 'PaymentRequest\PaymentRequestController');
Route::post('payment-request/paid/{payment_req_id}', 'PaymentRequest\PaymentRequestController@makrPaied');
// Parent Request Resource
Route::apiResource('/parent', 'Parent\ParentController');
// Users
Route::group(['middleware' => 'permission:users-create, users-read, users-update, users-delete', 'prefix' => 'accounts'], function () {
    Route::apiResource('users', 'Users\UsersController');
    Route::post('users/{user}', 'Users\UsersController@update')->name('users.update');
});;

// ============= Routes Test ============= //
Route::get('/sendmail', [Controller::class, 'demoMail']);
Route::post('/callback', 'Payments\PaymentController@callback');
