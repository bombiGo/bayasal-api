<?php

$router->get("/", function () use ($router) {
    return view("index");
});

$router->group(["prefix" => "api"], function () use ($router) {
    // Guest 
    $router->post("register", "AuthController@register");
    $router->post("login", "AuthController@login");
    
    // Get all data
    $router->get("get-data", "HelperController@getData");
    // Course detail
    $router->get("course-detail/{id}", "HelperController@detailCourse");

    $router->group(["middleware" => ["auth"]], function () use ($router) {
        // Auth 
        $router->get("me", "AuthController@me");
        $router->post("logout", "AuthController@logout");

        $router->post("user/update", "AuthController@updateUser");
        $router->post("password/change", "AuthController@changePassword");

        // Orders
        $router->post("/orders", "AuthController@createOrder");
    });

    // Web role middleware 
    $router->group(["middleware" => ["auth", "role"]], function () use ($router) {
        $router->get("dashboard", "HomeController@dashboard");
        
        // Editor upload
        $router->post("editor/upload", "HelperController@uploadEditor");

        // User 
        $router->get("users", "UserController@index");
        $router->post("users", "UserController@store");
        $router->get("users/{id}/edit", "UserController@edit");
        $router->post("users/{id}/update", "UserController@update");
        $router->delete("users/{id}", "UserController@destroy");

        // Info 
        $router->get("infos", "InfoController@index");
        $router->post("infos", "InfoController@store");
        $router->get("infos/{id}/edit", "InfoController@edit");
        $router->post("infos/{id}/update", "InfoController@update");
        $router->delete("infos/{id}", "InfoController@destroy");
        
        // Info category
        $router->get("info-categories", "InfoCategoryController@index");
        $router->post("info-categories", "InfoCategoryController@store");
        $router->get("info-categories/{id}/edit", "InfoCategoryController@edit");
        $router->post("info-categories/{id}/update", "InfoCategoryController@update");
        $router->delete("info-categories/{id}", "InfoCategoryController@destroy");

        // Info 
        $router->get("recipes", "RecipeController@index");
        $router->post("recipes", "RecipeController@store");
        $router->get("recipes/{id}/edit", "RecipeController@edit");
        $router->post("recipes/{id}/update", "RecipeController@update");
        $router->delete("recipes/{id}", "RecipeController@destroy");
        
        // Recipe category
        $router->get("recipe-categories", "RecipeCategoryController@index");
        $router->post("recipe-categories", "RecipeCategoryController@store");
        $router->get("recipe-categories/{id}/edit", "RecipeCategoryController@edit");
        $router->post("recipe-categories/{id}/update", "RecipeCategoryController@update");
        $router->delete("recipe-categories/{id}", "RecipeCategoryController@destroy");

        // Course
        $router->get("courses", "CourseController@index");
        $router->post("courses", "CourseController@store");
        $router->get("courses/{id}", "CourseController@show");
        $router->get("courses/{id}/edit", "CourseController@edit");
        $router->post("courses/{id}/update", "CourseController@update");
        $router->delete("courses/{id}", "CourseController@destroy");

        // Lesson 
        $router->post("lessons/{id}", "LessonController@store");
        $router->get("lessons/{id}", "LessonController@edit");
        $router->post("lessons/{id}/update", "LessonController@update");
        $router->delete("lessons/{id}", "LessonController@destroy");
    });
});