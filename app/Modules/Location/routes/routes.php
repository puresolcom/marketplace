<?php

$app->group(['middleware' => 'auth:api', ['middleware' => ['auth:api', 'role:admin']]], function () use ($app) {

    $app->post('country', 'CountryController@create');
    $app->put('country/{id}', 'CountryController@update');
    $app->delete('country/{id}', 'CountryController@delete');

    $app->post('/', 'LocationController@create');
    $app->put('/{id}', 'LocationController@update');
    $app->delete('/{id}', 'LocationController@delete');
});

$app->get('country/{id}', 'CountryController@get');
$app->get('country', 'CountryController@list');
$app->get('city', 'LocationController@list');
$app->get('/{id}', 'LocationController@get');
$app->get('/', 'LocationController@list');
