<?php

Route::group(['prefix' => 'meli'], function() {
    Route::get('/callback', 'CallbackController@callback');
});