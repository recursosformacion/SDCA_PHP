<?php

use Psr\Container\ContainerInterface;
// use Selective\Config\Configuration;
// use Psr\Http\Message\ResponseFactoryInterface;
use Slim\App;
use Slim\Factory\AppFactory;
use Slim\Middleware\ErrorMiddleware;

//use App\Auth\JwtAuth;

return [
    Configuration::class => function () {
        return new Configuration(require __DIR__ . '/settings.php');
    },

    App::class => function (ContainerInterface $container) {
        AppFactory::setContainer($container);
        $app = AppFactory::create();

        // Optional: Set the base path to run the app in a sub-directory
        // The public directory must not be part of the base path
        //-------------------------------------------------------------------->$app->setBasePath('/slim4-tutorial');

        return $app;
    },
    PDO::class => function (ContainerInterface $container) {
        $config = $container->get(Configuration::class);
        
        $host = $config->getString('db.host');
        $dbname =  $config->getString('db.database');
        $username = $config->getString('db.username');
        $password = $config->getString('db.password');
        $charset = $config->getString('db.charset');
        $flags = $config->getArray('db.flags');
        $dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";
        
        return new PDO($dsn, $username, $password, $flags);
    },
    
   // Preparandose para jwt
    ResponseFactoryInterface::class => function (ContainerInterface $container) {
        return $container->get(App::class)->getResponseFactory();
    },
    
    // 
    JwtAuth::class => function (ContainerInterface $container) {
        $config = $container->get(Configuration::class);
        
        $issuer = $config->getString('jwt.issuer');
        $lifetime = $config->getInt('jwt.lifetime');
        $privateKey = $config->getString('jwt.private_key');
        $publicKey = $config->getString('jwt.public_key');
        
        return new JwtAuth($issuer, $lifetime, $privateKey, $publicKey);
    },

];