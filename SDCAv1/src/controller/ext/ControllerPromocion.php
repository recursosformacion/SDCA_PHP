<?php
namespace controller;

require_once PATH_RAIZ . 'controller/ConstantesControladores.php';
require_once PATH_RAIZ . 'service/Request.php';
require_once PATH_RAIZ . 'modelos/Promocion.php';
require_once PATH_RAIZ . 'service/ProcesaImagenes.php';
require_once PATH_RAIZ . 'dao/DaoPromocion.php';
require_once PATH_RAIZ . 'controller/ControllerLocal.php';

use service\Request;
use service\ProcesaImagenes;
use dao\DaoPromocion;

use modelos\Promocion;



class ControllerPromocion
{

    private $cDao;

    private $request;

   

    function __construct()
    {
        $this->cDao = new DaoPromocion();
        $this->request = new Request();
    }

    public function index()
    {
        echo "<h1>index</h1>";
    }

    /**
     * Listado
     * return String
     * carga $_REQUEST['salida'] con un array de objetos Promocion *
     */
    public function listado()
    {
        $_REQUEST['salida'] = $this->cDao->listAll();
        $_REQUEST['cancelar'] = URL_CANCELACION_PROMOCION;
        return "listado";
    }

    /**
     *
     * Deja en $_REQUEST['salida'] el objeto Promocion solicitado
     *
     * @param
     *            Lista de parametros recibidos por GET y POST
     * @return string con nombre de vista
     */
    public function editar($request)
    {
        $promocion= $this->cDao->listPorId($request->getParamNumer('id'));
        $_REQUEST['salida'] =$promocion;
        $_REQUEST['url'] = "?controller=Promocion&action=actualizar";
        $_REQUEST['opcion'] = "Modificar";
        $_REQUEST['listaNiveles'] = montaNiveles($promocion->getPromo_nivel());
        $_REQUEST['listaComunidad'] = montaComunidadExist($promocion->getPromo_comunidad());
        $_REQUEST['listaprovincias']= montaProvinciaExist( $promocion->getPromo_provincia(),$promocion->getPromo_comunidad());
        $_REQUEST['listapoblacion'] = montaPoblacionExist($promocion->getPromo_poblacion(),
            $promocion->getPromo_provincia());
        $_REQUEST['cancelar'] = URL_CANCELACION_PROMOCION;
        return "formulario";
    }

    public function add($request)
    {
        $_REQUEST['salida'] = new Promocion();
        $_REQUEST['url'] = "?controller=Promocion&action=insertar";
        $_REQUEST['opcion'] = "Añadir";
        $_REQUEST['listaNiveles'] = montaNiveles("");
        $_REQUEST['listaComunidad'] = montaComunidadExist(0);
        $_REQUEST['listaprovincias']= "";
        $_REQUEST['listapoblacion'] ="";
        $_REQUEST['cancelar'] = URL_CANCELACION_PROMOCION;
        return "formulario";
    }

    public function borra($request)
    {
        $promocion=$this->cDao->listPorId($request->getParamNumer('id'));
        $_REQUEST['salida'] = $promocion;
        $_REQUEST['url'] = "?controller=Promocion&action=borrar";
        $_REQUEST['opcion'] = "Borrar";
        $_REQUEST['delete'] = "Y";
        $_REQUEST['listaNiveles'] = montaNiveles($promocion->getPromo_nivel());
        $_REQUEST['listaComunidad'] = montaComunidadExist($promocion->getPromo_comunidad());
        $_REQUEST['listaprovincias']= montaProvinciaExist( $promocion->getPromo_provincia(),$promocion->getPromo_comunidad());
        $_REQUEST['listapoblacion'] = montaPoblacionExist($promocion->getPromo_poblacion(),
            $promocion->getPromo_provincia());
        $_REQUEST['cancelar'] = URL_CANCELACION_PROMOCION;
        return "formulario";
    }
    
    public function montaObjeto($request)
    {
        $promocion = new Promocion();
        $promocion->setPromo_id($request->getParamNumer('promo_id'));
        $promocion->setPromo_nombre($request->getParamSpace('promo_nombre'));
        $promocion->setPromo_descripcion($request->getParamSpace('promo_descripcion'));
        $promocion->setPromo_nivel($request->getParamSpace('promo_nivel'));
        $promocion->setPromo_emergencia(false);
        $promocion->setPromo_fecini_String($request->getParamSpace('promo_fecini'));
        $promocion->setPromo_fecfin_String($request->getParamSpace('promo_fecfin'));
        $promocion->setPromo_comunidad($request->getParamNumer('promo_comunidad',0));
        $promocion->setPromo_provincia($request->getParamNumer('promo_provincia',0));
        $promocion->setPromo_poblacion($request->getParamNumer('promo_poblacion',0));
        $promocion->setPromo_cpostal($request->getParamNumer('promo_cpostal',0));
        $promocion->setPromo_actualizar($request->hasParam('promo_actualizar'));
        $promocion->setPromo_lastupdate(date("Y/m/d H:i:s"));
        return $promocion;
    }

    public function actualizar($request)
    {
        $promocion = $this->montaObjeto($request);
        $this->cDao->update($promocion);
        self::traspasa($promocion);
        return $this->listado();
    }

    public function insertar($request)
    {
        $promocion = $this->montaObjeto($request);
       
        $this->cDao->inserta($promocion);
        $promocion->setPromo_url(ProcesaImagenes::crearCarpetaAuto('promo', $promocion));
        $this->cDao->update($promocion);
        self::traspasa($promocion);
        return $this->listado();
    }

    public function borrar($request)
    {
        $obj = $this->cDao->listPorId((int) $request->getParamNumer('promo_id'));
        self::deleteRegistro($obj);
        $this->cDao->resetAutoIncrement();
        return $this->listado();
    }

//     public static function borrarConjunto($grupo)
//     {
//         $cDao = new DaoPromocion();
//         $listObj = $cDao->listxGrupo($grupo);
//         foreach ($listObj as $obj) {
//             self::deleteRegistro($obj);
//         }
//     }

    public static function deleteRegistro($obj)
    {
        $cDao = new DaoPromocion();
 //       ControllerLocal::borrarConjunto($obj->getId());
        ControllerImagen::borrarConjunto("P", $obj->getId());      
        $cDao->borra($obj);
    }

    public static function traspasa($obj)
    {
        if ($obj->getPromo_actualizar()) {
             $cDao = new DaoPromocion();
             
             switch ($obj->getPromo_nivel()){
                 case 'G':
                     ControllerLocal::actualizarTodos();
                     break;
                 case 'P':
                     ControllerLocal::actualizarXProvincia($obj->getPromo_provincia());
                     break;
                 case 'O':
                     ControllerLocal::actualizarXPoblacion($obj->getPromo_poblacion());
                     break;
                 case 'D':
                     ControllerLocal::actualizarXcPostal($obj->getPromo_cpostal());
                     break;
             };
             $obj->setPromo_actualizar(False);
             $cDao->update($obj);
         }
    }
   
}

