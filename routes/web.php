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
    return view('login');
})->name('loginpage');

Route::post('/login',[
    'as' => 'login',
    'uses' => 'UserController@loginUser'
]);

Route::group(['middleware'=>'auth'], function() {
    Route::get('/home',function () {
        return view('home');
    })->name('home');

    Route::get('/admin', [
        'as' => 'admin',
        'uses' => 'UserController@adminroute'
    ]);
    Route::get('/logout',[
       'as' => 'logout',
       'uses' => 'UserController@logout'
    ]);
    Route::post('/save-user',[
       'as' => 'save_user',
       'uses' => 'UserController@newUser'
    ]);

    Route::post('/save-time-in','AttendanceController@saveInTime');
    Route::post('/save-time-out','AttendanceController@saveOutTime');
    Route::get('/attendance-record','AttendanceController@attendanceRecord');
    Route::get('/all-user','UserController@getAll');
    Route::get('/get-userinfo','UserController@getUserInfo');
    Route::get('/is-admin','UserController@isAdmin');
    Route::post('/new-user','UserController@newUser');
    Route::get('/check-attendance','AttendanceController@checkAttendance');
});
