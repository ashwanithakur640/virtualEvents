<?php

use Illuminate\Support\Facades\Route;

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

/* admin routes */
Route::get('admin/login','admin\LoginController@loginForm');
Route::post('admin/login','admin\LoginController@login');
Route::match(['get', 'post'], 'admin/forgot-password', 'admin\LoginController@forgotPassword');
Route::match(['get', 'post'], 'admin/reset-password/{security_token}', 'admin\LoginController@resetPassword');
 

Route::post('auth/registeration','Auth\RegisterController@registeration')->name('registeration');


Route::get('superadmin/login','superadmin\LoginController@loginForm');
Route::post('superadmin/login','superadmin\LoginController@login');
Route::match(['get', 'post'], 'superadmin/forgot-password', 'superadmin\LoginController@forgotPassword');
Route::match(['get', 'post'], 'superadmin/reset-password/{security_token}', 'superadmin\LoginController@resetPassword');

Route::get('{domain}/signin','vendors\LoginController@loginForm');
Route::post('do-login','vendors\LoginController@login')->name('do-login');

Route::match(['get', 'post'], '{domain}/forgot-password', 'vendors\LoginController@forgotPassword');
Route::match(['get', 'post'], '{domain}/reset-password/{security_token}', 'vendors\LoginController@resetPassword');

/*******  participants Signin *******/

Route::get('{domain}/participant-signin','participant\LoginController@loginForm');
Route::post('participant-login','participant\LoginController@login')->name('participant-login');

Route::match(['get', 'post'], '{domain}/participant-forgot-password', 'participant\LoginController@forgotPassword');
Route::match(['get', 'post'], '{domain}/participant-reset-password/{security_token}', 'participant\LoginController@resetPassword');

Route::match(['get', 'post'],'{domain}/verify-account/{security_token}', 'vendors\UsersController@verifyVendorAccount');

/******* end participant signin *******/



/***  ***/


/* employee login */

Route::get('{domain}/employee/login','employee\LoginController@loginForm');
Route::post('{domain}/employee/login','employee\LoginController@login');

Route::match(['get', 'post'], '{domain}/employee/forgot-password', 'employee\LoginController@forgotPassword');
Route::match(['get', 'post'], '{domain}/employee/reset-password/{security_token}', 'employee\LoginController@resetPassword');


/* end employee login */


/** employee routes **/

Route::group(['prefix' => 'employee', 'middleware'=>'auth:employee'], function () {

	Route::get('/dashboard', 'employee\DashboardController@index');

	Route::get('logout', 'employee\LoginController@logout');

	Route::get('edit-profile','employee\DashboardController@editProfile');

	Route::post('update-profile','employee\DashboardController@updateProfile');

	Route::get('change-password','employee\DashboardController@editPassword');
 	Route::post('change-password','employee\DashboardController@updatePassword');

});

/** end employee routes **/


/*  Start super admin routes */

Route::group(['prefix' => 'superadmin', 'middleware'=>'auth:superadmin'], function() {

	Route::get('/conference/{id}', 'SessionHallController@conferenceCall')->name('conference');

	Route::get('/confe/{id}', 'SessionHallController@conferenceCalls')->name('confe');

	Route::get('/conf/{id}', 'SessionHallController@conferenceCallm')->name('conf');

	Route::get('cloud-recording-acquire','ConferencecallController@callRecordingAcquire');
	Route::get('cloud-recording-start','ConferencecallController@callRecordingStart');
	Route::get('cloud-recording-stop','ConferencecallController@callRecordingStop');
	Route::get('cloud-recording-stop-refresh/{id}','ConferencecallController@stopRecordingRefresh');

	Route::get('dashboard','superadmin\DashboardController@index');
	Route::get('change-password','superadmin\DashboardController@editPassword');
	Route::post('change-password','superadmin\DashboardController@updatePassword');
	Route::get('edit-profile','superadmin\DashboardController@editProfile');
	Route::post('update-profile','superadmin\DashboardController@updateProfile');
	Route::get('event-participants','superadmin\EventController@eventParticipants');

    Route::get('event-participants','superadmin\UserController@eventParticipants');

	Route::get('verify-user-account/{id}', 'superadmin\UsersController@verifyUserAccount');
	Route::get('change-state/{id}', 'superadmin\UsersController@changeState')->name('state');	

	Route::get('pay-state/{id}', 'superadmin\UsersController@payState')->name('pay-state');	

	Route::get('/getUser/{id}', 'ConferencecallController@getUsers');

	Route::group(['prefix' => 'vendors'], function () {
		Route::get('/', 'superadmin\UsersController@index');
		Route::get('create-vendor', 'superadmin\UsersController@create');
		Route::post('store-vendor', 'superadmin\UsersController@store');
		Route::get('show-vendor/{id}', 'superadmin\UsersController@show');
		Route::get('edit-vendor/{id}', 'superadmin\UsersController@edit');
		Route::patch('update-vendor/{id}', 'superadmin\UsersController@update');
		Route::delete('delete-vendor/{id}', 'superadmin\UsersController@destroy');
	});

	Route::group(['prefix' => 'customers'], function () {
		Route::get('/', 'superadmin\UsersController@customerIndex');

		Route::get('show-customer/{id}', 'superadmin\UsersController@customerShow');
		Route::get('edit-customer/{id}', 'superadmin\UsersController@customerEdit');
		Route::patch('update-customer/{id}', 'superadmin\UsersController@customerUpdate');
		Route::delete('delete-customer/{id}', 'superadmin\UsersController@customerDestroy');
	});

	Route::group(['prefix' => 'frontend-contents'], function () {
		Route::get('/', 'superadmin\FrontendContentController@index');
		Route::get('create-frontend-content', 'superadmin\FrontendContentController@create');
		Route::post('store-frontend-content', 'superadmin\FrontendContentController@store');
		Route::get('edit-frontend-content/{id}', 'superadmin\FrontendContentController@edit');
		Route::patch('update-frontend-content/{id}', 'superadmin\FrontendContentController@update');
	});

	Route::group(['prefix' => 'categories'], function () {
		Route::get('/', 'superadmin\CategoryController@index');
		Route::get('create-category', 'superadmin\CategoryController@create');
		Route::post('store-category', 'superadmin\CategoryController@store');
		Route::get('edit-category/{id}', 'superadmin\CategoryController@edit');
		Route::patch('update-category/{id}', 'superadmin\CategoryController@update');
		Route::delete('delete-category/{id}', 'superadmin\CategoryController@destroy');
	});

	Route::group(['prefix' => 'events'], function () {
		Route::get('/', 'superadmin\EventController@index');
		Route::get('show-event/{id}', 'superadmin\EventController@eventShow');
		Route::get('create-event', 'superadmin\EventController@create');
		Route::post('store-event', 'superadmin\EventController@store');
		Route::get('edit-event/{id}', 'superadmin\EventController@edit');
		Route::patch('update-event/{id}', 'superadmin\EventController@update');
		Route::delete('delete-event/{id}', 'superadmin\EventController@destroy');
		Route::get('today-events', 'superadmin\EventController@todayEvents');
		Route::get('future-events', 'superadmin\EventController@futureEvents');
		Route::get('weekly-events', 'superadmin\EventController@weeklyEvents');
		Route::get('past-events', 'superadmin\EventController@pastEvents');
		Route::get('hold-events', 'superadmin\EventController@holdEvents');
		Route::get('rescheduled-events', 'superadmin\EventController@rescheduledEvents');
		Route::get('change-state/{id}/{status}', 'superadmin\EventController@changeState')->name('change-status');	
	});

	Route::group(['prefix' => 'faq-report'], function () {
		Route::get('/', 'superadmin\ReportController@index');
		Route::get('reply-answer/{id}', 'superadmin\ReportController@replyAnswer');
		Route::post('update-answer/{id}', 'superadmin\ReportController@updateAnswer');
		Route::get('view/{id}', 'superadmin\ReportController@show');
		Route::delete('delete/{id}', 'superadmin\ReportController@destroy');
	});

	Route::group(['prefix' => 'contact-us-report'], function () {
		Route::get('/', 'superadmin\ReportController@indexContactUs');
		Route::get('reply-answer/{id}', 'superadmin\ReportController@replyContactUsAnswer');
		Route::post('update-answer/{id}', 'superadmin\ReportController@updateContactUsAnswer');
		Route::get('view/{id}', 'superadmin\ReportController@showContactUs');
		Route::delete('delete/{id}', 'superadmin\ReportController@destroyContactUs');
	});

	Route::get('set-payment-amount', 'superadmin\PaymentInfoController@setPaymentAmount');
	Route::post('update-payment-amount', 'superadmin\PaymentInfoController@updatePaymentAmount');

	Route::get('logout', 'superadmin\LoginController@logout');
	
	Route::get('customers-enrolled', 'superadmin\UsersController@customersEnrolled');
	
	Route::get('attendees-report', 'superadmin\UsersController@attendeesReport');

	Route::get('webinar/{id}', 'SessionHallController@webinar')->name('webinar');

	Route::get('session', 'superadmin\SessionController@index');

	Route::get('show-session/{id}', 'superadmin\SessionController@show');

	Route::get('export-chat/{id}', 'superadmin\SessionController@exportChat');

	Route::get('chat-report/{id}', 'superadmin\SessionController@showChat');

	Route::get('session-listing', 'superadmin\SessionController@sessionListing');

    /** feedback  **/

	Route::get('export-feedback/{id}', 'superadmin\SessionController@exportFeedback');

	Route::get('feedback-report/{id}', 'superadmin\SessionController@showFeedback');

	Route::get('feedback-listing', 'superadmin\SessionController@feedbackListing');

	/** end feedback  **/


});





/*  end super admin  */


/***  **/
Route::group(['prefix' => '{prefix}', 'middleware'=>'auth:participant'], function () {

	Route::get('/participant-dashboard', 'participant\DashboardController@index');

	Route::get('logout', 'participant\LoginController@logout');

	Route::get('profile','participant\DashboardController@editProfile');

	Route::post('profile-update','participant\DashboardController@updateProfile');

	Route::get('changing-password','participant\DashboardController@editPassword');

	
	Route::get('my-events','participant\DashboardController@myEvents');

 	Route::post('changing-password','participant\DashboardController@updatePassword');

});
/*   new vendor routes */

Route::group(['prefix' => '{prefix}', 'middleware'=>'auth:vendor'], function() {

	Route::post('add-participant-data','vendors\EventController@participantEventDoc');

	Route::get('dashboard','vendors\DashboardController@index');

	Route::get('/participants', 'vendors\UsersController@index');
	Route::get('create-participant', 'vendors\UsersController@create');
	Route::post('store-participant', 'vendors\UsersController@store');
	Route::get('show-participant/{id}', 'vendors\UsersController@show');
	Route::get('edit-participant/{id}', 'vendors\UsersController@edit');
	Route::patch('update-participant/{id}', 'vendors\UsersController@update');
	Route::delete('delete-participant/{id}', 'vendors\UsersController@destroy');

	Route::get('/employee', 'vendors\UsersController@indexEmp');
	Route::get('create-employee', 'vendors\UsersController@createEmp');
	Route::post('store-employee', 'vendors\UsersController@storeEmp');
	Route::get('show-employee/{id}', 'vendors\UsersController@showEmp');
	Route::get('edit-employee/{id}', 'vendors\UsersController@editEmp');
	Route::patch('update-employee/{id}', 'vendors\UsersController@updateEmp');
	Route::delete('delete-employee/{id}', 'vendors\UsersController@destroyEmp');

	//endUser

	Route::get('/end-user', 'vendors\UsersController@endUser');
	Route::get('show-customer/{id}', 'vendors\UsersController@customerShow');
	Route::get('edit-customer/{id}', 'vendors\UsersController@customerEdit');
	Route::patch('update-customer/{id}', 'vendors\UsersController@customerUpdate');
	Route::delete('delete-customer/{id}', 'vendors\UsersController@customerDestroy');

	Route::get('categories', 'vendors\CategoryController@index');
	Route::get('create-category', 'vendors\CategoryController@create');
	Route::post('store-category', 'vendors\CategoryController@store');
	Route::get('edit-category/{id}', 'vendors\CategoryController@edit');
	Route::patch('update-category/{id}', 'vendors\CategoryController@update');
	Route::delete('delete-category/{id}', 'vendors\CategoryController@destroy');

	Route::get('/events', 'vendors\EventController@index');
	Route::get('show-events/{id}', 'vendors\EventController@eventShow');
	Route::get('create-events', 'vendors\EventController@create');
	Route::post('store-events', 'vendors\EventController@store');
	Route::get('edit-events/{id}', 'vendors\EventController@edit');
	Route::patch('update-events/{id}', 'vendors\EventController@update');
	Route::delete('delete-events/{id}', 'vendors\EventController@destroy');
	Route::get('today-events', 'vendors\EventController@todayEvents');
	Route::get('future-events', 'vendors\EventController@futureEvents');
	Route::get('weekly-events', 'vendors\EventController@weeklyEvents');
	Route::get('past-events', 'vendors\EventController@pastEvents');
	Route::get('hold-events', 'vendors\EventController@holdEvents');
	Route::get('rescheduled-events', 'vendors\EventController@rescheduledEvents');
	Route::get('change-state/{id}/{status}', 'vendors\EventController@changeState')->name('change-status');	

	Route::get('session', 'vendors\SessionController@index');
	Route::get('create-session', 'vendors\SessionController@create');
	Route::post('store-session', 'vendors\SessionController@store');
	Route::get('edit-session/{id}', 'vendors\SessionController@edit');
	Route::patch('update-session/{id}', 'vendors\SessionController@update');
	Route::delete('delete-session/{id}', 'vendors\SessionController@destroy');
	Route::get('show-session/{id}', 'vendors\SessionController@show');

	Route::get('contact-us-report', 'vendors\ReportController@indexContactUs');
	Route::get('faq-report', 'vendors\ReportController@indexContactUs');
	Route::get('logout', 'vendors\LoginController@logout');
	Route::get('edit-profile','vendors\DashboardController@editProfile');
	Route::post('update-profile','vendors\DashboardController@updateProfile');
	Route::get('change-password','vendors\DashboardController@editPassword');
 	Route::post('change-password','vendors\DashboardController@updatePassword');
 	Route::get('webinar/{id}', 'SessionHallController@webinar')->name('webinar');
 	Route::get('confe/{id}', 'SessionHallController@webinar')->name('confe');
 	Route::get('getUser/{id}', 'ConferencecallController@getUser');
 	
 	Route::get('video-content', 'vendors\UsersController@videoContent')->name('video-content');
	
	Route::post('video-content', 'vendors\UsersController@saveContent');

	Route::get('/conference/{id}', 'SessionHallController@conferenceCall')->name('conference');

	//Route::get('/customer-enrolled', 'vendors\UsersController@customersEnrolled');

	Route::post('events/payment', 'vendors\EventController@payment');

	Route::get('customer-enrolled', 'vendors\UsersController@customersEnrolled');

	Route::get('session-listings', 'vendors\SessionController@sessionListing');

	Route::get('chat-reports/{id}', 'vendors\SessionController@showChat');

	Route::get('export-chats/{id}', 'vendors\SessionController@exportChat');

	Route::get('export-feedbacks/{id}', 'vendors\SessionController@exportFeedback');

	Route::get('feedback-reports/{id}', 'vendors\SessionController@showFeedback');

	Route::get('feedback-listings', 'vendors\SessionController@feedbackListing');

	Route::get('attendee-reports', 'vendors\SessionController@attendeesReport');



});





/*  new vendor routes */



// Route::group(['prefix' => 'admin', 'middleware'=>'auth:admin'], function() {

// 	Route::get('/conference/{id}', 'SessionHallController@conferenceCall')->name('conference');

// 	Route::get('/confe/{id}', 'SessionHallController@conferenceCalls')->name('confe');

// 	Route::get('/conf/{id}', 'SessionHallController@conferenceCallm')->name('confe');

// 	Route::get('cloud-recording-acquire','ConferencecallController@callRecordingAcquire');
// 	Route::get('cloud-recording-start','ConferencecallController@callRecordingStart');
// 	Route::get('cloud-recording-stop','ConferencecallController@callRecordingStop');
// 	Route::get('cloud-recording-stop-refresh/{id}','ConferencecallController@stopRecordingRefresh');

// 	Route::get('dashboard','admin\DashboardController@index');
// 	Route::get('change-password','admin\DashboardController@editPassword');
// 	Route::post('change-password','admin\DashboardController@updatePassword');
// 	Route::get('edit-profile','admin\DashboardController@editProfile');
// 	Route::post('update-profile','admin\DashboardController@updateProfile');

// 	Route::get('verify-account/{security_token}', 'admin\UsersController@verifyAccount');
// 	Route::get('verify-user-account/{id}', 'admin\UsersController@verifyUserAccount');
// 	Route::get('change-state/{id}', 'admin\UsersController@changeState')->name('change-state');	

// 	Route::get('/getUser/{id}', 'ConferencecallController@getUsers');

// 	Route::group(['prefix' => 'vendors'], function () {
// 		Route::get('/', 'admin\UsersController@index');
// 		Route::get('create-vendor', 'admin\UsersController@create');
// 		Route::post('store-vendor', 'admin\UsersController@store');
// 		Route::get('show-vendor/{id}', 'admin\UsersController@show');
// 		Route::get('edit-vendor/{id}', 'admin\UsersController@edit');
// 		Route::patch('update-vendor/{id}', 'admin\UsersController@update');
// 		Route::delete('delete-vendor/{id}', 'admin\UsersController@destroy');
// 	});

// 	Route::group(['prefix' => 'customers'], function () {
// 		Route::get('/', 'admin\UsersController@customerIndex');
// 		Route::get('show-customer/{id}', 'admin\UsersController@customerShow');
// 		Route::get('edit-customer/{id}', 'admin\UsersController@customerEdit');
// 		Route::patch('update-customer/{id}', 'admin\UsersController@customerUpdate');
// 		Route::delete('delete-customer/{id}', 'admin\UsersController@customerDestroy');
// 	});

// 	Route::group(['prefix' => 'frontend-contents'], function () {
// 		Route::get('/', 'admin\FrontendContentController@index');
// 		Route::get('create-frontend-content', 'admin\FrontendContentController@create');
// 		Route::post('store-frontend-content', 'admin\FrontendContentController@store');
// 		Route::get('edit-frontend-content/{id}', 'admin\FrontendContentController@edit');
// 		Route::patch('update-frontend-content/{id}', 'admin\FrontendContentController@update');
// 	});

// 	Route::group(['prefix' => 'categories'], function () {
// 		Route::get('/', 'admin\CategoryController@index');
// 		Route::get('create-category', 'admin\CategoryController@create');
// 		Route::post('store-category', 'admin\CategoryController@store');
// 		Route::get('edit-category/{id}', 'admin\CategoryController@edit');
// 		Route::patch('update-category/{id}', 'admin\CategoryController@update');
// 		Route::delete('delete-category/{id}', 'admin\CategoryController@destroy');
// 	});

// 	Route::group(['prefix' => 'events'], function () {
// 		Route::get('/', 'admin\EventController@index');
// 		Route::get('show-event/{id}', 'admin\EventController@eventShow');
// 		Route::get('create-event', 'admin\EventController@create');
// 		Route::post('store-event', 'admin\EventController@store');
// 		Route::get('edit-event/{id}', 'admin\EventController@edit');
// 		Route::patch('update-event/{id}', 'admin\EventController@update');
// 		Route::delete('delete-event/{id}', 'admin\EventController@destroy');
// 		Route::get('today-events', 'admin\EventController@todayEvents');
// 		Route::get('future-events', 'admin\EventController@futureEvents');
// 		Route::get('weekly-events', 'admin\EventController@weeklyEvents');
// 		Route::get('past-events', 'admin\EventController@pastEvents');
// 		Route::get('hold-events', 'admin\EventController@holdEvents');
// 		Route::get('rescheduled-events', 'admin\EventController@rescheduledEvents');
// 		Route::get('change-state/{id}/{status}', 'admin\EventController@changeState')->name('change-status');	
// 	});

// 	Route::group(['prefix' => 'faq-report'], function () {
// 		Route::get('/', 'admin\ReportController@index');
// 		Route::get('reply-answer/{id}', 'admin\ReportController@replyAnswer');
// 		Route::post('update-answer/{id}', 'admin\ReportController@updateAnswer');
// 		Route::get('view/{id}', 'admin\ReportController@show');
// 		Route::delete('delete/{id}', 'admin\ReportController@destroy');
// 	});

// 	Route::group(['prefix' => 'contact-us-report'], function () {
// 		Route::get('/', 'admin\ReportController@indexContactUs');
// 		Route::get('reply-answer/{id}', 'admin\ReportController@replyContactUsAnswer');
// 		Route::post('update-answer/{id}', 'admin\ReportController@updateContactUsAnswer');
// 		Route::get('view/{id}', 'admin\ReportController@showContactUs');
// 		Route::delete('delete/{id}', 'admin\ReportController@destroyContactUs');
// 	});

// 	Route::get('set-payment-amount', 'admin\PaymentInfoController@setPaymentAmount');
// 	Route::post('update-payment-amount', 'admin\PaymentInfoController@updatePaymentAmount');

// 	Route::get('logout', 'admin\LoginController@logout');
// });
/* admin routes */

/* vendor routes */




Route::get('vendor/about-us', 'FrontendController@aboutUs');
Route::get('vendor/privacy-policy', 'FrontendController@privacyPolicy');
Route::get('vendor/contact-us', 'FrontendController@contactUs');
Route::get('vendor/help', 'FrontendController@help');

Route::get('vendor/login','vendor\LoginController@loginForm');
Route::post('vendor/login','vendor\LoginController@login');
Route::get('vendor/register','vendors\LoginController@registerForm');
Route::post('vendors/register','vendors\LoginController@register');
Route::match(['get', 'post'], 'vendor/forgot-password', 'vendor\LoginController@forgotPassword');
Route::match(['get', 'post'], 'reset-password/{security_token}', 'vendor\LoginController@resetPassword');

Route::group(['middleware'=>'auth:vendor'], function() {
	
	Route::get('/getUser/{id}', 'ConferencecallController@getUser');
});

// Route::group(['prefix' => '{prefix}', 'middleware'=>'auth:vendor'], function() {

// 	Route::get('/conference/{id}', 'SessionHallController@conferenceCall')->name('conference');

// 	Route::get('/confe/{id}', 'SessionHallController@conferenceCalls')->name('confe');

// 	Route::get('/conf/{id}', 'SessionHallController@conferenceCallm')->name('confe');

// 	Route::get('cloud-recording-acquire','ConferencecallController@callRecordingAcquire');
// 	Route::get('cloud-recording-start','ConferencecallController@callRecordingStart');
// 	Route::get('cloud-recording-stop','ConferencecallController@callRecordingStop');
// 	Route::get('cloud-recording-stop-refresh/{id}','ConferencecallController@stopRecordingRefresh');

// 	/* recordings */

// 	Route::get('recordings/{cname}','videoController@getConferenceRecording');
// 	Route::post('get-video-recording','videoController@videoViewRecording');
// 	Route::get('get-video-recording/{id}','videoController@videoViewRecording');

// 	Route::get('/getUser/{id}', 'ConferencecallController@getUser');
// 	Route::get('dashboard','vendor\DashboardController@index');
// 	Route::get('change-password','vendor\DashboardController@editPassword');
// 	Route::post('change-password','vendor\DashboardController@updatePassword');
// 	Route::get('edit-profile','vendor\DashboardController@editProfile');
// 	Route::post('update-profile','vendor\DashboardController@updateProfile');
// 	Route::post('add-profile','vendor\DashboardController@addProfile');
//     Route::get('change-state/{id}/{status}', 'vendor\EventController@changeState')->name('change-sttus');	

	

// 	Route::group(['prefix' => 'events'], function () {
// 		Route::get('/', 'vendor\EventController@index');
// 		Route::get('create-event', 'vendor\EventController@create');
// 		Route::post('payment', 'vendor\EventController@payment');
// 		Route::post('store-event', 'vendor\EventController@store');
// 		Route::get('edit-event/{id}', 'vendor\EventController@edit');
// 		Route::patch('update-event/{id}', 'vendor\EventController@update');
// 		Route::delete('delete-event/{id}', 'vendor\EventController@destroy');
// 		Route::get('view-event/{id}', 'vendor\EventController@view');

// 		Route::get('today-events', 'vendor\EventController@todayEvents');
// 		Route::get('future-events', 'vendor\EventController@futureEvents');
// 		Route::get('weekly-events', 'vendor\EventController@weeklyEvents');
// 		Route::get('past-events', 'vendor\EventController@pastEvents');
// 		Route::get('hold-events', 'vendor\EventController@holdEvents');
// 		Route::get('rescheduled-events', 'vendor\EventController@rescheduledEvents');
			
// 	});

// 	Route::group(['prefix' => 'session'], function () {
// 		Route::get('/', 'vendor\SessionController@index');
// 		Route::get('create-session', 'vendor\SessionController@create');
// 		Route::post('store-session', 'vendor\SessionController@store');
// 		Route::get('edit-session/{id}', 'vendor\SessionController@edit');
// 		Route::patch('update-session/{id}', 'vendor\SessionController@update');
// 		Route::delete('delete-session/{id}', 'vendor\SessionController@destroy');
// 	});

// 	Route::group(['prefix' => 'employee'], function () {
// 		Route::get('/', 'vendor\EmployeeController@index');
// 		// Route::get('/', 'vendor\SessionController@index');
// 		 Route::get('create-employee', 'vendor\EmployeeController@create');
// 		 Route::post('store-employee', 'vendor\EmployeeController@store');
// 		// Route::get('edit-session/{id}', 'vendor\SessionController@edit');
// 		// Route::patch('update-session/{id}', 'vendor\SessionController@update');
// 		 Route::delete('delete-employee/{id}', 'vendor\EmployeeController@destroy');
// 	});

// 	Route::get('about-us', 'FrontendController@aboutUs');
// 	Route::get('privacy-policy', 'FrontendController@privacyPolicy');
// 	Route::get('contact-us', 'FrontendController@contactUs');
// 	Route::get('help', 'FrontendController@help');

// 	Route::post('contact-us', 'vendor\FrontendController@store');
// 	Route::post('faq', 'vendor\FrontendController@storeFaq');

// 	Route::get('logout', 'vendor\LoginController@logout');
// });
/* vendor routes */

/* Front end routes */
/*Route::get('/', function () {
    return view('home');
});*/

Auth::routes();

/*Route::get('/login/{social}','HomeController@socialLogin')->where('social','twitter|facebook|linkedin|google');
Route::get('/login/{social}/callback','HomeController@handleProviderCallback')->where('social','twitter|facebook|linkedin|google');*/

Route::get('about-us', 'FrontendController@aboutUs');
Route::get('privacy-policy', 'FrontendController@privacyPolicy');
Route::get('contact-us', 'FrontendController@contactUs');
Route::get('help', 'FrontendController@help');

/*Route::get('/', 'HomeController@index')->name('home');*/
Route::get('/', 'Auth\LoginController@index')->name('home');

Route::group(['middleware'=>'auth:web'], function() {
	
	Route::get('/conference/{id}', 'SessionHallController@conferenceCall')->name('conference');

	Route::get('/confe/{id}', 'SessionHallController@conferenceCalls')->name('confe');

	Route::get('/conf/{id}', 'SessionHallController@conferenceCallm')->name('conf');


	Route::get('cloud-recording-acquire','ConferencecallController@callRecordingAcquire');
	Route::get('cloud-recording-start','ConferencecallController@callRecordingStart');
	Route::get('cloud-recording-stop','ConferencecallController@callRecordingStop');
	Route::get('cloud-recording-stop-refresh/{id}','ConferencecallController@stopRecordingRefresh');


	/* recordings */
	Route::get('recordings/{cname}','videoController@getConferenceRecording');
	Route::post('get-video-recording','videoController@videoViewRecording');
	Route::get('get-video-recording/{id}','videoController@videoViewRecording');


	Route::get('/getUser/{id}', 'ConferencecallController@getUsers');
	Route::get('welcome','DashboardController@index');
	Route::get('edit-profile','DashboardController@editProfile');
	Route::post('update-profile','DashboardController@updateProfile');
	Route::get('change-password','DashboardController@editProfile');
	Route::post('change-password','DashboardController@updatePassword');
	Route::get('my-events','DashboardController@editProfile');

	Route::get('session-hall','SessionHallController@index');
	Route::get('view-session-detail/{id}', 'SessionHallController@viewSessionDetail');

	Route::group(['prefix' => 'exhibit-hall'], function () {
		/*Route::get('/','ExhibitHallController@index');*/
		Route::match(['get', 'post'], '/','ExhibitHallController@index');
		Route::get('view-all','ExhibitHallController@viewAllEvents');
		Route::get('event-detail/{id}', 'ExhibitHallController@eventDatail');
		Route::post('participate-into-event', 'ExhibitHallController@participateIntoEvent');
		Route::get('detail/{id}', 'ExhibitHallController@detail');

		Route::get('vendor-about/{id}', 'ExhibitHallController@vendorAbout');

		Route::get('documents/{id}', 'ExhibitHallController@documents');
		
	});

	Route::group(['prefix' => 'resource-center'], function () {
		Route::get('/','ResourceCenterController@index');
		Route::post('participate-into-event', 'ResourceCenterController@participateIntoEvent');
	});
	
	Route::get('networking-lounge','NetworkingLoungeController@index');
	Route::get('badges','BadgesController@index');

	Route::post('contact-us', 'FrontendController@store');
	Route::post('faq', 'FrontendController@storeFaq');

	Route::get('reviews/{id}', 'SessionHallController@getRatings');
	Route::post('session-hall', 'SessionHallController@saveRating')->name('rate');

	/* Agora */

	Route::get('webinar/{id}', 'SessionHallController@webinar')->name('webinar');


	Route::get('give-rating/{id}', 'SessionHallController@giveRating');
	//pastEvents

	Route::post('past-events','ResourceCenterController@pastEvents')->name('past-events');
		
	
});
/* Front end routes */


// Route::group(['middleware' => ['auth']], function () {

// 	Route::get('/conference/{id}', 'SessionHallController@conferenceCall')->name('conference');

// });



Route::get('change-state/{id}/{status}', 'admin\EventController@changeState')->name('change-status');	

Route::get('cloud-recording-acquire','ConferencecallController@callRecordingAcquire');
Route::get('cloud-recording-start','ConferencecallController@callRecordingStart');
Route::get('cloud-recording-stop','ConferencecallController@callRecordingStop');
Route::get('cloud-recording-stop-refresh/{id}','ConferencecallController@stopRecordingRefresh');


// Route::group(['middleware' => ['auth','web','customer']], function () {
// Route::group(['middleware' => ['auth']], function () {

//Route::group([ 'middleware'=>  ['auth:vendor','auth:web','auth:superadmin' , 'auth:participant' , 'auth:employee'] ], function() {

//Route::group(['middleware' => ['auth:participant', 'auth:vendor', 'auth:superadmin']], function() {


// Route::group(['middleware' => ['auth:participant' OR 'auth:web' OR 'auth:superadmin' OR 'auth:vendor']], function() {
 
// 	Route::get('/web-conference/{id}', 'SessionHallController@webconferenceCalls')->name('web-conference');

// 	Route::get('/getUser/{id}', 'ConferencecallController@getUsers');

// });