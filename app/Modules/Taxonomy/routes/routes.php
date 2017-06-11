<?php
$app->group(['middleware' => 'auth:api'], function () use ($app) {
    $app->post('term', 'TaxonomyController@create');
    $app->get('/{id}', 'TaxonomyController@get');
    $app->get('/', 'TaxonomyController@fetch');
});