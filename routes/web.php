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
});

Route::get('/info', function () {
    return view('phpinfo');
});

Route::get('/test', function () {
    return view('dbtest');
});

Route::post('/validate', 'LoginController@login')->name('admin.login');
Route::get('/validation', function () {
    return view('validation');
})->name('admin.validation');
Route::post('/validatequestion', 'LoginController@validatequestion')->name('admin.validatequestion');
Route::post('/question', 'LoginController@getQuestion')->name('admin.getquestion');
Route::get('/transaction', function () {
    return view('transaction');
})->name('admin.transaction');

Route::get('/login', function () {
    return view('login');
});

Route::post('/questionwithblock', 'LoginController@questionWithBlock')->name('admin.questionwithblock');