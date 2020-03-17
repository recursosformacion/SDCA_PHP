<?php
namespace controller;


require_once (realpath(dirname(dirname(__FILE__))) . "/config/init.php");
require_once PATH_RAIZ . 'controller/ConstantesControladores.php';
require_once PATH_RAIZ . 'dao/DaoProvincia.php';
require_once PATH_RAIZ . 'dao/DaoPoblacion.php';
require_once PATH_RAIZ . 'dao/DaoCodigoPostal.php';
require_once (PATH_RAIZ . 'service/Request.php');
require_once (PATH_RAIZ . 'share/MiErrorLog.php');
set_error_handler("gestionLog");

use dao\DaoProvincia;
use dao\DaoPoblacion;
use dao\DaoCodigoPostal;
use service\Request;

class AjaxController
{

    private $request;

    function __construct()
    {
        $this->request = new Request();
    }

    /**
     * Envia lista de poblaciones en una provincia
     * o solo existentes
     *
     * @param int $provincia
     *            consultada
     */
    public static function cargaMunicipios($provincia)
    {
        $cDaoPoblacion = new DaoPoblacion();
        enviaPoblacionXML($cDaoPoblacion->listConWhere("cppro_id = " . $provincia));
    }

    public static function cargaMunicipiosExist($provincia)
    {
        $cDaoPoblacion = new DaoPoblacion();
        $where = preparaSelectPoblacionExist($provincia);
        enviaPoblacionXML($cDaoPoblacion->listConWhere($where));
    }

    /**
     * Envia lista de provincias de una Comunidad
     *
     * @param int $provincia
     *            consultada
     */
    public static function cargaProvincias($ca)
    {
        $cDaoProvincia = null;
        $cDaoProvincia = new DaoProvincia();

        if ($ca == "999999999")
            $lasprovincias = $cDaoProvincia->listAll();
        else
            $lasprovincias = $cDaoProvincia->listConWhere("cppro_codca = " . $ca);
        enviaProvinciaXML($lasprovincias);
    }

    public static function cargaProvinciasCP($cpostal)
    {
        $cDaoCodPostal = null;
        $elementos_xml = [];
        header("Content-Type: application/xml");
        $cDaoCodPostal = new DaoCodigoPostal();
        $cPostales = $cDaoCodPostal->listPorId($cpostal);

        $elementos_xml[] = "<provincia>\n<codigo>" . $cPostales->getCppro_id() . "</codigo>\n<municipio>" . $cPostales->getCppob_id() . "</municipio>\n</provincia>";
        echo "<provincias>\n" . implode("\n", $elementos_xml) . "\n</provincias>";
    }

    public static function cargaProvinciasExist($comunidad)
    {
        $cDaoProvincia = new DaoProvincia();
        $where = preparaSelectProvinciaExist($comunidad);
        enviaProvinciaXML($cDaoProvincia->listConWhere($where));
    }

    public static function cargaLocales($request)
    {
        ControllerAdmin::obtenListado($request);
    }
    
}
$provincia = 0;
$codca = 0;
$cpostal = 0;
$exist = 0;

$request = new Request();

/**
 * Si viene parametro provincia, se entregan todos los municipios de la provincia
 * Si viene codca y no es 999999999 se entregan las provincias de la comunidad; si es 999999999, se entregan todas las provincias
 * Si viene cpostal, se entrega uno de los codigos de provincia y poblacion de ese c.
 * postal
 */
if (isset($_GET["provincia"]))
    $provincia = $_GET["provincia"];
if (isset($_POST["provincia"]))
    $provincia = $_POST["provincia"];

if (isset($_GET["codca"]))
    $codca = $_GET["codca"];
if (isset($_POST["codca"]))
    $codca = $_POST["codca"];

if (isset($_GET["cpostal"]))
    $cpostal = $_GET["cpostal"];
if (isset($_POST["cpostal"]))
    $cpostal = $_POST["cpostal"];

if (isset($_GET["exist"]))
    $exist = $_GET["exist"];
if (isset($_POST["exist"]))
    $exist = $_POST["exist"];

if ((isset($_GET["comunidad"]) && isset($_GET["provincia"]) && isset($_GET["poblacion"]) && isset($_GET["codigo_postal"])) 
    || (isset($_POST["comunidad"]) && isset($_POST["provincia"]) && isset($_POST["poblacion"]) && isset($_POST["codigo_postal"]))) {
   
        cargaDatosLocalizacion($request);
}
if (isset($_GET["action"])) {
    if ($_GET["action"] == "locales") {
        ControllerAdmin::generaListadoJSON($request);
        exit();
    }
}

if ($provincia > 0) {
    if ($exist == 0)
        AjaxController::cargaMunicipios($provincia);
    else
        AjaxController::cargaMunicipiosExist($provincia);
}
if ($codca > 0) {
    if ($exist == 0)
        AjaxController::cargaProvincias($codca);
    else
        AjaxController::cargaProvinciasExist($codca);
}
if ($cpostal > 0) {
    AjaxController::cargaProvinciasCP($cpostal);
}