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

Route::get('/getaccounts', 'CreditController@getAccountList')->name('credit.getaccounts');
Route::post('/updateaccount', 'CreditController@updateAccount')->name('credit.updateaccount');
Route::post('/deleteaccount', 'CreditController@deleteAccount')->name('credit.deleteaccount');
Route::post('/addaccount', 'CreditController@addAccount')->name('credit.addaccount');
Route::get('/account', function () {
    return view('Account');
});


Route::get('/getcards', 'CreditController@getCardList')->name('credit.getcards');
Route::post('/updatecard', 'CreditController@updateCard')->name('credit.updatecard');
Route::post('/deletecard', 'CreditController@deleteCard')->name('credit.deletecard');
Route::post('/addcard', 'CreditController@addCard')->name('credit.addcard');
Route::get('/card', function () {
   return view('creditCard');
});

Route::get('/graph', 'StationController@getGraph')->name('graph.get');
Route::post('/addstation', 'StationController@add')->name('graph.add');
Route::post('/shortest', 'StationController@shortest')->name('graph.shortest');
Route::get('/graphtest', 'StationController@graphtest');
Route::get('/testshort', 'StationController@shortest');