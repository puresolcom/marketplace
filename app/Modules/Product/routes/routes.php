<?php

$app->group(['middleware' => ['auth:api', 'role:admin|seller']], function () use ($app) {

    // Attribute Routes
    $app->post('/attribute', 'AttributeController@create');
    $app->put('/attribute/{id}', 'AttributeController@update');
    $app->delete('/attribute/{id}', 'AttributeController@delete');
    $app->get('/attribute/{id}', 'AttributeController@get');
    $app->get('/attribute', 'AttributeController@fetch');

    // Product Routes
    $app->post('/{id}/media', 'MediaController@store');
    $app->post('/', 'ProductController@create');
    $app->put('/{id}', 'ProductController@update');
    $app->delete('/{id}', 'ProductController@delete');
    $app->get('/{id}', 'ProductController@get');
    $app->get('/{id}/attributes', 'ProductController@getProductAttributes');
    $app->get('/', 'ProductController@fetch');
});