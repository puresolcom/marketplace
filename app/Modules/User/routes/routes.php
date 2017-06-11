<?php

$app->post('auth/register', 'AuthController@register');
$app->post('auth/login', 'AuthController@login');

$app->group(['middleware' => 'auth:api'], function () use ($app) {
    $app->get('/{id}', 'UserController@get');
    $app->get('/', 'UserController@fetch');
});