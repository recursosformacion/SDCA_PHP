<?php
namespace dao;

require_once (PATH_RAIZ . 'service/Conexion.php');
require_once (PATH_RAIZ . 'modelos/Local.php');
require_once (PATH_RAIZ . 'service/excepciones/DaoException.php');
require_once (PATH_RAIZ . 'dao/DaoBase.php');
use PDO;
use modelos\Local;

class DaoLocal extends DaoBase
{

    const SELECT_ALL = 'SELECT * FROM Locales';
    const SELECT_WHERE = 'SELECT l.* FROM Locales l 
                                  LEFT JOIN Cp_provincias p ON cppro_id = local_provincia
                                  LEFT JOIN Clientes c ON local_cliente = cliente_id
                                    WHERE :where ORDER BY local_nombre';

    const SELECT_UNO = 'SELECT * FROM Locales WHERE local_id = :id';

    const SELECT_CLIENTE = 'SELECT * FROM Locales WHERE local_cliente = :local_cliente';

    const INSERTAR = 'INSERT into Locales values (:id,:local_cliente,:local_nombre,:local_direccion1,
                            :local_direccion2,:local_poblacion,:local_cp,:local_provincia,
                            :local_nom_comercial,
                            :local_actualizar,:local_url,:local_lastupdate,:local_lastip,:local_lastconexion)';

    const ACTUALIZA = 'UPDATE Locales set local_cliente = :local_cliente,
                                        local_nombre = :local_nombre,
                                        local_direccion1=:local_direccion1,
                                        local_direccion2=:local_direccion2,
                                        local_poblacion=:local_poblacion,
                                        local_cp=:local_cp,
                                        local_provincia=:local_provincia,
                                        local_nom_comercial=:local_nom_comercial,
                                        local_actualizar=:local_actualizar,
                                        local_url=:local_url,
                                        local_lastupdate = :local_lastupdate,
                                        local_lastip = :local_lastip,
                                        local_lastconexion = :local_lastconexion
                                        WHERE local_id = :id ';

    const ACTUALIZA_BOTON =  'UPDATE Locales set local_actualizar=:local_actualizar WHERE local_id = :id ';
    
    const DELETE = 'DELETE FROM Locales WHERE local_id = :id';

    const SELECT_LIMITE_POBLA ='SELECT DISTINCT local_poblacion FROM Locales';
    const SELECT_LIMITE_PROVIN ='SELECT DISTINCT local_provincia FROM Locales';
    

    function __construct()
    {
        parent::__construct('modelos\Local');
    }

    function listCliente(int $cliente):array
    {
        $stmt = $this->pdo->prepare(static::SELECT_CLIENTE);
        $stmt->bindValue(':local_cliente', $cliente);

        return $this->acceder($stmt);
    }


    function montaBind(string $orden,  $local)
    {
        $stmt = $this->pdo->prepare($orden);
        $stmt->bindValue(':id', $local->getLocal_id());
        $stmt->bindValue(':local_cliente', $local->getLocal_cliente());
        $stmt->bindValue(':local_nombre', $local->getLocal_nombre());
        $stmt->bindValue(':local_direccion1', $local->getLocal_direccion1());
        $stmt->bindValue(':local_direccion2', $local->getLocal_direccion2());
        $stmt->bindValue(':local_poblacion', $local->getLocal_poblacion());
        $stmt->bindValue(':local_cp', $local->getLocal_cp());
        $stmt->bindValue(':local_provincia', $local->getLocal_provincia());
        $stmt->bindValue(':local_nom_comercial', $local->getLocal_nom_comercial());
        $stmt->bindValue(':local_actualizar', $local->getLocal_actualizar());
        $stmt->bindValue(':local_url', $local->getLocal_url());
        $stmt->bindValue(':local_lastupdate', $local->getLocal_lastupdate(),PDO::PARAM_STR);
        $stmt->bindValue(':local_lastip', $local->getLocal_lastip());
        $stmt->bindValue(':local_lastconexion', $local->getLocal_lastconexion(),PDO::PARAM_STR);
        return $stmt;
    }
    function montaBindDel(string $orden,  $local)
    {
        $stmt = $this->pdo->prepare($orden);
        $stmt->bindValue(':id', $local->getLocal_id());
        return $stmt;
    }
    
    function actualizaActualizar($id, $estado):bool
    {
        $stmt = $this->pdo->prepare(static::ACTUALIZA_BOTON);
        $stmt->bindValue(':id',$id);
        $stmt->bindValue(':local_actualizar', $estado);
        
        $this->lanzarSQL($stmt);
        return true;
       
    }

    function montaDebug(string $orden, Local $local)
    {
        $paraDebug = $orden;
        $paraDebug= str_replace(':id', $local->getLocal_id(),$paraDebug);
        $paraDebug= str_replace(':local_cliente', $local->getLocal_cliente(),$paraDebug);
        $paraDebug= str_replace(':local_nombre', $local->getLocal_nombre(),$paraDebug);
        $paraDebug= str_replace(':local_direccion1', $local->getLocal_direccion1(),$paraDebug);
        $paraDebug= str_replace(':local_direccion2', $local->getLocal_direccion2(),$paraDebug);
        $paraDebug= str_replace(':local_poblacion', $local->getLocal_poblacion(),$paraDebug);
        $paraDebug= str_replace(':local_cp', $local->getLocal_cp(),$paraDebug);
        $paraDebug= str_replace(':local_provincia', $local->getLocal_provincia(),$paraDebug);
        $paraDebug= str_replace(':local_nom_comercial', $local->getLocal_nom_comercial(),$paraDebug);
        $paraDebug= str_replace(':local_actualizar', $local->getLocal_actualizar(),$paraDebug);
        $paraDebug= str_replace(':local_url', $local->getLocal_url(),$paraDebug);
        $paraDebug= str_replace(':local_lastupdate', $local->getLocal_lastUpdate(),$paraDebug);
        $paraDebug= str_replace(':local_lastip', $local->getLocal_lastip(),$paraDebug);
        $paraDebug= str_replace(':local_lastconexion', $local->getLocal_lastconexion(),$paraDebug);
        return $paraDebug;
    }
}

