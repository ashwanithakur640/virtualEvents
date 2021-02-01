<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::post('end-conference-time','ConferencecallController@endConferenceTime');
Route::post('end-conference-all','ConferencecallController@endAllConference');
Route::post('recording-done','ConferencecallController@RecordingDone');
Route::post('merge-videos','ConferencecallController@mergeVideos');
Route::post('log-conference-joinee','ConferenceController@addConfLog');
Route::post('add-presenter','ConferencecallController@addPresenter');
Route::post('add-recording','ConferencecallController@addRecordingLog');
Route::post('add-message','ConferencecallController@saveMessage');
Route::post('get-message','ConferencecallController@getOldMessage');
Route::post('modrator-controls','ConferencecallController@saveModratorDetails');
Route::post('updatevideo-layout','ConferencecallController@updateRecordingLayout');

