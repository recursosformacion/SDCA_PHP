<?php
namespace App\Action;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as ServerRequest;

final class PreflightAction
{
    public function __invoke(ServerRequest $request, Response $response): Response
    {
        // Do nothing here. Just return the response.
        return $response;
    }
}