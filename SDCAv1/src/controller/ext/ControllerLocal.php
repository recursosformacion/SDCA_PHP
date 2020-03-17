<?php
namespace controller;

require_once PATH_RAIZ . 'controller/ConstantesControladores.php';
require_once PATH_RAIZ . 'controller/ControllerImagen.php';
require_once PATH_RAIZ . 'service/Request.php';
require_once PATH_RAIZ . 'modelos/Local.php';
require_once PATH_RAIZ . 'service/ProcesaImagenes.php';
require_once PATH_RAIZ . 'dao/DaoLocal.php';
require_once PATH_RAIZ . 'dao/DaoPoblacion.php';
require_once PATH_RAIZ . 'share/RutinasFicheros.php';
require_once PATH_RAIZ . 'share/RutinasEjecucion.php';
require_once PATH_RAIZ . 'service/RutinasFirewall.php';

use service\DaoException;
use service\Request;
use service\ProcesaImagenes;
use service\RutinasFirewall;
use modelos\Cliente;
use modelos\Local;
use dao\DaoLocal;
use dao\DaoPoblacion;
use Exception;
use RutinasEjecucion;
use RutinasFicheros;

class ControllerLocal
{

    private $cDao;

    private $request;

    function __construct()
    {
        $this->cDao = new DaoLocal();
        $this->request = new Request();
    }

    public function index()
    {
        echo "<h1>index</h1>";
    }

    /**
     * Listado
     * return String
     * carga $_REQUEST['salida'] con un array de objetos Local *
     */
    public function listado()
    {
        $_REQUEST['salida'] = $this->cDao->listAll();
        $_REQUEST['cancelar'] = URL_CANCELACION_LOCAL;
        return "listado";
    }

    public function genera_All_SSH()
    {
        $salida = "";
        $lista = $this->cDao->listAll();
        foreach ($lista as $local) {
            $port = 30000 + ($local->getId() * 2) - 1;
            $salida .= "\nHost LOCAL-" . $local->getLocal_idAjus();
            $salida .= "\n\t User pi";
            $salida .= "\n\t Hostname localhost";
            $salida .= "\n\t Port " . $port;
            $salida .= "\n\t ForwardAgent= yes";
            $salida .= "\n\t ForwardX11=yes";
            $salida .= "\n\t StrictHostKeyChecking=no";
            $salida .= "\n\t UserKnownHostsFile=/dev/null";
            $salida .= "\n\n";
        }
        RutinasFicheros::grabarWithPermission($salida, RUTA_USUARIO_VPN . '.ssh/config');
        if (USUARIO_VPN != get_current_user()) {
            RutinasEjecucion::ejecutar('chown ' . USUARIO_VPN . ':' . USUARIO_VPN . ' ' . RUTA_USUARIO_VPN . '.ssh/config');
            RutinasEjecucion::ejecutar('chmod 600 ' . RUTA_USUARIO_VPN . '.ssh/config');
        }
    }

    /**
     *
     * Deja en $_REQUEST['salida'] el objeto Local solicitado
     *
     * @param
     *            Lista de parametros recibidos por GET y POST
     * @return string con nombre de vista
     */
    public function editar($request)
    {
        $local = $this->cDao->listPorId($request->getParamNumer('id'));
        $_REQUEST['salida'] = $local;
        $_REQUEST['url'] = "?controller=Local&action=actualizar";
        $_REQUEST['opcion'] = "Modificar";
        $_REQUEST['listaprovincias'] = montaProvincia($local->getLocal_provincia());
        $_REQUEST['listapoblacion'] = montaPoblacionxProvincia($local->getLocal_poblacion(), $local->getLocal_provincia());
        $_REQUEST['listaclientes'] = montaCliente($local->getLocal_cliente());
        $_REQUEST['cancelar'] = URL_CANCELACION_LOCAL;
        return "formulario";
    }

    public function add($request)
    {
        $_REQUEST['salida'] = new Local();
        $_REQUEST['url'] = "?controller=Local&action=insertar";
        $_REQUEST['opcion'] = "Añadir";

        $_REQUEST['listaprovincias'] = montaProvincia(0);
        $_REQUEST['listapoblacion'] = montaPoblacion1(0);
        $_REQUEST['listaclientes'] = montaCliente(0);
        $_REQUEST['cancelar'] = URL_CANCELACION_LOCAL;
        return "formulario";
    }

    public function borra($request)
    {
        $_REQUEST['salida'] = $this->cDao->listPorId($request->getParamNumer('id'));
        $_REQUEST['url'] = "?controller=Local&action=borrar";
        $_REQUEST['opcion'] = "Borrar";
        $_REQUEST['delete'] = "Y";

        $_REQUEST['listaprovincias'] = montaProvincia($_REQUEST['salida']->getLocal_provincia());
        $_REQUEST['listapoblacion'] = montaPoblacion1($_REQUEST['salida']->getLocal_poblacion());
        $_REQUEST['listaclientes'] = montaCliente($_REQUEST['salida']->getLocal_cliente());
        $_REQUEST['cancelar'] = URL_CANCELACION_LOCAL;
        return "formulario";
    }

    public function montaObjeto($request)
    {
        try {
            if ($request->getParamNumer('local_id') > 0) {
                $local = $this->cDao->listPorId($request->getParamNumer('local_id'));
            } else {
                $local = new Local();
            }
            $local->setLocal_cliente($request->getParam('local_cliente'));
            $local->setLocal_nombre($request->getParamSpace('local_nombre'));
            $local->setLocal_direccion1($request->getParamSpace('local_direccion1'));
            $local->setLocal_direccion2($request->getParamSpace('local_direccion2'));
            $local->setLocal_poblacion($request->getParamSpace('local_poblacion'));
            $local->setLocal_cp($request->getParamSpace('local_cp'));
            $local->setLocal_provincia($request->getParamSpace('local_provincia'));
            $local->setLocal_nom_comercial($request->getParamSpace('local_nom_comercial'));
            $local->setLocal_actualizar($request->hasParam('local_actualizar'));
            $local->setLocal_url($request->getParamSpace('local_url'));
            $local->setLocal_lastupdate(date("Y/m/d H:i:s"));
        } catch (Exception $e) {
            logKioskoWebError($e->getLine() . "-" . $e->getMessage(), $request, $e);
        }
        return $local;
    }

    public function actualizar($request)
    {
        $local=$this->montaObjeto($request);
        try {
            $this->cDao->update($local);
            return $this->listado();
        } catch (DaoException $e) {
            $this->cDao->rollback();
            echo "************************************************************************************************************************";
            $request->setParam('id',$local->getid());
            $_REQUEST['msg'] = 'Error acceso a datos: '. $e->getMessage();
            return $this->editar($request);
        }
    }

    /**
     * Recibe un cliente y da de alta el Primer Local
     *
     * @param Cliente $obj
     * @return \modelos\Local
     */
    public function altaEncadenadal(Cliente $obj)
    {
        $local = new Local();
        $local->setLocal_id(0);
        $local->setLocal_cliente($obj->getCliente_id());
        $local->setLocal_nombre($obj->getCliente_nombre());
        $local->setLocal_direccion1($obj->getCliente_direccion1());
        $local->setLocal_direccion2($obj->getCliente_direccion2());
        $local->setLocal_poblacion($obj->getCliente_poblacion());
        $local->setLocal_cp($obj->getCliente_cp());
        $local->setLocal_provincia($obj->getCliente_provincia());
        $local->setLocal_nom_comercial($obj->getCliente_nom_comercial());
        $local->setLocal_actualizar(true);
        $local->setLocal_url("");
        $local->setLocal_lastupdate(date("Y/m/d H:i:s"));

        $this->cDao->inserta($local);
        $local->setLocal_url(ProcesaImagenes::crearCarpetaAuto('local', $local));
        $this->cDao->update($local);
    }

    public function insertar($request)
    {
        try {

            $this->cDao->beginTransaction();
            $obj = $this->montaObjeto($request);
            $this->cDao->inserta($obj);
            $obj->setLocal_url(ProcesaImagenes::crearCarpetaAuto('local', $obj));
            $this->cDao->update($obj);
            $this->cDao->commit();
            return $this->listado();
        } catch (Exception $e) {

            logKioskoError($e);
            $this->cDao->rollback();
            return $this->add();
        }
    }

    public function borrar($request)
    {
        try {
            $obj = $this->cDao->listPorId($request->getParam('local_id'));
            self::deleteRegistro($obj);
            return $this->listado();
        } catch (Exception $e) {

            logKioskoError($e);
            $this->cDao->rollback();
            return $this->borra();
        }
    }

    public static function borrarConjunto($cliente)
    {
        $cDao = new DaoLocal();
        $listObj = $cDao->listCliente($cliente);
        foreach ($listObj as $obj) {
            self::deleteRegistro($obj);
        }
    }

    public static function deleteRegistro($obj)
    {
        $cDao = new DaoLocal();
        ControllerImagen::borrarConjunto("L", $obj->getId());
        if (NULL === $obj->getLocal_url() && (substr($obj->getLocal_url(), 0, 4) != "http") && ($obj->getLocal_url() != "")) {
            ProcesaImagenes::borraCarpeta($obj->getLocal_url());
        }

        $cDao->borra($obj);
        $cDao->resetAutoIncrement();
    }

    public static function actualizarConjunto($id)
    {
        $cDao = new DaoLocal();
        self::actualizaLista($cDao->listCliente($id));
    }

    public static function actualizarConWhere($where)
    {
        $cDao = new DaoLocal();
        self::actualizaLista($cDao->listConWhere($where));
    }

    public static function actualizarAct($obj)
    {
        $cDao = new DaoLocal();
        $cDao->actualizaActualizar($obj->getLocal_id(), true);
    }

    public static function actualizarOff($obj)
    {
        $cDao = new DaoLocal();
        $cDao->actualizaActualizar($obj->getLocal_id(), false);
    }

    public static function actualizarTodos()
    {
        $cDao = new DaoLocal();
        self::actualizaLista($cDao->listAll());
    }

    public static function actualizarXProvincia($provin)
    {
        self::actualizarConWhere('local_provincia = ' . $provin);
    }

    public static function actualizarXPoblacion($pobla)
    {
        self::actualizarConWhere('local_poblacion = ' . $pobla);
    }

    public static function actualizarXcPostal($postal)
    {
        self::actualizarConWhere('local_cp = ' . $postal);
    }

    public static function actualizaLista($listObj)
    {
        foreach ($listObj as $obj) {
            self::actualizarAct($obj);
        }
    }

    /**
     * Actualizacion de los datos de conexion
     *
     * @param
     *            $request
     */
    public static function actConexion($request)
    {
        if ($request->getParamNumer('id') == 0)
            return;

        if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) { // correccion por CloudFlare
            $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
        }
        // if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        // $ip = $_SERVER['HTTP_CLIENT_IP'];
        // } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        // $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        // } else {
        $ip = $_SERVER['REMOTE_ADDR'];
        // }

        try {
            $cDao = new DaoLocal();
            $local = $cDao->listPorId($request->getParamNumer('id'));
            $local->setLocal_lastconexion(date("Y/m/d H:i:s"));
            if ($local->getLocal_lastip() != $ip) { // ha cambiado la IP, cambia la proteccion de csf
                if (null != $local->getLocal_lastip() && $local->getLocal_lastip() != "") {
                    logKiosko("Cambiamos IP de " . $local->getLocal_lastip() . " a " . $ip);
                    RutinasFirewall::desprotegerIP($local->getLocal_lastip());
                }
                RutinasFirewall::protegeIP($ip);
            }
            $local->setLocal_lastip($ip);
            $cDao->update($local);
        } catch (Exception $e) {
            logKioskoWeb($e->getLine() . "-" . $e->getMessage(), $request);
        }
    }

    public static function listaToArray($lista)
    {
        $todos = array();
        foreach ($lista as $local) {
            $este = $local->getArrayMas();
            $poblaCli = ((new DaoPoblacion())->listPorId($local->getLocal_poblacion()))->getCppob_nombre();
            $este['poblacion'] = $poblaCli;
            $todos[] = $este;
        }
        return $todos;
    }
}

