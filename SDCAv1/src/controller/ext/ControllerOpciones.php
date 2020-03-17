<?php
namespace controller;

require_once PATH_RAIZ . 'service/Request.php';
require_once PATH_RAIZ . 'modelos/Grupo.php';
require_once PATH_RAIZ . 'modelos/Cliente.php';
require_once PATH_RAIZ . 'modelos/Local.php';
require_once PATH_RAIZ . 'modelos/Cp_provincias.php';
require_once PATH_RAIZ . 'modelos/Opciones.php';

require_once PATH_RAIZ . 'dao/DaoGrupo.php';
require_once PATH_RAIZ . 'dao/DaoCliente.php';
require_once PATH_RAIZ . 'dao/DaoLocal.php';
require_once PATH_RAIZ . 'dao/DaoImagen.php';
require_once PATH_RAIZ . 'dao/DaoProvincia.php';

require_once PATH_RAIZ . 'service/ProcesaImagenes.php';
require_once PATH_RAIZ . 'controller/ControllerLocal.php';
require_once (PATH_RAIZ . 'service/RutinasXml.php');
//require_once (PATH_RAIZ . 'share//RutinasInicio.php');

use modelos\Cliente;
use modelos\Grupo;
use modelos\Local;
use modelos\Opciones;
use service\RutinasXml;
use dao\DaoCliente;
use dao\DaoGrupo;
use dao\DaoImagen;
use dao\DaoLocal;
use service\Request;
use dao\DaoProvincia;
use service\DaoException;



const URL_GENERACION = "?controller=Opciones&action=generacion";
const URL_CANCELACION = "?controller=Local&action=listado";

const NUEVA_PAGINA = '/indexDemo.php';

class ControllerOpciones
{

    private $cDaoLocal;

    private $cDaoCliente;

    private $cDaoGrupo;

    private $cDaoImagen;

    private $cDaoProvincia;

    private $request;

    private $objLocal;

    private $objCliente;

    private $objGrupo;

    private $lista;

    private $xmlImagenes;

    function __construct()
    {
        $this->request = new Request();
        $this->cDaoLocal = new DaoLocal();
        $this->cDaoCliente = new DaoCliente();
        $this->cDaoGrupo = new DaoGrupo();
        $this->cDaoImagen = new DaoImagen();
        $this->cDaoProvincia = new DaoProvincia();
    }

    public function index()
    {
        echo "<h1>Sin tarea asignada</h1>";
    }

    

    /**
     * El objeto Opciones, de momento, no se guarda en ninguna base de datos
     *
     * @param Request $request
     */
    public function pre_generacion($request)
    {
        $this->obtenInformacion($request->getParamNumer('id'));
        $op = new Opciones($this->objGrupo, $this->objCliente, $this->objLocal);
        $op->setId($request->getParamNumer('id'));
        $_REQUEST['salida'] = $op;
        $_REQUEST['salidaGrupo'] = $this->objGrupo;
        $_REQUEST['salidaCliente'] = $this->objCliente;
        $_REQUEST['salidaLocal'] = $this->objLocal;
        $_REQUEST['url'] = URL_GENERACION;
        $_REQUEST['opcion'] = "Generar ficheros";
        $_REQUEST['cancelar'] = URL_CANCELACION;

        return "formulario";
    }

    public function generacion($request)
    {
        $salida = $this->montaTodo($request);
        ob_clean();
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=opciones.ini');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Type: text/plain; charset=utf-8');
        // header('Content-Length: ' . strlen($salida));
        echo $salida;
        ob_flush();
        $cLocal=new ControllerLocal();
        $cLocal->genera_All_SSH();

        exit();
    }

    /**
     * Generacion del fichero opciones.ini
     */
    public function montaTodo($request)
    {
      
        $op = $this->montaObjeto($request);
        return $op->obtenINI();
       
    }

       

    /**
     * utilizando el id de local, prepara los modelos de
     * Local
     * Cliente
     * Grupo
     * @param int $id
     */
    public function obtenInformacion(int $id)
    {
        $this->objLocal = $this->cDaoLocal->listPorId($id);
        $this->objCliente = $this->cDaoCliente->listPorId($this->objLocal->getLocal_cliente());
        $this->objGrupo = $this->cDaoGrupo->listPorId($this->objCliente->getCliente_grupo());
        // $xml=$objGrupo->toXML.$objCliente->toXML.$objLocal->toXML
    }

    /**
     * Monta objeto opciones desde formulario
     *
     * @param
     *            $request
     * @return Opciones
     */
    public function montaObjeto($request): Opciones
    {
        $tienda = $request->getParamNumer('tienda');
        if ($tienda == 0) {
            $tienda = $request->getParamNumer('id');
        }
        $url = $url = $request->getParamSpace('url');
        if ($url == "") {
            $url = $request->getDomain();
        }
        $ruta = $request->getParamSpace('ruta');
        if ($ruta == "") {
            $ruta = RUTA_BASICA;
        }
        $op = new Opciones();
        $op->setId($request->getParamNumer('id'));
        $op->setCliente($request->getParamSpace('cliente'));
        $op->setTienda($tienda);
        $op->setEncender($request->getTime('encender'));
        $op->setApagar($request->getTime('apagar'));
        $op->setUrl($url);
        $op->setRuta($ruta);
        $op->setTipo($request->getParamSpace('tipo'));
        $op->setOculta($request->getParamSpace('redOculta'));
        $op->setWfNombre($request->getParamSpace('wfNombre'));
        $op->setWfSeguridad($request->getParamSpace('wfSeguridad'));
        $op->setWfUsuario($request->getParamSpace('wfUsuario'));
        $op->setWfPassword($request->getParamSpace('wfPassword'));

        return $op;
    }

    
    
    
}

