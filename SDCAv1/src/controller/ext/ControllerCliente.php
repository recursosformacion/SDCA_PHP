<?php
namespace controller;

require_once PATH_RAIZ . 'controller/ConstantesControladores.php';
require_once PATH_RAIZ . 'service/Request.php';
require_once PATH_RAIZ . 'modelos/Cliente.php';
require_once PATH_RAIZ . 'service/ProcesaImagenes.php';
require_once PATH_RAIZ . 'dao/DaoCliente.php';
require_once PATH_RAIZ . 'controller/ControllerLocal.php';

use service\Request;
use service\ProcesaImagenes;
use dao\DaoCliente;

use modelos\Cliente;



class ControllerCliente
{

    private $cDao;

    private $request;

   

    function __construct()
    {
        $this->cDao = new DaoCliente();
        $this->request = new Request();
    }

    public function index()
    {
        echo "<h1>index</h1>";
    }

    /**
     * Listado
     * return String
     * carga $_REQUEST['salida'] con un array de objetos Cliente *
     */
    public function listado()
    {
        $_REQUEST['salida'] = $this->cDao->listAll();
        $_REQUEST['cancelar'] = URL_CANCELACION_CLIENTE;
        return "listado";
    }

    /**
     *
     * Deja en $_REQUEST['salida'] el objeto Cliente solicitado
     *
     * @param
     *            Lista de parametros recibidos por GET y POST
     * @return string con nombre de vista
     */
    public function editar($request)
    {
        $cliente= $this->cDao->listPorId($request->getParamNumer('id'));
        $_REQUEST['salida'] =$cliente;
        $_REQUEST['url'] = "?controller=Cliente&action=actualizar";
        $_REQUEST['opcion'] = "Modificar";
        $_REQUEST['listaGrupos'] = montaGrupo($cliente->getCliente_grupo());
        $_REQUEST['listaprovincias']= montaProvincia( $cliente->getCliente_provincia());
        $_REQUEST['listapoblacion'] = montaPoblacionxProvincia($cliente->getCliente_poblacion(),
            $cliente->getCliente_provincia());
        $_REQUEST['cancelar'] = URL_CANCELACION_CLIENTE;
        return "formulario";
    }

    public function add($request)
    {
        $_REQUEST['salida'] = new Cliente();
        $_REQUEST['url'] = "?controller=Cliente&action=insertar";
        $_REQUEST['opcion'] = "Añadir";
        $_REQUEST['listaGrupos'] = montaGrupo(0);
        $_REQUEST['listaprovincias']= montaProvincia( 0);
        $_REQUEST['listapoblacion'] =montaPoblacion1( 0);
        $_REQUEST['cancelar'] = URL_CANCELACION_CLIENTE;
        return "formulario";
    }

    public function borra($request)
    {
        $_REQUEST['salida'] = $this->cDao->listPorId($request->getParamNumer('id'));
        $_REQUEST['url'] = "?controller=Cliente&action=borrar";
        $_REQUEST['opcion'] = "Borrar";
        $_REQUEST['delete'] = "Y";
        $_REQUEST['listaGrupos'] = montaGrupo($_REQUEST['salida']->getCliente_grupo());
        $_REQUEST['listaprovincias']= montaProvincia( $_REQUEST['salida']->getCliente_provincia());
        $_REQUEST['listapoblacion'] = montaPoblacion1( $_REQUEST['salida']->getCliente_poblacion());
        $_REQUEST['cancelar'] = URL_CANCELACION_CLIENTE;
        return "formulario";
    }
    
    public function montaObjeto($request)
    {
        $Cliente = new Cliente();
        $Cliente->setCliente_id($request->getParamNumer('cliente_id'));
        $Cliente->setCliente_nombre($request->getParamSpace('cliente_nombre'));
        $Cliente->setCliente_direccion1($request->getParamSpace('cliente_direccion1'));
        $Cliente->setCliente_direccion2($request->getParamSpace('cliente_direccion2'));
        $Cliente->setCliente_poblacion($request->getParamSpace('cliente_poblacion'));
        $Cliente->setCliente_cp($request->getParamSpace('cliente_cp'));
        $Cliente->setCliente_provincia($request->getParamSpace('cliente_provincia'));
        $Cliente->setCliente_nom_comercial($request->getParamSpace('cliente_nom_comercial'));
        $Cliente->setCliente_grupo($request->getParamSpace('cliente_grupo'));
        $Cliente->setCliente_actualizar($request->hasParam('cliente_actualizar'));
        $Cliente->setCliente_url($request->getParamSpace('cliente_url'));
        $Cliente->setCliente_lastupdate(date("Y/m/d H:i:s"));
        return $Cliente;
    }

    public function actualizar($request)
    {
        $cliente = $this->montaObjeto($request);
        $this->cDao->update($cliente);
        self::traspasa($cliente);
        return $this->listado();
    }

    public function insertar($request)
    {
        $obj = $this->montaObjeto($request);
        $obj->setCliente_actualizar(False); // se ha puesto el otro a true manualmente
        $this->cDao->beginTransaction();
        $this->cDao->inserta($obj);
        $obj->setCliente_url(ProcesaImagenes::crearCarpetaAuto('cliente', $obj));
        $this->cDao->update($obj);
        $objLocal = new ControllerLocal();
        $objLocal->altaEncadenadal($obj);
        $this->cDao->commit();
        return $this->listado();
    }

    public function borrar($request)
    {
        $obj = $this->cDao->listPorId((int) $request->getParamNumer('cliente_id'));
        self::deleteRegistro($obj);
        $this->cDao->resetAutoIncrement();
        return $this->listado();
    }

    public static function borrarConjunto($grupo)
    {
        $cDao = new DaoCliente();
        $listObj = $cDao->listxGrupo($grupo);
        foreach ($listObj as $obj) {
            self::deleteRegistro($obj);
        }
    }

    public static function deleteRegistro($obj)
    {
        $cDao = new DaoCliente();
        ControllerLocal::borrarConjunto($obj->getId());
        ControllerImagen::borrarConjunto("C", $obj->getId());
        if (substr($obj->getCliente_url(), 0, 4) != "http") {
            ProcesaImagenes::borraCarpeta($obj->getCliente_url());
        }
        $cDao->borra($obj);
    }

    public static function actualizarConjunto($id)
    {
        $cDao = new DaoCliente();
        $listObj = $cDao->listxGrupo($id);
        foreach ($listObj as $obj) {
            self::actualizarAct($obj);
        }
    }

    public static function actualizarAct($obj)
    {
        $obj->setCliente_actualizar(true);
        // $this->cDao->update($obj);
        self::traspasa($obj);
    }

    public static function traspasa($obj)
    {
        if ($obj->getCliente_actualizar()) {
            $cDao = new DaoCliente();
            ControllerLocal::actualizarConjunto($obj->getId());
            $obj->setCliente_actualizar(False);
            $cDao->update($obj);
        }
    }
}

