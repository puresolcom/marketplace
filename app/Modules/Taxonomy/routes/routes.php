<?php
$app->group(['middleware' => 'auth:api'], function () use ($app) {
    $app->post('', 'TaxonomyController@create');
    $app->put('/{id}', 'TaxonomyController@update');
    $app->delete('/{id}', 'TaxonomyController@delete');
    $app->get('/{id}', 'TaxonomyController@get');
    $app->get('/', 'TaxonomyController@fetch');
});