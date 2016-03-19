<?php

//$app->add(new App\Middleware\ResourseMetterMiddleware());
/*
$app->add('App\Middleware\ResourseMetterMiddleware:run1');
$app->add('App\Middleware\ResourseMetterMiddleware:run2');
$app->add('App\Middleware\ResourseMetterMiddleware:run3');
*/

$app->add(new \Slim\HttpCache\Cache('public', 86400)); // Priority: 40

$app->add(new \Slim\Csrf\Guard); // Priority: 30

$app->add(new App\Middleware\CorsMiddleware()); // Priority: 20

//$app->add('App\Middleware\ResourseMetterMiddleware:metter'); // Priority: 10