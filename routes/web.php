<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MessageGroupController;

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

// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/conversation/{userId}', 'MessageController@conversation')->name('message.conversation');
Route::post('/send-message','MessageController@sendMessage')->name('message.send-message');

Route::post('/send-group-message','MessageController@sendGroupMessage')->name('message.send-group-message');
Route::resource('message-group','MessageGroupController');
Route::get('/groupresponse/{userId}', 'MessageGroupController@reply')->name('message.reply');

