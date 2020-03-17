<?php
namespace dao;
require_once (PATH_RAIZ . 'service/Conexion.php');
require_once (PATH_RAIZ . 'modelos/Cliente.php');
require_once (PATH_RAIZ . 'service/excepciones/DaoException.php');
require_once (PATH_RAIZ . 'dao/DaoBase.php');
use modelos\Cliente;

class DaoCliente extends DaoBase
{

    const SELECT_ALL = 'SELECT * FROM Clientes';
    const SELECT_WHERE = 'SELECT * FROM Clientes WHERE :where ORDER BY cliente_nombre';

    const SELECT_UNO = 'SELECT * FROM Clientes WHERE cliente_id = :id';

    const SELECT_GRUPO = 'SELECT * FROM Clientes WHERE cliente_grupo = :cliente_grupo';

    const INSERTAR = 'INSERT into Clientes values (:id,:cliente_nombre,:cliente_direccion1,
                            :cliente_direccion2,:cliente_poblacion,:cliente_cp,:cliente_provincia,
                            :cliente_nom_comercial,:cliente_grupo,
                            :cliente_actualizar,:cliente_url,:cliente_lastUpdate)';

    const ACTUALIZA = 'UPDATE Clientes set cliente_nombre = :cliente_nombre,
                                        cliente_direccion1=:cliente_direccion1,
                                        cliente_direccion2=:cliente_direccion2,
                                        cliente_poblacion=:cliente_poblacion,
                                        cliente_cp=:cliente_cp,
                                        cliente_provincia=:cliente_provincia,
                                        cliente_nom_comercial=:cliente_nom_comercial,
                                        cliente_grupo=:cliente_grupo,
                                        cliente_actualizar=:cliente_actualizar,
                                        cliente_url=:cliente_url,
                                        cliente_lastUpdate = :cliente_lastUpdate
                                        WHERE cliente_id = :id ';

    const DELETE = 'DELETE FROM Clientes WHERE cliente_id = :id';



    function __construct()
    {
        parent::__construct('modelos\Cliente');
    }

    function listxGrupo(int $grupo):array
    {
        $stmt = $this->pdo->prepare(static::SELECT_GRUPO);
        $stmt->bindValue(':cliente_grupo', $grupo);

        return $this->acceder($stmt);
    }

    function montaBind(string $orden,  $cliente)
    {
        $stmt = $this->pdo->prepare($orden);
        $stmt->bindValue(':id', $cliente->getCliente_id());
        $stmt->bindValue(':cliente_nombre', $cliente->getCliente_nombre());
        $stmt->bindValue(':cliente_direccion1', $cliente->getCliente_direccion1());
        $stmt->bindValue(':cliente_direccion2', $cliente->getCliente_direccion2());
        $stmt->bindValue(':cliente_poblacion', $cliente->getCliente_poblacion());
        $stmt->bindValue(':cliente_cp', $cliente->getCliente_cp());
        $stmt->bindValue(':cliente_provincia', $cliente->getCliente_provincia());
        $stmt->bindValue(':cliente_nom_comercial', $cliente->getCliente_nom_comercial());
        $stmt->bindValue(':cliente_grupo', $cliente->getCliente_grupo());
        $stmt->bindValue(':cliente_actualizar', $cliente->getCliente_actualizar());
        $stmt->bindValue(':cliente_url', $cliente->getCliente_url());
        $stmt->bindValue(':cliente_lastUpdate', $cliente->getCliente_lastUpdate());
        return $stmt;
    }
    function montaBindDel(string $orden,  $cliente)
    {
        $stmt = $this->pdo->prepare($orden);
        $stmt->bindValue(':id', $cliente->getCliente_id());
        return $stmt;
    }

    function montaDebug(string $orden, Cliente $cliente)
    {
        $paraDebug = $orden;
        $paraDebug= str_replace(':id', $cliente->getCliente_id(),$paraDebug);
        $paraDebug= str_replace(':cliente_nombre', $cliente->getCliente_nombre(),$paraDebug);
        $paraDebug= str_replace(':cliente_direccion1', $cliente->getCliente_direccion1(),$paraDebug);
        $paraDebug= str_replace(':cliente_direccion2', $cliente->getCliente_direccion2(),$paraDebug);
        $paraDebug= str_replace(':cliente_poblacion', $cliente->getCliente_poblacion(),$paraDebug);
        $paraDebug= str_replace(':cliente_cp', $cliente->getCliente_cp(),$paraDebug);
        $paraDebug= str_replace(':cliente_provincia', $cliente->getCliente_provincia(),$paraDebug);
        $paraDebug= str_replace(':cliente_nom_comercial', $cliente->getCliente_nom_comercial(),$paraDebug);
        $paraDebug= str_replace(':cliente_grupo', $cliente->getCliente_grupo(),$paraDebug);
        $paraDebug= str_replace(':cliente_actualizar', $cliente->getCliente_actualizar(),$paraDebug);
        $paraDebug= str_replace(':cliente_url', $cliente->getCliente_url(),$paraDebug);
        $paraDebug= str_replace(':cliente_lastUpdate', $cliente->getCliente_lastUpdate(),$paraDebug);
        return $paraDebug;
    }
}

