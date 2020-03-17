<?php
namespace App\Domain\usuario\service;

use App\Domain\usuario\data\ModeloUsuario;
use App\Domain\usuario\repository\RepositorioUsuario;
use UnexpectedValueException;

/**
 * Service.
 */
class ServicioUsuario
{
    /**
     * @var RepositorioUsuario
     */
    private $repository;
    
    /**
     * The constructor.
     *
     * @param RepositorioUsuario $repository The repository
     */
    public function __construct(RepositorioUsuario $repository)
    {
        $this->repository = $repository;
    }
    
    /**
     * Create a new user.
     *
     * @param ModeloUsuario $user The user data
     *
     * @return int The new user ID
     */
    public function createUser(ModeloUsuario $user): int
    {
        // Validation
        if (empty($user->cou_nombre)) {
            throw new UnexpectedValueException('Username required');
        }
        
        // Insert user
        $userId = $this->repository->insertUser($user);
        
        // Logging here: User created successfully
        
        return $userId;
    }
}

