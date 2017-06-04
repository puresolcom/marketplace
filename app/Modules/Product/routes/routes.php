<?php

$app->group(['middleware' => 'auth:api'], function () use ($app) {
    $app->post('/', 'ProductController@create');
});

$app->get('test', 'ProductController@test');