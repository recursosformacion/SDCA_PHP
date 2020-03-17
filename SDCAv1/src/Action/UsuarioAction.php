<?php
namespace App\Action;

use App\Domain\usuario\data\ModeloUsuario;
use App\Domain\usuario\service\ServicioUsuario;
use Slim\Http\Response;
use Slim\Http\ServerRequest;

final class UsuarioAction
{
    private $userCreator;
    
    public function __construct(ServicioUsuario $userCreator)
    {
        $this->userCreator = $userCreator;
    }
    
    public function __invoke(ServerRequest $request, Response $response): Response
    {
        // Collect input from the HTTP request
        $data = (array)$request->getParsedBody();
        var_dump($data);
        // Mapping (should be done in a mapper class)
        $user = new ModeloUsuario();
        $user->cou_nombre = $data['nombre'];
        $user->cou_mnemonico = $data['mnemonico'];
        $user->cou_mail = $data['mail'];
        $user->cou_password = $data['password'];
        
        // Invoke the Domain with inputs and retain the result
        $userId = $this->userCreator->createUser($user);
        
        // Transform the result into the JSON representation
        $result = [
            'user_id' => $userId
        ];
        
        // Build the HTTP response
        return $response->withJson($result)->withStatus(201);
    }
}

