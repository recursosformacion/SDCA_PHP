<?php
namespace controller;

require_once PATH_RAIZ . 'controller/ConstantesControladores.php';
require_once PATH_RAIZ . 'service/ProcesaImagenes.php';
require_once PATH_RAIZ . 'service/Request.php';
require_once PATH_RAIZ . 'modelos/Grupo.php';
require_once PATH_RAIZ . 'dao/DaoGrupo.php';
require_once PATH_RAIZ . 'service/ProcesaImagenes.php';
require_once PATH_RAIZ . 'controller/ControllerImagen.php';
require_once PATH_RAIZ . 'controller/ControllerCliente.php';

use dao\DaoGrupo;
use modelos\Grupo;
use service\ProcesaImagenes;
use service\Request;



class ControllerGrupo
{

    private $cDao;

    private $request;

    function __construct()
    {
        $this->cDao = new DaoGrupo();
        $this->request = new Request();
    }

    public function index()
    {
        echo "<h1>index</h1>";
    }

    /**
     * Listado
     * return String
     * carga $_REQUEST['salida'] con un array de objetos Grupo *
     */
    public function listado()
    {
        $_REQUEST['salida'] = $this->cDao->listAll();
        $_REQUEST['cancelar'] = URL_CANCELACION_GRUPO;
        return "listado";
    }

    /**
     *
     * Deja en $_REQUEST['salida'] el objeto Grupo solicitado
     *
     * @param
     *            Lista de parametros recibidos por GET y POST
     * @return string con nombre de vista
     */
    public function editar($request)
    {
        $_REQUEST['salida'] = $this->cDao->listPorId($request->getParamNumer('id'));
        $_REQUEST['url'] = "?controller=Grupo&action=actualizar";
        $_REQUEST['opcion'] = "Modificar";
        $_REQUEST['cancelar'] = URL_CANCELACION_GRUPO;
        return "formulario";
    }

    public function add($request)
    {
        $_REQUEST['salida'] = new Grupo();
        $_REQUEST['url'] = "?controller=Grupo&action=insertar";
        $_REQUEST['opcion'] = "Añadir";
        $_REQUEST['cancelar'] = URL_CANCELACION_GRUPO;
        return "formulario";
    }

    public function borra($request)
    {
        $_REQUEST['salida'] = $this->cDao->listPorId($request->getParamNumer('id'));
        $_REQUEST['url'] = "?controller=Grupo&action=borrar";
        $_REQUEST['opcion'] = "Borrar";
        $_REQUEST['delete'] = "Y";
        $_REQUEST['cancelar'] = URL_CANCELACION_GRUPO;
        return "formulario";
    }

    public function montaObjeto($request)
    {
        $Grupo = new Grupo();
        $Grupo->setGrupo_id($request->getParam('grupo_id'));
        $Grupo->setGrupo_nombre($request->getParamSpace('grupo_nombre'));
        $Grupo->setGrupo_descripcion($request->getParamSpace('grupo_descripcion'));
        $Grupo->setGrupo_actualizar($request->hasParam('grupo_actualizar'));
        $Grupo->setGrupo_url($request->getParamSpace('grupo_url'));
        $Grupo->setGrupo_lastupdate(date("Y/m/d H:i:s"));
        return $Grupo;
    }

    public function actualizar($request)
    {
        $obj = $this->montaObjeto($request);
        $this->cDao->update($obj);
        self::traspasa($obj);
        return $this->listado();
    }

    public function insertar($request)
    {
        $obj = $this->montaObjeto($request);
        $this->cDao->inserta($obj);
        $obj->setGrupo_url(ProcesaImagenes::crearCarpetaAuto('grupo', $obj));
        $this->cDao->update($obj);
        self::traspasa($obj);
        return $this->listado();
    }

    public function borrar($request)
    {
        $obj = $this->cDao->listPorId((int) $request->getParam('grupo_id'));
        self::deleteRegistro($obj);
        $this->cDao->resetAutoIncrement();
        return $this->listado();
    }

    public static function deleteRegistro($obj)
    {
        $cDao = new DaoGrupo();
        ControllerCliente::borrarConjunto($obj->getId());
        ControllerImagen::borrarConjunto("G", $obj->getId());
        if (substr($obj->getGrupo_url(), 0, 4) != "http") {
            ProcesaImagenes::borraCarpeta($obj->getGrupo_url());
        }
        $cDao->borra($obj);
    }

    public static function traspasa($obj)
    {
        if ($obj->getGrupo_actualizar()) {

            $cDao = new DaoGrupo();
            ControllerCliente::actualizarConjunto($obj->getId());
            $obj->setGrupo_actualizar(False);
            $cDao->update($obj);
        }
    }
}

