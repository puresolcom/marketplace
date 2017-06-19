<?php

$app->group(['middleware' => ['auth:api', 'role:admin']], function () use ($app) {
    $app->post('/', 'CurrencyController@create');
    $app->put('/{id}', 'CurrencyController@update');
    $app->delete('/{id}', 'CurrencyController@delete');
});
$app->get('/', 'CurrencyController@list');

