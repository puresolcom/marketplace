<?php

$app->group(['middleware' => 'auth:api'], function () use ($app) {
    $app->post('/', 'StoreController@create');
    $app->get('/{id}', 'StoreController@get');
    $app->get('/', 'StoreController@fetch');
});