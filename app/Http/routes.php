<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

// Route::get('/', function () {
//   Log::info("INFO",['logging'=>true]);
//   if(!empty(Auth::user())) Log::warning("WARNING",['user'=>["name"=>Auth::user()->name, "id"=>Auth::user()->id]]);
//     //return Redirect::to('/home');
//     //dd(Auth::user());
//     return view('welcome');
// });

Route::auth();
Route::get('/',                                     'EventController@events');
Route::get('event/{id}',                            'EventController@event');
Route::get('stand/{event_id}/{stand_id_internal}',  'EventController@book');

Route::get( 'API/event/list',            'API\EventsController@index');
Route::post('API/event/reserve',         'API\EventsController@reserve');
Route::get( 'API/event/{id}',            'API\EventsController@event')->where('id', '[0-9]+');

// Same controller for every upload
Route::post('upload/{target}',                      'UploadController@receive');

// Control panel (admin area)  not implemented
Route::get('panel', 'ControlPanelController@index');
