<?php
namespace dao;
require_once (PATH_RAIZ . 'service/Conexion.php');
require_once (PATH_RAIZ . 'modelos/Promocion.php');
require_once (PATH_RAIZ . 'service/excepciones/DaoException.php');
require_once (PATH_RAIZ . 'dao/DaoBase.php');
use modelos\Promocion;

class DaoPromocion extends DaoBase
{

    const SELECT_ALL = 'SELECT * FROM Promocion WHERE promo_emergencia = 0';
    const SELECT_WHERE = 'SELECT * FROM Promocion WHERE :where AND promo_emergencia = 0 ORDER BY promo_nombre';

    const SELECT_UNO = 'SELECT * FROM Promocion WHERE promo_id = :id';

    const INSERTAR = 'INSERT into Promocion values (:id,:promo_nombre,:promo_descripcion,
                            :promo_nivel,:promo_emergencia,:promo_fecini,:promo_fecfin,:promo_comunidad,
                            :promo_provincia,:promo_poblacion,
                            :promo_cpostal,:promo_actualizar,:promo_url, :promo_lastUpdate)';

    const ACTUALIZA = 'UPDATE Promocion set promo_nombre = :promo_nombre,
                                        promo_descripcion=:promo_descripcion,
                                        promo_nivel=:promo_nivel,
                                        promo_emergencia=:promo_emergencia,
                                        promo_fecini=:promo_fecini,
                                        promo_fecfin=:promo_fecfin,
                                        promo_comunidad=:promo_comunidad,
                                        promo_provincia=:promo_provincia,
                                        promo_poblacion=:promo_poblacion,
                                        promo_cpostal=:promo_cpostal,
                                        promo_actualizar=:promo_actualizar,
                                        promo_url = :promo_url,
                                        promo_lastUpdate = :promo_lastUpdate
                                        WHERE promo_id = :id ';

    const DELETE = 'DELETE FROM Promocion WHERE promo_id = :id';



    function __construct()
    {
        parent::__construct('modelos\Promocion');
    }

    
    function montaBind(string $orden,  $promocion)
    {
        $stmt = $this->pdo->prepare($orden);
        $stmt->bindValue(':id', $promocion->getPromo_id());
        $stmt->bindValue(':promo_nombre', $promocion->getPromo_nombre());
        $stmt->bindValue(':promo_descripcion', $promocion->getPromo_descripcion());
        $stmt->bindValue(':promo_nivel', $promocion->getPromo_nivel());
        $stmt->bindValue(':promo_emergencia', $promocion->getPromo_emergencia());
        $stmt->bindValue(':promo_fecini', $promocion->getPromo_fecini_String());
        $stmt->bindValue(':promo_fecfin', $promocion->getPromo_fecfin_String());
        $stmt->bindValue(':promo_comunidad', $promocion->getPromo_comunidad());
        $stmt->bindValue(':promo_provincia', $promocion->getPromo_provincia());
        $stmt->bindValue(':promo_poblacion', $promocion->getPromo_poblacion());
        $stmt->bindValue(':promo_cpostal', $promocion->getPromo_cpostal());
        $stmt->bindValue(':promo_actualizar', $promocion->getPromo_actualizar());
        $stmt->bindValue(':promo_url', $promocion->getPromo_url());
        $stmt->bindValue(':promo_lastUpdate', $promocion->getPromo_lastUpdate());
        return $stmt;
    }
    function montaBindDel(string $orden,  $promocion)
    {
        $stmt = $this->pdo->prepare($orden);
        $stmt->bindValue(':id', $promocion->getPromo_id());
        return $stmt;
    }

    function montaDebug(string $orden, Promocion $promocion)
    {
        $paraDebug = $orden;
        $paraDebug= str_replace(':id', $promocion->getPromo_id(),$paraDebug);
        $paraDebug= str_replace(':promo_nombre', $promocion->getPromo_nombre(),$paraDebug);
        $paraDebug= str_replace(':promo_descripcion', $promocion->getPromo_descripcion(),$paraDebug);
        $paraDebug= str_replace(':promo_nivel', $promocion->getPromo_nivel(),$paraDebug);
        $paraDebug= str_replace(':promo_fecini', $promocion->getPromo_fecini(),$paraDebug);
        $paraDebug= str_replace(':promo_fecfin', $promocion->getPromo_fecfin(),$paraDebug);
        $paraDebug= str_replace(':promo_comunidad', $promocion->getPromo_comunidad(),$paraDebug);
        $paraDebug= str_replace(':promo_provincia', $promocion->getPromo_provincia(),$paraDebug);
        $paraDebug= str_replace(':promo_poblacion', $promocion->getPromo_poblacion(),$paraDebug);
        $paraDebug= str_replace(':promo_cpostal', $promocion->getPromo_cpostal(),$paraDebug);
        $paraDebug= str_replace(':promo_actualizar', $promocion->getPromo_actualizar(),$paraDebug);
        $paraDebug= str_replace(':promo_url', $promocion->getPromo_url(),$paraDebug);
        $paraDebug= str_replace(':promo_lastUpdate', $promocion->getPromo_lastUpdate(),$paraDebug);
        return $paraDebug;
    }
}

