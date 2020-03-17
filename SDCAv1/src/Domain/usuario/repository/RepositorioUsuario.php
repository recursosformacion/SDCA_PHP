<?php
namespace App\Domain\usuario\repository;

use App\Domain\usuario\data\ModeloUsuario;
use PDO;

/**
 * Repository.
 */
class RepositorioUsuario
{
    /**
     * @var PDO The database connection
     */
    private $connection;
    
    /**
     * Constructor.
     *
     * @param PDO $connection The database connection
     */
    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }
    
    /**
     * Insert user row.
     *
     * @param ModeloUsuario $user The user
     *
     * @return int The new ID
     */
    public function insertUser(ModeloUsuario $user): int
    {
        $row = [
            'cou_nombre' => $user->cou_nombre,
            'cou_mnemonico' => $user->cou_mnemonico,
            'cou_mail' => $user->cou_mail,
            'cou_password' => $user->cou_password,
        ];
        
        $sql = "INSERT INTO co_usuarios SET
                cou_nombre=:cou_nombre,
                cou_mnemonico=:cou_mnemonico,
                cou_mail=:cou_mail,
                cou_password=:cou_password;";
        
        $this->connection->prepare($sql)->execute($row);
        
        return (int)$this->connection->lastInsertId();
    }
}

