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
 
Route::get('/', function () {
    return view('welcome');
});
Route::get('/user',function(){
	return auth()->user();
})->middleware('auth');

Auth::routes();
Broadcast::routes();

Route::get('/home', 'HomeController@index')->name('home');
 Route::get('/complete_profile', function(){
 	return view('complete_registration');
 })->name('complete_registration')->middleware('auth');
 Route::post('/app/user/profile/update','HomeController@updateProfile');


Route::get('/app/fetch_notes','AdminController@show');
Route::delete('/app/deleteNote','AdminController@deleteNote');
Route::post('/app/edit_note ','AdminController@editNote');
Route::post('/app/create_note','AdminController@create');
Route::post('/app/notes/share','AdminController@shareNote');

 

 Route::post('/app/create_plan','PlansController@create');
 Route::post('/app/edit_plan','PlansController@edit');
 Route::delete('/app/deletePlan','PlansController@destroy');
 Route::get('/app/fetch_plans','PlansController@index');


 Route::post('/app/create_room','RoomsController@store');
 Route::patch('/app/edit_room','RoomsController@update');
 Route::delete('/app/deleteRoom','RoomsController@destroy');
 Route::get('/app/fetch_rooms','RoomsController@index');
 Route::post('/joinClass/{room}','RoomsController@join');

 Route::get('/student/get/rooms','RoomsController@available');

 Route::get('/user/fetchContacts','ChatController@index');
 Route::get('/user/fetchMessages/{user}','ChatController@show');
 Route::post('/user/sendMessage','ChatController@store');

 Route::post('/student/fetchnotes','ReadController@notes_from_class_loader');
 Route::get('/app/fetch_my_rooms','ReadController@my_classes');


 Route::post('/teacher/post_opinion_request','ReviewsController@store');
 Route::get('/teacher/fetch_review_questions','ReviewsController@index');
 Route::get('/responder/fetch_review_questions','ReviewsController@show');
Route::post('/reviews/add','ReviewsController@create');
Route::post('questions/fetch_reviews','ReviewsController@all_reviews');

Route::any('{slug}' , function(){
	return view('home');
})->middleware('auth');
