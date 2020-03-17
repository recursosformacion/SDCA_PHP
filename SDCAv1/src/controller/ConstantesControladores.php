<?php
//require_once PATH_RAIZ . 'dao/DaoCliente.php';
//require_once PATH_RAIZ . 'dao/DaoGrupo.php';
require_once PATH_RAIZ . 'dao/DaoProvincia.php';
require_once PATH_RAIZ . 'dao/DaoPoblacion.php';
require_once PATH_RAIZ . 'dao/DaoComunidades.php';
//require_once PATH_RAIZ . 'dao/DaoLocal.php';
//require_once PATH_RAIZ . 'modelos/Cliente.php';
//require_once PATH_RAIZ . 'modelos/Grupo.php';
require_once PATH_RAIZ . 'modelos/Cp_provincias.php';
require_once PATH_RAIZ . 'modelos/Cp_poblacion.php';
require_once PATH_RAIZ . 'modelos/Cp_comunidades.php';
// require_once PATH_RAIZ . 'controller/ControllerAdmin.php';


const URL_CANCELACION_CLIENTE = "?controller=Cliente&action=listado";

const URL_CANCELACION_GRUPO = "?controller=Grupo&action=listado";

const URL_CANCELACION_LOCAL = "?controller=Local&action=listado";

const URL_CANCELACION_IMAGEN = "?controller=imagen&action=imagenes";

const URL_CANCELACION_PROMOCION = "?controller=Promocion&action=listado";

//++++++ Variables Session
const GUARDA_CONTROL_LOCALES = "guardaControlLocales";

const NIVELES = [
    'G' => 'General',
    'C' => 'Comunidad autonoma',
    'P' => 'Provincia',
    'O' => 'Poblacion',
    'D' => 'Codigo  postal'
];

// use controller\ControllerAdmin;
use dao\DaoProvincia;
use dao\DaoPoblacion;
// use dao\DaoCliente;
use dao\DaoComunidades;
use modelos\Cp_provincias;
use modelos\Cp_poblacion;
use modelos\Cp_comunidades;



function montaPoblacion1($actual)
{
    if ($actual == 0) {
        $respuesta = "";
    } else {
        $cDaoPoblacion = new DaoPoblacion();
        $modeloPoblacion = new Cp_poblacion();
        $respuesta = $cDaoPoblacion->obtenSelectPosicionado($modeloPoblacion->getSelect(), $actual);
    }
    return $respuesta;
}

function montaPoblacionxProvincia($actual, $provincia)
{
    $cDaoPoblacion = new DaoPoblacion();
    $modeloPoblacion = new Cp_poblacion();
    $respuesta = $cDaoPoblacion->obtenSelectxPartes($modeloPoblacion->getSelect(), $actual, "cppro_id=" . $provincia);
    return $respuesta;
}

function montaProvincia($actual)
{
    $cDaoProvincia = new DaoProvincia();
    $modeloProvincia = new Cp_provincias();
    $respuesta = $cDaoProvincia->obtenSelect($modeloProvincia->getSelect(), $actual);
    return $respuesta;
}

function montaComunidad($actual)
{
    $modeloComunidad = new Cp_comunidades();
    $cDaoComunidades = new DaoComunidades();
    $respuesta = $cDaoComunidades->obtenSelect($modeloComunidad->getSelect(), $actual);
    return $respuesta;
}
/*
 * Seleccion por existentes...para Promociones y emergencias
 */
function montaPoblacionExist($actual, $provincia)
{
    $where = preparaSelectPoblacionExist($provincia);
    return generaSelectPoblacion($actual, $provincia, $where);
}

function preparaSelectPoblacionExist($provincia)
{
//    $daoLocal = new DaoLocal();
//    $lista = $daoLocal->sql(DaoLocal::SELECT_LIMITE_POBLA);
    return "cppro_id=" . $provincia . " AND id in (" . implodeLista($lista) . ")";
}

function generaSelectPoblacion($actual, $provincia, $where)
{
    $cDaoPoblacion = new DaoPoblacion();
    $modeloPoblacion = new Cp_poblacion();
    $respuesta = $cDaoPoblacion->obtenSelectxPartes($modeloPoblacion->getSelect(), $actual, $where);
    return $respuesta;
}

function montaProvinciaExist($actual, $comunidad)
{
    $where = preparaSelectProvinciaExist($comunidad);
    return generaSelectProvincia($actual, $comunidad, $where);
}

function preparaSelectProvinciaExist($comunidad)
{
//     $daoLocal = new DaoLocal();
//     $lista = $daoLocal->sql(DaoLocal::SELECT_LIMITE_PROVIN);

    $where= "cppro_codca=" . $comunidad . " AND cppro_id in (" . implodeLista($lista) . ")";
    return $where;
}

function generaSelectProvincia($actual, $comunidad, $where)
{
    $cDaoProvincia = new DaoProvincia();
    $modeloProvincia = new Cp_provincias();
    $respuesta = $cDaoProvincia->obtenSelectxPartes($modeloProvincia->getSelect(), $actual, $where);
    return $respuesta;
}

function montaComunidadExist($actual)
{
//     $daoLocal = new DaoLocal();
//     $lista = $daoLocal->sql(DaoLocal::SELECT_LIMITE_PROVIN); // lista provincias que existen
    $where = "cppro_id in (" . implodeLista($lista) . ")";
    $lista = $daoLocal->sql(DaoProvincia::SELECT_LIMITE_COMUNIDAD . ' WHERE ' . $where); // lista provincias que existen
    $modeloComunidad = new Cp_comunidades();
    $cDaoComunidades = new DaoComunidades();
    $where = "cpcoa_id in (" . implodeLista($lista) . ")";
    $respuesta = $cDaoComunidades->obtenSelectxPartes($modeloComunidad->getSelect(), $actual, $where);
    return $respuesta;
}

function implodeLista($lista)
{
    $salida = "";
    foreach ($lista as $clave => $valor) {
        if ($salida != "")
            $salida .= ",";
        $salida .= $valor[0];
    }
    return $salida;
}

function montaNiveles($nivel)
{
    $salida = "";
    foreach (NIVELES as $opcion => $valor) {
        $salida .= '<option value="' . $opcion . '" ';
        if ($nivel == $opcion) {
            $salida .= 'selected';
        }
        $salida .= '>' . $valor . '</option>';
    }
    return $salida;
}

/**
 * Recibe la lista de municipios a enviar...
 * y los envia en XML
 */
function enviaPoblacionXML($losMunicipios)
{
    header("Content-Type: application/xml");
    $elementos_xml = [];
    foreach ($losMunicipios as $poblacion) {
        $elementos_xml[] = "<municipio>" . 
                "<codigo>" .$poblacion->getid() . "</codigo>" . 
                "<nombre>" . $poblacion->getCppob_nombre() . "</nombre>" . 
                "<cpro>" . $poblacion->getCppob_id() . "</cpro>" . 
        "</municipio>";
    }

    echo "<municipios>\n" . implode("\n", $elementos_xml) . "</municipios>";
}
/**
 * Recibe la lista de provincias a enviar...
 * y los envia en XML
 */
function enviaProvinciaXML($lasprovincias)
{
    $elementos_xml = [];
    header("Content-Type: application/xml");

    foreach ($lasprovincias as $provincia) {
        $elementos_xml[] = "<provincia>\n<codigo>" . $provincia->getCppro_id() . 
                        "</codigo>\n<nombre>" . $provincia->getCppro_nombre() . "</nombre>\n</provincia>";
    }
    echo "<provincias>\n" . implode("\n", $elementos_xml) . "\n</provincias>";
}


