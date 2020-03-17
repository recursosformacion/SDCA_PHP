<?php
namespace controller;

use dao\DaoUsuario;
use Exception;
use modelos;
use service;

require_once PATH_RAIZ.'service/Request.php';
require_once PATH_RAIZ.'modelos/Usuario.php';
require_once PATH_RAIZ.'dao/DaoUsuario.php';

class ControllerUsuario
{
    private $daoUsuario;
    private $request;

    public function __construct()
    {
        $this->daoUsuario=new DaoUsuario();
        $this->request= new service\Request();
    }
    public function index()
    {
        echo "<h1>index</h1>";
    }
    /**
     * Listado
     * return String
     * carga $_REQUEST['salida'] con un array de objetos Usuario  *
     */
    public function listado()
    {
        $_REQUEST['salida']=  $this->daoUsuario->listAll();
        return "listado";
    }

    /**
     *
     * Deja en $_REQUEST['salida'] el objeto Usuario solicitado
     * @param  Lista de parametros recibidos por GET y POST
     * @return string con nombre de vista
     */
    public function editar($request)
    {
        $_REQUEST['salida']=  $this->daoUsuario->listPorId($request->getParam('id'));
        $_REQUEST['url'] = "?controller=Usuario&action=actualizar";
        $_REQUEST['opcion'] = "Modificar";
        return "formulario";
    }

    public function add($request)
    {
 
        $_REQUEST['salida']=new modelos\Usuario();
        $_REQUEST['url'] = "?controller=Usuario&action=insertar";
        $_REQUEST['opcion'] = "Añadir";
        return "formulario";
    }

    public function borra($request)
    {
        $_REQUEST['salida']=$this->daoUsuario->listPorId($request->getParam('id'));
        $_REQUEST['url'] = "?controller=Usuario&action=borrar";
        $_REQUEST['opcion'] = "Borrar";
        $_REQUEST['delete'] = "Y";
        return "formulario";
    }

    public function montaObjeto($request)
    {
        $usuario = new modelos\Usuario();
        $usuario->setUsuario_id($request->getParam('usuario_id'));
        $usuario->setUsuario_nombre($request->getParam('usuario_nombre'));
        $usuario->setUsuario_apellidos($request->getParam('usuario_apellidos'));
        $usuario->setUsuario_correo($request->getParam('usuario_correo'));
        $usuario->setUsuario_password($request->getParam('usuario_password'));
        $usuario->setUsuario_lastUpdate(date("Y/m/d H:i:s"));
        return $usuario;
    }
    public function actualizar($request)
    {
        try {
            $this->daoUsuario->update($this->montaObjeto($request));
            return $this->listado();
        } catch (Exception $e) {
            $request->setParam('msg', $e->getMessage());
            $request->setParam('id', $request->getParam('usuario_id'));
            return $this->editar($request);
        }
    }
    public function insertar($request)
    {
        try {
            $this->daoUsuario->inserta($this->montaObjeto($request));
            return $this->listado();
        } catch (Exception $e) {
            $request->setParam('msg', $e->getMessage());
            $request->setParam('id', $request->getParam('usuario_id'));
            return $this->add($request);
        }
    }
    public function borrar($request)
    {
        $this->daoUsuario->borra($this->montaObjeto($request));
        $this->daoUsuario->resetAutoIncrement();
        return $this->listado();
    }
}
