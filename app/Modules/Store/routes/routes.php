<?php

$app->group(['middleware' => ['auth:api', 'role:admin|seller']], function () use ($app) {
    $app->post('/', 'StoreController@create');
    $app->put('/{id}', 'StoreController@update');
    $app->delete('/{id}', 'StoreController@delete');
    $app->get('/{id}', 'StoreController@get');
    $app->get('/', 'StoreController@fetch');
});