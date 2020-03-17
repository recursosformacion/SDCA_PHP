<?php
namespace controller;

require_once PATH_RAIZ . 'controller/ConstantesControladores.php';


use service\Request;

use DateTime;

use service\RutinasServidor;

class ControllerAdmin
{

    private $cDao;

    private $request;

    public static $datosControlLocales = array(
        "comunidad" => 0,
        "provincia" => 0,
        "poblacion" => 0,
        "codigo_postal" => 0
    );

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
    public function listado($request)
    {
        logKioskoWeb("Admin-listado", $request);

        if (isset($_SESSION[GUARDA_CONTROL_LOCALES])) {
            $control = $_SESSION[GUARDA_CONTROL_LOCALES];
        } else {
            $control = self::$datosControlLocales;
        }
        $_REQUEST['salida'] = self::obtenListado($this->request);
        $_REQUEST['cancelar'] = URL_CANCELACION_LOCAL;
        $_REQUEST['opcion'] = "Filtrar";
        $_REQUEST['control'] = $control;
        $_REQUEST['listaComunidad'] = montaComunidadExist($control['comunidad']);
        $_REQUEST['listaprovincias'] = "";
        $_REQUEST['listapoblacion'] = "";
        return "listado";
    }

    public static function obtenListado($request)
    {
        $cDao = new DaoLocal();
        $criterio = self::obtenCriterio($request);
        if ($criterio == "")
            $lista = $cDao->listAll();
        else {
            $lista = $cDao->listConWhere($criterio);
        }
        $lc = count($lista);
        for ($i = 0; $i < $lc; $i ++) {
            $id = $lista[$i]->getLocal_id();
            $puerto = 30000 + ($id * 2) - 1;
            $lista[$i]->setLocal_direccion2(RutinasServidor::comprobarPuerto($puerto));
            $lista[$i]->setLocal_nombre($puerto.'/'.$lista[$i]->getLocal_nombre());
        }
        return ControllerLocal::listaToArray($lista);
    }

    public static function obtenCriterio($request)
    {
        if (isset($_SESSION[GUARDA_CONTROL_LOCALES])) {
            $control = $_SESSION[GUARDA_CONTROL_LOCALES];
        } else {
            $control = self::$datosControlLocales;
        }

        $criterio = "";
        If ($control['comunidad'] != 0)
            $criterio = "cppro_codca = " . $control['comunidad'];
        If ($control['provincia'] != 0)
            $criterio = "local_provincia = " . $control['provincia'];
        If ($control['poblacion'] != 0)
            $criterio = "local_poblacion = " . $control['poblacion'];
        If ($control['codigo_postal'] != 0)
            $criterio = "local_cp = " . $control['codigo_postal'];
        return $criterio;
    }

    public static function generaListadoJSON($request)
    {
        $lista = self::obtenListado($request);
        header('Content-Type: application/json');
        echo json_encode($lista);
        exit();
    }

    public function actA($request)
    { // Actualizar byte actualizar ID y true/false
        $cDao = new DaoLocal();
        // logKiosko("Al grabar:id=".$request->getParamNumer('id') ." act=" . $request->getParam('act'));
        $cDao->actualizaActualizar($request->getParamNumer('id'), $request->getParam('act'));
        $obj = $cDao->listPorId($request->getParamNumer('id'));
        $respuesta = [];
        $respuesta['idHTML'] = $request->getParam('idHTML');
        $respuesta['id'] = $obj->getLocal_id();
        $respuesta['act'] = $obj->getLocal_actualizar();
        header('Content-Type: application/json');
        echo json_encode($respuesta);
        exit();
    }
}


