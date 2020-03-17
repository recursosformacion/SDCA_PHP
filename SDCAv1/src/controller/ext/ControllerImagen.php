<?php
namespace controller;

require_once PATH_RAIZ . 'controller/ConstantesControladores.php';
require_once PATH_RAIZ . 'service/Request.php';
require_once PATH_RAIZ . 'modelos/Imagen.php';
require_once PATH_RAIZ . 'dao/DaoImagen.php';
require_once PATH_RAIZ . 'dao/DaoGrupo.php';
require_once PATH_RAIZ . 'dao/DaoCliente.php';
require_once PATH_RAIZ . 'dao/DaoLocal.php';
require_once PATH_RAIZ . 'dao/DaoPromocion.php';
require_once PATH_RAIZ . 'service/ProcesaImagenes.php';
require_once PATH_RAIZ . 'service/RutinasServidor.php';


use dao\DaoCliente;
use dao\DaoGrupo;
use dao\DaoImagen;
use dao\DaoLocal;
use dao\DaoPromocion;
use modelos\Imagen;
use service\ProcesaImagenes;
use service\Request;
use service\RutinasServidor;


class ControllerImagen
{

    private $cDao;

    private $request;

    function __construct()
    {
        $this->cDao = new DaoImagen();
        $this->request = new Request();
    }

    public function index()
    {
        echo "<h1>index</h1>";
    }

    /**
     * Listado
     * return String
     * carga $_REQUEST['salida'] con un array de objetos Imagen *
     */
    public function listado()
    {
        global $request;
        if (null !== $request->getParam('imagen_pertenencia')) {
            return $this->imagenes($request);
        }
        $_REQUEST['salida'] = $this->cDao->listAll();
        return "listado";
    }

    public function imagenes($request)
    {
        self::obtenElemento($request->getParam('imagen_pertenencia'), (int) $request->getParamNumer('imagen_valor'));
        $obj = $this->cDao->listxTipo($request->getParam('imagen_pertenencia'), (int) $request->getParamNumer('imagen_valor'));
        $_REQUEST['imagenes'] = $obj;
        $_REQUEST['url'] = $this->montaURL('addImagen');
      
        return 'galeria';
    }

    /**
     *
     * Deja en $_REQUEST['salida'] el objeto Imagen solicitado
     *
     * @param
     *            Lista de parametros recibidos por GET y POST
     * @return string con nombre de vista
     */
    public function editar($request)
    {
        $objImagen = $this->cDao->listPorId($request->getParamNumer('id'));
        self::obtenElemento($objImagen->getImagen_pertenencia(), (int) $objImagen->getImagen_valor());
        $_REQUEST['salida'] = $objImagen;
        $_REQUEST['url'] = $this->montaURL('actualizar');
        $_REQUEST['opcion'] = "Modificar";
        $_REQUEST['alta'] =0;
        $_REQUEST['cancelar'] = URL_CANCELACION_IMAGEN
        .'&imagen_pertenencia='.$objImagen->getImagen_pertenencia()
        .'&imagen_valor='.$objImagen->getImagen_valor();
        return "formulario";
    }

    public function add($request)
    {
        $objImagen = new Imagen();
        $objImagen->setImagen_tiempo(8);
        $objImagen->setImagen_pertenencia($request->getParamSpace('imagen_pertenencia'));
        $objImagen->setImagen_valor($request->getParamNumer('imagen_valor'));
        $objImagen->setImagen_dias("*");
        $objImagen->setImagen_caduca(time() + (365 * 24 * 60 * 60));
        self::obtenElemento($objImagen->getImagen_pertenencia(), (int) $objImagen->getImagen_valor());
        $_REQUEST['salida'] = $objImagen;
        $_REQUEST['url'] = $this->montaURL('insertar');
        $_REQUEST['opcion'] = "Añadir";
        $_REQUEST['alta'] =1;
        $_REQUEST['cancelar'] = URL_CANCELACION_IMAGEN
            .'&imagen_pertenencia='.$objImagen->getImagen_pertenencia()
            .'&imagen_valor='.$objImagen->getImagen_valor();
        return "formulario";
    }

    public function borra($request)
    {
        $objImagen = $this->cDao->listPorId($request->getParamNumer('id'));
        self::obtenElemento($objImagen->getImagen_pertenencia(), (int) $objImagen->getImagen_valor());
        $_REQUEST['salida'] = $objImagen;
        $_REQUEST['url'] = "?controller=Imagen&action=borrar";
        $_REQUEST['opcion'] = "Borrar";
        $_REQUEST['delete'] = "Y";
        $_REQUEST['alta'] =0;
        $_REQUEST['cancelar'] = URL_CANCELACION_IMAGEN
        .'&imagen_pertenencia='.$objImagen->getImagen_pertenencia()
        .'&imagen_valor='.$objImagen->getImagen_valor();
        return "formulario";
    }

    public function montaObjeto($request)
    {
        $imagen = new Imagen();
        $imagen->setImagen_id($request->getParam('imagen_id'));
        if (isset($_FILES['photo']['name'])) {
            $imagen->setImagen_nombre($_FILES['photo']['name']);
            $imagen->setImagen_nombre_origen($_FILES['photo']['name']);
        }
        $imagen->setImagen_orden($request->getParamNumer('imagen_orden'));
        $imagen->setImagen_tiempo($request->getParamNumer('imagen_tiempo'));
        $imagen->setImagen_pertenencia($request->getParamSpace('imagen_pertenencia'));
        $imagen->setImagen_valor($request->getParamSpace('imagen_valor'));
        $imagen->setImagen_horaInicio_String(RutinasServidor::formatTiempo($request->getParamSpace('imagen_horainicio')), 0);
        $imagen->setImagen_horaFin_String(RutinasServidor::formatTiempo($request->getParamSpace('imagen_horafin')), 0);
        $imagen->setImagen_dias($request->getParamSpace('imagen_dias'));
        ;
        $imagen->setImagen_caduca_String($request->getParamSpace('imagen_caduca'));
        $imagen->setImagen_lastupdate(date("Y/m/d H:i:s"));
        return $imagen;
    }

    public function actualizar($request)
    {
        $this->cDao->update($this->montaObjeto($request));
        return $this->listado();
    }

    public function insertar($request)
    {
        
        $objImagen = $this->montaObjeto($request);
        self::obtenElemento($objImagen->getImagen_pertenencia(), $objImagen->getImagen_valor());
        $nImagen= ProcesaImagenes::cargaImagen($request, $request->getParam('urlDato'));
        if ($nImagen != "") {
            $objImagen->setImagen_nombre($nImagen);
            $this->cDao->inserta($objImagen);
            return $this->imagenes($request);
        } else {
            $_REQUEST['salida'] = $objImagen;
            $_REQUEST['url'] = $this->montaURL('insertar');
            $_REQUEST['opcion'] = "Añadir";
            $_REQUEST['msg']=ProcesaImagenes::$errores;
            $_REQUEST['alta'] =1;
            return "formulario";
        }
    }

    public function borrar($request)
    {
        $objImagen = $this->cDao->listPorId((int) $request->getParam('imagen_id'));
        self::deleteImagen($objImagen);
        return $this->listado();
    }

    public static function borrarConjunto($tipo, $valor)
    {
        $cDao = new DaoImagen();
        $listImagen = $cDao->listxTipo($tipo, $valor);

        foreach ($listImagen as $objImagen) {
            self::deleteImagen($objImagen);
        }
    }

    public static function deleteImagen($objImagen)
    {
        global $request;
        $cDao = new DaoImagen();
        self::obtenElemento($objImagen->getImagen_pertenencia(), (int) $objImagen->getImagen_valor());
        ProcesaImagenes::borraImagen($request->getParam('urlDato'), $objImagen->getImagen_nombre());

        $cDao->borra($objImagen);
        $cDao->resetAutoIncrement();
    }

    public static function obtenElemento($tipo, $valor)
    {
        global $request;
        $request->setParam('imagen_pertenencia', $tipo);
        $request->setParam('imagen_valor', $valor);
        switch ($tipo) {
            case 'G':
                $cDao = new DaoGrupo();
                $elem = $cDao->listPorId($valor);
                $request->setParam('titulo', "Grupo");
                $request->setParam("tituloDato", $elem->getGrupo_nombre());
                $request->setParam("urlDato", $elem->getGrupo_url());
                $_REQUEST['cancelar'] = URL_CANCELACION_GRUPO;
                break;
            case 'C':
                $cDao = new DaoCliente();
                $elem = $cDao->listPorId($valor);
                $request->setParam('titulo', "Cliente");
                $request->setParam("tituloDato", $elem->getCliente_nombre());
                $request->setParam("urlDato", $elem->getCliente_url());
                $_REQUEST['cancelar'] = URL_CANCELACION_CLIENTE;
                break;
            case 'L':
                $cDao = new DaoLocal();
                $elem = $cDao->listPorId($valor);
                $request->setParam('titulo', "Local");
                $request->setParam("tituloDato", $elem->getLocal_nombre());
                $request->setParam("urlDato", $elem->getLocal_url());
                $_REQUEST['cancelar'] = URL_CANCELACION_LOCAL;
                break;
            case 'P':
                $cDao = new DaoPromocion();
                $elem = $cDao->listPorId($valor);
                $request->setParam('titulo', "Promo");
                $request->setParam("tituloDato", $elem->getPromo_nombre());
                $request->setParam("urlDato", $elem->getPromo_url());
                $_REQUEST['cancelar'] = URL_CANCELACION_PROMOCION;
                break;
        }
        return $elem;
    }

    public static function montaURL($opcion)
    {
        global $request;
        return '?controller=Imagen' . '&action=' . $opcion . '&imagen_pertenencia=' . $request->getParamSpace('imagen_pertenencia') . '&imagen_valor=' . $request->getParamNumer('imagen_valor');
    }
}

