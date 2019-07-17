<?php

use Illuminate\Routing\Router;

Admin::registerAuthRoutes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('admin.home');
    $router->resource('inventory/users', PersonsController::class);
    $router->resource('inventory/borrowed', BorrowedController::class);
    $router->resource('inventory/inventory', InventoryController::class);
    $router->resource('inventory/warehouse', WarehouseController::class);
    $router->resource('inventory/category', CategoryController::class);

});
