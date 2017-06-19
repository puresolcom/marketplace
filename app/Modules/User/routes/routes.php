<?php

$app->post('auth/register', 'AuthController@register');
$app->post('auth/login', 'AuthController@login');

$app->group(['middleware' => ['auth:api', 'role:admin|seller']], function () use ($app) {
    $app->put('/{id}', 'UserController@update');
    $app->put('/{id}', 'UserController@delete');
    $app->get('/{id}', 'UserController@get');
    $app->get('/', 'UserController@fetch');
});