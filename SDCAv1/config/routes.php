<?php

//use App\Action\PreflightAction;
//use App\Middleware\JwtMiddleware;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\App;

// use Slim\Http\Response;
// use Slim\Http\ServerRequest;

// use Slim\Routing\RouteCollectorProxy;
// use App\controller\ControllerAPI;

return function (App $app) {
    $app->get('/', function (
        ServerRequestInterface $request,
        ResponseInterface $response
        ) {
            $response->getBody()->write('Hello, World!');
            
            return $response;
    });
    
//     $app->get('/', \App\Action\HomeAction::class);
   
//     $app->post('/api/v1/tokens', \App\Action\TokenCreateAction::class);
    
//     // Protect the whole group
//     $app->group('/api/v0', function (RouteCollectorProxy $group) {
        
        
//         $group->get('/ccaa', ControllerAPI::class . ':listadoCCAA');
//         $group->get('/pais',ControllerAPI::class );
//         $group->get('/poblacion',ControllerAPI::class . ':listadoPobla');
//         $group->get('/provincia',ControllerAPI::class . ':listadoProvin');
//         $group->get('/codpostal',ControllerAPI::class .':listadoCodPos');
        
//         $group->map(['GET', 'DELETE', 'PATCH', 'PUT'], '/usuario',\App\Action\NoImplementado::class);
        
// /*        $group->post('/usuario', \App\Action\UsuarioAction::class);
//         $group->get('/usuario',\App\Action\NoImplementado::class)->via('GET','POST','PUT','DELETE');
//         $group->get('/usuario/{id}',\App\Action\NoImplementado::class);
//         $group->put('/usuario/{id}',\App\Action\NoImplementado::class);
//         $group->delete('/usuario/{id}',\App\Action\NoImplementado::class);
        
//         $group->options('/usuario', PreflightAction::class);
//         $group->options('/usuario/{id}', PreflightAction::class);*/
//     });                           //->add(JwtMiddleware::class);
   
};
