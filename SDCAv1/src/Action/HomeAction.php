<?php

namespace App\Action;

use Slim\Http\Response;
use Slim\Http\ServerRequest;

final class HomeAction
{
    public function __invoke(ServerRequest $request, Response $response): Response
    {
        $response->getBody()->write('Hello, Miguel!');

       // return  $response->withJson(['success' => true]);
        $result = ['error' => ['message' => 'Validation failed']];
        
        return $response->withJson($result)->withStatus(422);
    }
}