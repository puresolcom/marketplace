<?php

$app->group(['middleware' => 'auth:api'], function () use ($app) {
    $app->post('/', 'CurrencyController@create');
    $app->put('/{id}', 'CurrencyController@update');
    $app->delete('/{id}', 'CurrencyController@delete');
});
$app->get('/', 'CurrencyController@list');

