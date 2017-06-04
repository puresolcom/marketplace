<?php

$app->group(['middleware' => 'auth:api'], function () use ($app) {
    $app->post('/', 'StoreController@create');
});