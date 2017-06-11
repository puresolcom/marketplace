<?php

$app->group(['middleware' => 'auth:api'], function () use ($app) {
    $app->get('/attribute/{id}', 'AttributeController@get');
    $app->get('/attribute', 'AttributeController@fetch');
    $app->get('/{id}', 'ProductController@get');
    $app->get('/', 'ProductController@fetch');
    $app->post('/', 'ProductController@create');
    $app->put('/{id}', 'ProductController@update');
});