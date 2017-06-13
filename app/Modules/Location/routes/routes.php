<?php

$app->group(['middleware' => 'auth:api'], function () use ($app) {

    $app->post('country', 'CountryController@create');
    $app->put('country/{id}', 'CountryController@update');
    $app->delete('country/{id}', 'CountryController@delete');

    $app->post('/', 'LocationController@create');
    $app->put('/{id}', 'LocationController@update');
    $app->delete('/{id}', 'LocationController@delete');
});

$app->get('/', 'LocationController@list');
$app->get('country', 'CountryController@list');
$app->get('city', 'LocationController@list');
