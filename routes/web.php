<?php

Route::get('/', 'HomeController@index')->name('home');
Auth::routes();

/**
 * Rutas del adminsitrador
 */
Route::prefix('admin')->middleware('auth')->group(function(){
    Route::prefix('usuarios')->group(function(){
        Route::get('/', 'UserController@index')->name('usuarios');
        Route::get('crear', 'UserController@create')->name('crearusuario');
        Route::post('nuevo', 'UserController@store')->name('guardausuario');
        Route::get('ver/{id}', 'UserController@edit')->name('verusuario');
        Route::post('guardar', 'UserController@update')->name('actualizausuario');
        Route::get('eliminar/{id}', 'UserController@destroy')->name('borrausuario');
        Route::get('acceso/{id}', 'UserController@changePassword')->name('cambiapassword');
        Route::post('password', 'UserController@updatePassword')->name('guardanuevopassword');
    });

    Route::prefix('dashboard')->group(function(){
        Route::get('/', 'UserController@home')->name('home');
        Route::get('/partido', 'UserController@partido')->name('partido');
    });
});
