<?php

$app->group(['middleware' => 'auth:api'], function () use ($app) {

    // Attribute Routes
    $app->post('/attribute', 'AttributeController@create');
    $app->put('/attribute/{id}', 'AttributeController@update');
    $app->delete('/attribute/{id}', 'AttributeController@delete');
    $app->get('/attribute/{id}', 'AttributeController@get');
    $app->get('/attribute', 'AttributeController@fetch');

    // Product Routes
    $app->post('/', 'ProductController@create');
    $app->put('/{id}', 'ProductController@update');
    $app->get('/{id}', 'ProductController@get');
    $app->get('/', 'ProductController@fetch');
});