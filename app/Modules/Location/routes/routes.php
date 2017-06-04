<?php

$app->group(['middleware' => 'auth:api'], function () use ($app) {
});

$app->get('country', 'CountryController@list');
$app->get('city', 'CityController@list');
