<?php
namespace App\Action;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as ServerRequest;

final class NoImplementado
{
    public function __invoke(ServerRequest $request, Response $response, array $arguments): Response
    {
        $id=0;
        if (is_array($arguments)) {
            if (isset($arguments['id'])){
               $id=$arguments['id'];
            }
        }
        $response->getBody()->write('Ruta no implementada. id = '. $id);
        
        return $response;
    }
}