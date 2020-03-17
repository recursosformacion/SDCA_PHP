<?php
namespace App\dao;

use App\dao\Daobase;
use App\modelos\Cp_comunidades;
use PDO;

class DaoComunidades extends Daobase
{

    const SELECT_ALL = 'SELECT * FROM Cp_comunidades ORDER BY cpcoa_nombre';
    const SELECT_WHERE = 'SELECT * FROM Cp_comunidades WHERE :where ORDER BY cpcoa_nombre';

    const SELECT_UNO = 'SELECT * FROM Cp_comunidades WHERE cpcoa_id = :id';

    const INSERTAR = 'INSERT into Cp_comunidades values (:id,:cpcoa_nombre,:cpcoa_pais)';

    const ACTUALIZA = 'UPDATE Cp_comunidades set cpcoa_nombre = :cpcoa_nombre
                                        WHERE cpcoa_id = :id ';

    const DELETE = 'DELETE FROM Cp_comunidades WHERE cpcoa_id = :id';



    function __construct(PDO $connection)
    {
        parent::__construct($connection,'App\modelos\Cp_comunidades');
    }

    
   
    function montaBind(string $orden,  $comunidad)
    {
        $stmt = $this->pdo->prepare($orden);
        $stmt->bindValue(':id', $comunidad->getCpcoa_id());
        $stmt->bindValue(':cpcoa_nombre', $comunidad->getCpcoa_nombre());
        $stmt->bindValue(':cpcoa_pais', $comunidad->getCpcoa_pais());
        return $stmt;
    }
    function montaBindDel(string $orden,  $comunidad)
    {
        $stmt = $this->pdo->prepare($orden);
        $stmt->bindValue(':id', $comunidad->getCpcoa_id());
        return $stmt;
    }

    function montaDebug(string $orden, Cp_comunidades $comunidad)
    {
        $paraDebug = $orden;
        $paraDebug= str_replace(':id', $comunidad->getCpcoa_id(),$paraDebug);
        $paraDebug= str_replace(':cpcoa_nombre', $comunidad->getCpcoa_nombre(),$paraDebug);
        return $paraDebug;
    }
}

