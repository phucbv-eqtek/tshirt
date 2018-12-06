<?php
Route::get('/', 'Frontend\Controller@index')->name('home');
Route::get('/home', function () {
    return redirect('/');
});
