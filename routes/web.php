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
        ->except(['show'])
        ->parameters(['skill-groups' => 'skill_group_id', 'skills' => 'id']);

    Route::get('/skills/my', 'MySkillsController@index')->name('skills.my.index');
    Route::get('/skills/{skill_id}/my/create', 'MySkillsController@create')->name('skills.my.create');
    Route::post('/skills/{skill_id}/my', 'MySkillsController@store')->name('skills.my.store');
    Route::get('/skills/{skill_id}/my/edit', 'MySkillsController@edit')->name('skills.my.edit');
    Route::put('/skills/{skill_id}/my', 'MySkillsController@update')->name('skills.my.update');
    Route::delete('/skills/{skill_id}/my', 'MySkillsController@destroy')->name('skills.my.destroy');
});