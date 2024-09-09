<?php
    //$router->apiResource($apiPrefix.'/stations', 'App\Controllers\Api\StationController');
    // $router->get($apiPrefix.'/stations', 'App\Controllers\Api\StationController@index');

    $router->apiResource($apiPrefix.'/stations', 'App\Controllers\Api\StationController');
    $router->apiResource($apiPrefix.'/information', 'App\Controllers\Api\InformationController');
    $router->apiResource($apiPrefix.'/companies', 'App\Controllers\Api\CompanyController');
    $router->apiResource($apiPrefix.'/oil-types', 'App\Controllers\Api\OilTypeController');
    $router->apiResource($apiPrefix.'/phones', 'App\Controllers\Api\PhoneController');
    $router->apiResource($apiPrefix."/townships", 'App\Controllers\Api\TownshipController');
    $router->apiResource($apiPrefix.'/posts', 'App\Controllers\Api\PostController');

    $router->post($apiPrefix.'/information-reset-available', 'App\Controllers\Api\InformationController@reset');
    $router->post($apiPrefix.'/information-reset-price', 'App\Controllers\Api\InformationController@resetPrice');

    $router->post($apiPrefix.'/stations-open', 'App\Controllers\Api\StationController@open');
 
