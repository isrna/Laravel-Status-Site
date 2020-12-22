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

Route::get('/', 'HomeController@index')->name('home');

Auth::routes(['register' => false, 'verify' => false]);

Route::get('/dashboard', 'DashboardController@index');

Route::get('/upt', 'HomeController@updateModule');
Route::get('/loadincident', 'IncidentController@LoadIncident');
Route::post('/loadincident', 'IncidentController@LoadIncident');

Route::post('/newincident', 'IncidentController@NewIncident');

Route::post('/updateincident', 'IncidentController@UpdateIncident');

Route::post('/newnotice', 'NoticeController@NewNotice');

Route::post('/updatenotice', 'NoticeController@UpdateNotice');
