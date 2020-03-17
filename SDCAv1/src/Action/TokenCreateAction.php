<?php
namespace App\Action;

use App\Auth\JwtAuth;
use Slim\Http\Response;
use Slim\Http\ServerRequest;

final class TokenCreateAction
{
    private $jwtAuth;
    
    public function __construct(JwtAuth $jwtAuth)
    {
        $this->jwtAuth = $jwtAuth;
    }
    
    public function __invoke(ServerRequest $request, Response $response): Response
    {
        $data = (array)$request->getParsedBody();
        
        $username = (string)($data['username'] ?? '');
        $password = (string)($data['password'] ?? '');
        
        // Validate login (pseudo code)
        // Warning: This should be done in an application service and not here!
        // e.g. $isValidLogin = $this->userAuth->checkLogin($username, $password);
        $isValidLogin = ($username === 'user' && $password === 'secret');
        
        if (!$isValidLogin) {
            // Invalid authentication credentials
            return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(401, 'Unauthorized');
        }
        
        // Create a fresh token
        $token = $this->jwtAuth->createJwt($username);
        $lifetime = $this->jwtAuth->getLifetime();
        
        // Transform the result into a OAuh 2.0 Access Token Response
        // https://www.oauth.com/oauth2-servers/access-tokens/access-token-response/
        $result = [
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => $lifetime,
        ];
        
        // Build the HTTP response
        return $response->withJson($result)->withStatus(201);
    }
}