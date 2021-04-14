<?php

$router->get("/", function () use ($router) {
    return $router->app->version();
});

$router->group(["prefix" => "api"], function () use ($router) {
    // Guest 
    $router->post("register", "AuthController@register");
    $router->post("login", "AuthController@login");
    
    // Auth 
    $router->group(["middleware" => "auth"], function () use ($router) {
        $router->get("me", "AuthController@me");
        $router->post("logout", "AuthController@logout");
        // $router->post("change-password", "AuthController@changePassword");
        // $router->post("update-user", "AuthController@updateUser");
    
        $router->get("users", "UserController@index");

        // Info 
        $router->get("infos", "InfoController@index");
        $router->post("infos", "InfoController@store");
        $router->get("infos/{id}/edit", "InfoController@edit");
        // $router->post("info-categories/{id}/update", "InfoCategoryController@update");
        // $router->delete("info-categories/{id}", "InfoCategoryController@destroy");
        
        // Info category
        $router->get("info-categories", "InfoCategoryController@index");
        $router->post("info-categories", "InfoCategoryController@store");
        $router->get("info-categories/{id}/edit", "InfoCategoryController@edit");
        $router->post("info-categories/{id}/update", "InfoCategoryController@update");
        $router->delete("info-categories/{id}", "InfoCategoryController@destroy");
    });
});