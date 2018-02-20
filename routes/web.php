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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/', 'HomeController@index')->name('home');
Route::get('/home', 'HomeController@index')->name('home');
Route::resource('user','UserController');
Route::resource('reservation','ReservationController');
Route::get('getDataReservations/{fecha}','ReservationController@getDataReservations');
Route::get('getDisponible/{fecha}/{key_css}','ReservationController@getDisponible');
Route::get('getStyles','ReservationController@getStyles');
Route::get('getStyleByKey/{key_css}','ReservationController@getStyleByKey');
Route::get('getHourFinal/{fecha}/{hora_final}','ReservationController@getHourFinal');
Route::get('getHours/{fecha}','ReservationController@getHours');
Route::get('getEquipos/{fecha}/{hora_inicial}/{hora_final}','ReservationController@getEquipos');
Route::get('statusReservation/{reservation_id}/{status}','ReservationController@statusReservation');
Route::get('statusReservation/{reservation_id}/{status}/{comment}','ReservationController@statusReservation');
Route::get('testCorreo','ReservationController@testCorreo');
Route::get('Reservadas','ReservationController@Reservadas');
Route::get('Reservadas/{fecha}','ReservationController@Reservadas');
Route::get('getPendientes','ReservationController@getPendientes');

#social
Route::get('auth/facebook', 'AuthController@redirectToProvider');
Route::get('auth/facebook/callback', 'Auth\AuthController@handleProviderCallback');

Route::get('reservation/create/{reserva_id}','ReservationController@create');

#login facebook
Route::get('loginfb/{userIDFb}/{name}/{status}/{email}','LoginFacebookController@login');
