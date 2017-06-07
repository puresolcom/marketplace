<?php

$app->group(['middleware' => 'auth:api'], function () use ($app) {
    $app->post('/', 'ProductController@create');
    $app->put('/{id}', 'ProductController@update');
    $app->get('/{id}', 'ProductController@get');
    $app->get('/', 'ProductController@fetch');
});