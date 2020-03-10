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

Route::group(['middleware'=>'api'],function (){
    //List articles
    Route::get('articles','ArticleController@index')->name('articles.index');
//List single article
    Route::get('article/{article}','ArticleController@show')->name('article.show');

//Create new article
    Route::post('article','ArticleController@store')->name('article.store');

//Update article
    Route::put('article/{article}/edit','ArticleController@update')->name('article.update');

//Delete article
    Route::delete('article/{article}/delete','ArticleController@destroy')->name('article.delete');

});
