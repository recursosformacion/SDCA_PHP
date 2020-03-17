<?php
namespace App\dao;


use PDO;
use modelos\Cp_poblacion;

class DaoPoblacion extends DaoBase
{

    const SELECT_ALL = 'SELECT * FROM Cp_poblacion';
    const SELECT_WHERE = 'SELECT * FROM Cp_poblacion WHERE :where ORDER BY cppob_nombre';

    const SELECT_UNO = 'SELECT * FROM Cp_poblacion WHERE id = :id';

    const INSERTAR = 'INSERT into Cp_poblacion values (:id,:cppob_id,:cppro_id, :cppob_nombre,:cppob_ineid,:cppob_lat,:cppob_lon)';

    const ACTUALIZA = 'UPDATE Cp_poblacion set cppob_nombre = :cppob_nombre,
                                               cppob_id = :cppob_id,
                                               cppro_id = :cppro_id,
                                               cppob_ineid = :cppob_ineid,
                                               cppob_lat = :cppob_lat,
                                               cppob_lon = :cppob_lon
                                        WHERE id = :id ';

    const DELETE = 'DELETE FROM Cp_poblacion WHERE cppob_id = :id';


    const SELECT_COMUNIDADES_EXIST = "SELECT DISTINCT co.cpcoa__nombre 
                FROM Cp_Poblacion p,Cp_Provincias pr ,Cp_comunidades co 
                WHERE p.cppro_id = pr.cppro_id AND pr.cppro_codca = co.cpcoa_id";
    const SELECT_PROVINCIA_EXIST = "SELECT DISTINCT promo_comunidad FROM Cp_Poblacion";
 //   const POBLACIONES_EXISTENTES = "SELECT  cppro_nombre FROM Cp_poblacion WHERE id in (".
 //                                   DaoLocal::SELECT_LIMITE_POBLA .")";

    function __construct(PDO $connection)
    {
        parent::__construct($connection,'App\modelos\Cp_poblacion');
    }

    function montaPoblaExist():array
    {
        $stmt = $this->pdo->prepare(static::POBLACIONES_EXISTENTES);
        return $this->acceder($stmt);
    } 

    function montaBind(string $orden,  $poblacion)
    {
        $stmt = $this->pdo->prepare($orden);
        $stmt->bindValue(':id', $poblacion->getid());
        
        $stmt->bindValue(':cppob_id', $poblacion->getCppob_id());
        $stmt->bindValue(':cppro_id', $poblacion->getCpppro_id());
        $stmt->bindValue(':cppob_nombre', $poblacion->getCppob_nombre());
        $stmt->bindValue(':cppob_ineid', $poblacion->getCppob_ineid());
        $stmt->bindValue(':cppob_lat', $poblacion->getCppob_lat());
        $stmt->bindValue(':cppob_lon', $poblacion->getCppob_lon());

        return $stmt;
    }
    function montaBindDel(string $orden,  $poblacion)
    {
        $stmt = $this->pdo->prepare($orden);
        $stmt->bindValue(':id', $poblacion->getid());
        return $stmt;
    }

    function montaDebug(string $orden, Cp_poblacion $poblacion)
    {
        $paraDebug = $orden;
        $paraDebug= str_replace(':id', $poblacion->getCppob_id(),$paraDebug);
        $paraDebug= str_replace(':cppob_nombre', $poblacion->getCppob_nombre(),$paraDebug);
        return $paraDebug;
    }
}

