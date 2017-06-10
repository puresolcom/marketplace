<?php
$app->group(['middleware' => 'auth:api'], function () use ($app) {
    $app->post('term', 'TaxonomyController@create');
    $app->get('remote-sync', 'TaxonomyController@syncWithBitrix');
});