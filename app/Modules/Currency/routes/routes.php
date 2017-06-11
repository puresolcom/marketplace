<?php

$app->group(['middleware' => 'auth:api'], function () use ($app) {
});
$app->get('/', 'CurrencyController@list');

