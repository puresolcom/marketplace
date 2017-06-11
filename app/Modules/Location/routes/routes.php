<?php

$app->group(['middleware' => 'auth:api'], function () use ($app) {
});

$app->get('/', 'LocationController@list');
$app->get('country', 'CountryController@list');
$app->get('city', 'LocationController@list');
