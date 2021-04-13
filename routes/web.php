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
        $router->get("info-categories", "InfoCategoryController@index");
        $router->post("info-categories", "InfoCategoryController@store");
    });
});