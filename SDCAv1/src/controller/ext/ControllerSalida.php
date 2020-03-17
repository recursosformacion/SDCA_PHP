<?php
namespace controller;



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
require_once PATH_RAIZ . 'dao/DaoPromocion.php';

require_once PATH_RAIZ . 'controller/ControllerLocal.php';
require_once PATH_RAIZ . 'controller/ConstantesControladores.php';

require_once PATH_RAIZ . 'service/ProcesaImagenes.php';
require_once PATH_RAIZ . 'service/Request.php';
require_once (PATH_RAIZ . 'service/RutinasXml.php');
require_once (PATH_RAIZ . 'service//RutinasServidor.php');



use dao\DaoCliente;
use dao\DaoGrupo;
use dao\DaoImagen;
use dao\DaoLocal;
use dao\DaoPromocion;
use dao\DaoProvincia;
use modelos\Opciones;
use service\DaoException;
use service\Request;
use service\RutinasServidor;
use service\RutinasXml;

const URL_GENERACION = "?controller=Salida&action=generacion";

const NUEVA_PAGINA = '/indexDemo.php';

class ControllerSalida
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

    /** Prepara la lista de todas las imagenes de un local *************************************
     * 
     * @param array $request
     * @return string
     */
    public function demo($request)
    {
        $this->obtenInformacion($request->getParam('id'));
        $this->obtenLista();
        $_REQUEST['imagenes'] = $this->lista;
        $_REQUEST['objeto'] = $this->preparaObj($request);
        $_REQUEST['cancelar'] = URL_CANCELACION_LOCAL;
        return "galeria";
    }

    /**
     * Solicitar todas las imagenes de un local determinado ************************************
     * @param  $request
     */
    public function pedirImagenes($request)
    {
        logKioskoWeb('Salida-pedirImagenes',$request);
        $this->obtenInformacion($request->getParam('id'));
        $this->obtenLista();
        if (null != $this->xmlImagenes) {
            echo RutinasXml::arrayToXML($this->xmlImagenes, "Imagenes");
        } else {
            echo "";
        }
        ControllerLocal::actConexion($request);

        exit();
    }

    /**
     *  envia lista de imagenes, y apaga el switch de actualizacion **********************************
     * */
    public function actualizacion($request)
    {
        logKioskoWeb("Salida-Actualizacion ",$request);
        echo $this->preparaObj($request);
        ControllerLocal::actualizarOff($this->objLocal);
        ControllerLocal::actConexion($request);
        exit();
    }

    public function preparar($request)
    {
        logKioskoWeb("Salida-preparar",$request);
        $this->obtenInformacion($request->getParam('id'));
        $salida = $this->montaTodo($request);
        $opciones = parse_ini_string($salida, false, INI_SCANNER_RAW);
        InicializarDatos($opciones, true);
        $_SESSION['posicion'] = 0;
        $retorno = NUEVA_PAGINA;
        $retorno = $request->getParam('salida');
        header('Location: ' . $retorno);

    }
    /**
     * Generacion del fichero opciones.ini simulado
     */
    public function montaTodo($request)
    {
        
        $op = new Opciones($this->objGrupo, $this->objCliente, $this->objLocal);
        $op->setTienda($request->getParamNumer('id'));
        $op->setUrl($_SERVER['SERVER_NAME']);
        $op->setRuta(dirname(dirname(__FILE__)) . '/datos');
        return $op->obtenINI();
        
    }

   
/*
***        Sistema de actualizaciones    ***************************************************
*/
    public function rutaVersion($request){
        
        $modelo = $request->getParamSpace('modelo');
        return  PATH_RAIZ . 'version' . CD . 'version' . $modelo . CD;
    }
    /**
     * Actualizacion version******************************************************
     * @param  $request
     */
    public function preguntaActualizacion($request)
    {
        logKioskoWeb('Salida-preguntaActualizacion',$request);
        $actualizar= $this->hayNuevaVersion($request);
        ob_clean();
        header('Content-type: text/plain ;charset=utf-8');
        echo $actualizar;
        ControllerLocal:: actConexion($request);
        exit();
    }
    
    private function hayNuevaVersion($request){
        $actualizar = 0;
        $versionRemota = $request->getParamDouble('version');
        $path=$this->rutaVersion($request);
        if (file_exists($path . 'version-actual.php')) {
            $current = file_get_contents($path . 'version-actual.php');
            if ($current > $versionRemota) {
                $actualizar = 1;
            }
        }
        return $actualizar;
    }
    /**
     * Cuando se indica version, se invoca a este metodo desde el terminal, para iniciar la actualizacion
     * @param  $request
     */
    public function pedirListaProgramas($request){
        logKioskoWeb('Salida-pedirListaProgramas',$request);
        $protegidos=['opciones.ini','*MODELO.ini','datos','.gitignore','.','..'];
        $ruta=$this->rutaVersion($request);    
        $todos=scandir($ruta,false);     
        $ficheros=array_diff($todos,$protegidos);
        $json = json_encode(compact('ficheros',$ficheros));
       
        ob_clean();
        header('Content-type: application/json;charset=utf-8');
        
        echo $json;
        ControllerLocal:: actConexion($request);
        exit();
    }
    /**
     * Solicita programa a programa desde el puesto
     * 
     * @param  $request
     */
    public function pedirPrograma($request){
       // logKioskoWeb('Salida-pedirPrograma',$request);
        $ruta=$this->rutaVersion($request);    
        $filename=$request->getParamSpace('name');   
        $filename=$ruta.$filename;

        ob_clean();
 //       header("Content-Type: application/octet-stream; ");
        header("Content-Transfer-Encoding: binary");
        header("Content-Length: ". filesize($filename).";");
        header("Content-disposition: attachment; filename=" . $filename);
        
        readfile($filename);
        exit();
    }
    function preparaObj($request)
    {
        $elArray = $this->montaArray($request->getParam('id'));
        if (null != $elArray) {
            return RutinasXml::arrayToXML($elArray, $elArray["cliente"]);
        } else {
            return null;
        }
    }
    
    /**
     *
     * @param int $id
     * @return Array con los datos a enviar al cliente como identificador
     */
    public function montaArray(int $id)
    {
        try {
            $this->obtenInformacion($id);           //Prepara Local, Cliente, Grupo
            $objProvincia = $this->cDaoProvincia->listPorId($this->objCliente->getCliente_provincia());
            
            $ident = substr("0000{$this->objGrupo->getId()}", - 4);
            $ident .= substr("000000{$this->objCliente->getId()}", - 6);
            $ident .= substr("000{$this->objGrupo->getId()}", - 3);
            $arLocal = $this->objLocal->getArray();
            $arLocal["cliente"] = $ident;
            $arLocal["provincia"] = $objProvincia->getCppro_nombre();
        } catch (DaoException $e) {
            return null;
        }
        return $arLocal;
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
     * Monta una lista de todas la imagenes para un local determinado, y lo devuelve en Array
     */
    public function obtenLista()
    {
        $this->lista = $this->cDaoImagen->listTodas($this->objGrupo->getGrupo_id(), 
            $this->objCliente->getCliente_id(), 
            $this->objLocal->getLocal_id(),
            $this->objLocal->getLocal_cp(),
            $this->objLocal->getLocal_poblacion()
            );
        $urlBase = $this->request->getUrl();
        $cuenta = count($this->lista);
        
        $this->xmlImagenes = array();
        for ($a = 0; $a < $cuenta; $a ++) {
            
            switch ($this->lista[$a]->getImagen_pertenencia()) {
                case "G":
                    $urlParcial = $this->objGrupo->getGrupo_url();
                    break;
                case "C":
                    $urlParcial = $this->objCliente->getCliente_url();
                    break;
                case "L":
                    $urlParcial = $this->objLocal->getLocal_url();                   
                    break;
                 case "P":
                     $objPromo = (new DaoPromocion())->listPorId( $this->lista[$a]->getImagen_valor());
                     $urlParcial = $objPromo->getPromo_url();
                     break;
            }
            $urlParcial = str_replace("\\", "/", $urlParcial);
            $nombre = $this->lista[$a]->getImagen_nombre();
            $this->lista[$a]->setImagen_nombre($urlBase . $urlParcial . "/" . $nombre);
            $this->xmlImagenes[$a] = $this->lista[$a]->getControl();
            $this->xmlImagenes[$a]['ident'] = $nombre;
            
        }
        return  $this->xmlImagenes;
    }
    /**
     * Llamado por controlarCambios en cliente
     * //$estado 0 0 0 0 0 0 0 0
//+++++++        ^ ^ ^ ^ ^ ^ ^ ^
//+++++++        | | | | | | | + ----- Actualizar version
//*******        | | | | | | +-------- Actualizar fotos
//*******        | | | | | +---------- Mensaje emergencia
//*******        | | | | +------------ 
//*******        | | | +--------------
//*******        + -------------------
 * 
     * @param  $request
     */
    public function actualizarAlgo($request){
       
        $this->objLocal = $this->cDaoLocal->listPorId($request->getParamNumer('id'));
        $actualizaFotos=$this->objLocal->getLocal_actualizar();
        $actualizacion=  $this->hayNuevaVersion($request);          //Comprobamos actualizacion de version
        $respuesta = $actualizacion + ($actualizaFotos*10);// + 1000;  //activo envios de log
        
        header('Content-type: text/plain ;charset=utf-8');
        ob_clean();
        echo bindec($respuesta);
        ControllerLocal::actConexion($request);
        exit;
    }
    
    public function enviaLog($request){
       RutinasServidor::enviarMail('migarcia@dopc.com','Log de la tienda '.$request->getParamNumer('id'),$request->getParamDefault('msg',"No se indico mensaje"));
        exit;
    }
    
    public function enviaFicLog($request){
        $uploadfile = '/home/kpilarbox/tmp' . basename($_FILES['file_contents']['name']);
        if (move_uploaded_file($_FILES['file_contents']['tmp_name'], $uploadfile)) {
            echo "Fichero correcto y subido bien\n";
        } else {
            echo "Possible file upload attack!\n";
            echo "Fichero:" . $_FILES['file_contents']['tmp_name']."\n";
            echo "Fichero def:" . $uploadfile ."\n";
            var_dump($_FILES);
        }
        RutinasServidor::enviarMailcAtt('migarcia@dopc.com',
            "",
            "",
            'Log de la tienda '.$request->getParamNumer('id'),
            $uploadfile,
            $request->getParamDefault('msg',"No se indico mensaje")
        );
        exit;
    }

}
?>