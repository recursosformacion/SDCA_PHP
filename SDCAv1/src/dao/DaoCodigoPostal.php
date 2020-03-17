<?php
namespace App\dao;


use PDO;
use App\modelos\Cp_codigopostal;
use service\DaoException;

class DaoCodigoPostal extends DaoBase
{
//     const SELECT_UNO_POSTAL = 'SELECT cp.*, cpo.id FROM Cp_codigopostal cp 
//                     LEFT JOIN Cp_poblacion cpo on cpo.cppob_id = cp.cppob_id AND cpo.cppro_id = cp.cppro_id
//                     WHERE cpcod_cpostal = :id';
    
    const SELECT_ALL = 'SELECT * FROM Cp_codigopostal';
    const SELECT_WHERE = 'SELECT * FROM Cp_codigopostal WHERE :where ORDER BY cppob_id';

    const SELECT_UNO = 'SELECT * FROM Cp_codigopostal WHERE id = :id';

    const INSERTAR = 'INSERT into Cp_codigopostal values (:id,:cpcod_cpostal,:cppob_id,:cppro_id)';

    const ACTUALIZA = 'UPDATE Cp_codigopostal set cppob_id = :cppob_id,
                                              set cppro_id = :cppro_id     
                                        WHERE cpcod_cpostal = :id ';

    const DELETE = 'DELETE FROM Cp_codigopostal WHERE cpcod_id = :id';



    function __construct(PDO $connection)
    {
        parent::__construct($connection,'App\modelos\Cp_codigopostal');
    }

    
    function montaBind(string $orden,  $codigopostal)
    {
        $stmt = $this->pdo->prepare($orden);
        $stmt->bindValue(':id', $codigopostal->getCpcod_id());
        $stmt->bindValue(':cpcod_cpostal', $codigopostal->getCpcod_cpostal());
        $stmt->bindValue(':cppob_id', $codigopostal->getCppob_id());
        $stmt->bindValue(':cppro_id', $codigopostal->getCppro_id());

        return $stmt;
    }
    function montaBindDel(string $orden,  $codigopostal)
    {
        $stmt = $this->pdo->prepare($orden);
        $stmt->bindValue(':id', $codigopostal->getCpcod_id());
        return $stmt;
    }

    function montaDebug(string $orden, Cp_codigopostal $codigopostal)
    {
        $paraDebug = $orden;
        $paraDebug= str_replace(':id', $codigopostal->getCpcod_id(),$paraDebug);
        $paraDebug= str_replace(':cpcod_cpostal', $codigopostal->getCpcod_cpostal(),$paraDebug);
        $paraDebug= str_replace(':cppob_id', $codigopostal->getCppob_id(),$paraDebug);
        $paraDebug= str_replace(':cppro_id', $codigopostal->getCppro_id(),$paraDebug);
        return $paraDebug;
    }
}

