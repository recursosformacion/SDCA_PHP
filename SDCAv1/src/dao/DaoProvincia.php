<?php
namespace App\dao;

use PDO;
use App\modelos\Cp_provincias;




class DaoProvincia extends DaoBase
{

    const SELECT_ALL = 'SELECT * FROM Cp_provincias';
    const SELECT_WHERE = 'SELECT * FROM Cp_provincias WHERE :where ORDER BY cppro_nombre';

    const SELECT_UNO = 'SELECT * FROM Cp_provincias WHERE cppro_id = :id';

    const INSERTAR = 'INSERT into Cp_provincias values (:id,:cppro_nombre)';

    const ACTUALIZA = 'UPDATE Cp_provincias set cppro_nombre = :cppro_nombre
                                        WHERE cppro_id = :id ';

    const DELETE = 'DELETE FROM Cp_provincias WHERE cppro_id = :id';
    const SELECT_LIMITE_COMUNIDAD ='SELECT DISTINCT cppro_codca FROM Cp_provincias ';


    function __construct(PDO $connection)
    {
        parent::__construct($connection,'App\modelos\Cp_provincias');
    }


    function montaBind(string $orden,  $provincia)
    {
        $stmt = $this->pdo->prepare($orden);
        $stmt->bindValue(':id', $provincia->getCppro_id());
        $stmt->bindValue(':cppro_nombre', $provincia->getCppro_nombre());

        return $stmt;
    }
    function montaBindDel(string $orden,  $provincia)
    {
        $stmt = $this->pdo->prepare($orden);
        $stmt->bindValue(':id', $provincia->getCppro_id());
        return $stmt;
    }

    function montaDebug(string $orden, Cp_provincias $provincia)
    {
        $paraDebug = $orden;
        $paraDebug= str_replace(':id', $provincia->getCppro_id(),$paraDebug);
        $paraDebug= str_replace(':cppro_nombre', $provincia->getCppro_nombre(),$paraDebug);
        return $paraDebug;
    }
}

