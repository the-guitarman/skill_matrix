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

Route::namespace('Auth')->group(function() {
    //Route::prefix('session')->group(function () {
        Route::get('/login', 'SessionController@login')->name('login');
        Route::post('/login', 'SessionController@create')->name('login_create');
        Route::delete('/logout', 'SessionController@delete')->name('logout');
    //});
});



Route::middleware(['auth'])->group(function () {
    Route::get('/', 'PagesController@index')->name('root');

    Route::resource('skill-groups', 'SkillsGroupController')->parameters(['skill-groups' => 'id']);

    Route::resource('skill-groups.skills', 'SkillsController')
        ->parameters(['skill-groups' => 'skill_group_id', 'skills' => 'id']);
});