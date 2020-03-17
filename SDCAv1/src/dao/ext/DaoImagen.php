<?php
namespace dao;

use PDO;
use modelos\Imagen;

require_once (PATH_RAIZ . 'service/Conexion.php');
require_once (PATH_RAIZ . 'modelos/Imagen.php');
require_once (PATH_RAIZ . 'service/excepciones/DaoException.php');
require_once (PATH_RAIZ . 'dao/DaoBase.php');


class DaoImagen extends DaoBase
{

    const SELECT_ALL = 'SELECT * FROM imagenes';

    const SELECT_UNO = 'SELECT * FROM imagenes WHERE imagen_id = :id';
    const SELECT_TIPO = 'SELECT * FROM imagenes WHERE imagen_pertenencia = :imagen_pertenencia AND
                                                      imagen_valor = :imagen_valor
                                                ORDER BY imagen_orden';

    const SELECT_TODAS = 
    'SELECT * FROM (
         SELECT * FROM imagenes r WHERE (imagen_pertenencia = "G" AND imagen_valor = :valorG
                                   OR imagen_pertenencia = "C" AND imagen_valor = :valorC
                                   OR imagen_pertenencia = "L" AND imagen_valor = :valorL)
                                                         
                                                UNION
                                         
        SELECT i.* FROM imagenes i join Promocion p  ON imagen_pertenencia = "P" 
                                                      AND imagen_valor = promo_id
                                 WHERE (promo_nivel = "D" AND promo_cpostal= :cpostal 
                                    OR promo_nivel = "O" AND promo_poblacion =:pobla  
                                    OR promo_nivel = "P" AND promo_provincia = :provin  
                                    OR promo_nivel = "C" AND promo_comunidad = :ca
                                       OR promo_nivel = "G")
                                  AND promo_fecini <= CURRENT_TIMESTAMP
                                  AND promo_fecfin >=  CURRENT_TIMESTAMP) extra
                             
   
    WHERE imagen_caduca >=  CURRENT_TIMESTAMP
                                                ORDER BY imagen_orden'
        ;
    
    
    const INSERTAR = 'INSERT into imagenes values (:id,
                                                    :imagen_nombre,
                                                    :imagen_nombre_origen,
                                                   :imagen_tiempo,
                                                   :imagen_orden,:imagen_pertenencia,
                                                   :imagen_valor,  :imagen_horainicio,
                                                   :imagen_horafin, :imagen_dias,
                                                   :imagen_caduca, :imagen_lastupdate)';

    //set imagen_nombre = :imagen_nombre, no se mantiene
    const ACTUALIZA = 'UPDATE imagenes  SET                    
                                        imagen_tiempo=:imagen_tiempo,
                                        imagen_orden=:imagen_orden,
                                        imagen_pertenencia=:imagen_pertenencia,
                                        imagen_valor=:imagen_valor,
                                        imagen_horainicio=:imagen_horainicio,
                                        imagen_horafin=:imagen_horafin,
                                        imagen_dias=:imagen_dias,
                                        imagen_caduca=:imagen_caduca, 
                                        imagen_lastupdate = :imagen_lastupdate
                                        WHERE imagen_id = :id ';

    const DELETE = 'DELETE FROM imagenes WHERE imagen_id = :id';



    function __construct()
    {
        parent::__construct('modelos\Imagen');
    }

    function listxTipo(string $tipo,int $valor):array
    {
        $stmt = $this->pdo->prepare(static::SELECT_TIPO);
        $stmt->bindValue(':imagen_pertenencia', $tipo);
        $stmt->bindValue(':imagen_valor', $valor);

        $lista = $this->acceder($stmt);
       
        return $lista;
    }

    function listTodas(int $valorG,int $valorC,$valorL,$cp,$poblacion):array
    {
        $stmt = $this->pdo->prepare(static::SELECT_TODAS);
        $stmt->bindValue(':valorG', $valorG);
        $stmt->bindValue(':valorC', $valorC);
        $stmt->bindValue(':valorL', $valorL);
        $stmt->bindValue(':cpostal', $cp);
        $stmt->bindValue(':pobla', $poblacion);
        $provincia = (new DaoPoblacion())->listPorId( $poblacion)->getcppro_id();
        $stmt->bindValue(':provin', $provincia);
        $ca=((new DaoProvincia())->listPorId( $provincia))->getCppro_codca();
        $stmt->bindValue(':ca', $ca);
        $result =  $this->acceder($stmt);
 //       $stmt->debugDumpParams() ;
        return $result;
    }

    public function montaBind(string $orden,  $imagen)
    {
        $stmt = $this->pdo->prepare($orden);
        $stmt->bindValue(':id', $imagen->getImagen_id());
        if (substr($orden,0,6)=="INSERT"){
            $stmt->bindValue(':imagen_nombre', $imagen->getImagen_nombre());
            $stmt->bindValue(':imagen_nombre_origen', $imagen->getImagen_nombre_origen());
        }
        $stmt->bindValue(':imagen_tiempo', $imagen->getImagen_tiempo());
        $stmt->bindValue(':imagen_orden', $imagen->getImagen_orden());
        $stmt->bindValue(':imagen_pertenencia', $imagen->getImagen_pertenencia());
        $stmt->bindValue(':imagen_valor', $imagen->getImagen_valor());
        $stmt->bindValue(':imagen_horainicio', $imagen->getImagen_horaInicio_String(),PDO::PARAM_STR);
        $stmt->bindValue(':imagen_horafin', $imagen->getImagen_horaFin_String(),PDO::PARAM_STR);
        $stmt->bindValue(':imagen_dias', $imagen->getImagen_dias());
        $stmt->bindValue(':imagen_caduca', $imagen->getImagen_caduca_String());
        $stmt->bindValue(':imagen_lastupdate', $imagen->getImagen_lastUpdate());
        return $stmt;
    }
    public function montaBindDel(string $orden,  $imagen)
    {
        $stmt = $this->pdo->prepare($orden);
        $stmt->bindValue(':id', $imagen->getImagen_id());
        return $stmt;
    }

    public function montaDebug(string $orden,Imagen  $imagen)
    {
        $paraDebug = $orden;
        $paraDebug= str_replace(':id', $imagen->getImagen_id(),$paraDebug);
        $paraDebug= str_replace(':imagen_nombre', $imagen->getImagen_nombre(),$paraDebug);
        $paraDebug= str_replace(':imagen_nombre_origen', $imagen->getImagen_nombre_origen(),$paraDebug);
        $paraDebug= str_replace(':imagen_tiempo', $imagen->getImagen_tiempo(),$paraDebug);
        $paraDebug= str_replace(':imagen_orden', $imagen->getImagen_orden(),$paraDebug);
        $paraDebug= str_replace(':imagen_pertenencia', $imagen->getImagen_pertenencia(),$paraDebug);
        $paraDebug= str_replace(':imagen_valor', $imagen->getImagen_valor(),$paraDebug);
        $paraDebug= str_replace(':imagen_horainicio', $imagen->getImagen_horaInicio_String(),$paraDebug);
        $paraDebug= str_replace(':imagen_horafin', $imagen->getImagen_horaFin_String(),$paraDebug);
        $paraDebug= str_replace(':imagen_dias', $imagen->getImagen_dias(),$paraDebug);
        $paraDebug= str_replace(':imagen_caduca', $imagen->getImagen_caduca_String(),$paraDebug);
        $paraDebug= str_replace(':imagen_lastupdate', $imagen->getImagen_lastUpdate(),$paraDebug);
        return $paraDebug;
    }
}

