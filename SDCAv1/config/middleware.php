<?php

// use Selective\Config\Configuration;
use Slim\App;
use Slim\Middleware\ErrorMiddleware;

return function (App $app) {
    // // Parse json, form data and xml
    $app->addBodyParsingMiddleware();

    // $app->add(\App\Middleware\CorsMiddleware::class);

    // // Add routing middleware
    $app->addRoutingMiddleware();
    $app->add(BasePathMiddleware::class); // <--- here

    // Catch exceptions and errors
    $app->add(ErrorMiddleware::class);

    // $container = $app->getContainer();

    // // Add error handler middleware
    // $settings = $container->get(Configuration::class)->getArray('error_handler_middleware');
    // $displayErrorDetails = (bool)$settings['display_error_details'];
    // $logErrors = (bool)$settings['log_errors'];
    // $logErrorDetails = (bool)$settings['log_error_details'];

    // $app->addErrorMiddleware($displayErrorDetails, $logErrors, $logErrorDetails);
};