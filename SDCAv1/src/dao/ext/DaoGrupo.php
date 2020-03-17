<?php
namespace dao;

require_once (PATH_RAIZ . 'service/Conexion.php');
require_once (PATH_RAIZ . 'modelos/Grupo.php');
require_once (PATH_RAIZ . 'service/excepciones/DaoException.php');
require_once (PATH_RAIZ . 'dao/DaoBase.php');
use modelos\Grupo;

class DaoGrupo extends DaoBase
{

    const SELECT_ALL = 'SELECT * FROM Grupos';
    const SELECT_WHERE = 'SELECT * FROM Grupos WHERE :where ORDER BY grupo_nombre';

    const SELECT_UNO = 'SELECT * FROM Grupos WHERE grupo_id = :id';

    const INSERTAR = 'INSERT into Grupos values (:id,:grupo_nombre,:grupo_descripcion,:grupo_actualizar,:grupo_url,:grupo_lastUpdate)';

    const ACTUALIZA = 'UPDATE Grupos set grupo_nombre = :grupo_nombre,
                                        grupo_descripcion=:grupo_descripcion,
                                        grupo_actualizar=:grupo_actualizar,
                                        grupo_url=:grupo_url,
                                        grupo_lastUpdate = :grupo_lastUpdate
                                        WHERE grupo_id = :id ';

    const DELETE = 'DELETE FROM Grupos WHERE grupo_id = :id';



    function __construct()
    {
        parent::__construct('modelos\Grupo');
    }


    public function montaBind(string $orden,  $grupo)
    {
        $stmt = $this->pdo->prepare($orden);
        $stmt->bindValue(':id', $grupo->getGrupo_id());
        $stmt->bindValue(':grupo_nombre', $grupo->getGrupo_nombre());
        $stmt->bindValue(':grupo_descripcion', $grupo->getGrupo_descripcion());
        $stmt->bindValue(':grupo_actualizar', $grupo->getGrupo_actualizar());
        $stmt->bindValue(':grupo_url', $grupo->getGrupo_url());
        $stmt->bindValue(':grupo_lastUpdate', $grupo->getGrupo_lastUpdate());
        return $stmt;
    }
    public function montaBindDel(string $orden,  $grupo)
    {
        $stmt = $this->pdo->prepare($orden);
        $stmt->bindValue(':id', $grupo->getGrupo_id());
        return $stmt;
    }

    public function montaDebug(string $orden,  $grupo)
    {
        $paraDebug = $orden;
        $paraDebug= str_replace(':id', $grupo->getGrupo_id(),$paraDebug);
        $paraDebug= str_replace(':grupo_nombre', $grupo->getGrupo_nombre(),$paraDebug);
        $paraDebug= str_replace(':grupo_descripcion', $grupo->getGrupo_descripcion(),$paraDebug);
        $paraDebug= str_replace(':grupo_actualizar', $grupo->getGrupo_actualizar(),$paraDebug);
        $paraDebug= str_replace(':grupo_url', $grupo->getGrupo_url(),$paraDebug);
        $paraDebug= str_replace(':grupo_lastUpdate', $grupo->getGrupo_lastUpdate(),$paraDebug);
        return $paraDebug;
    }
}

