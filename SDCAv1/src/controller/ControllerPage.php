<?php
namespace controller;




require_once PATH_RAIZ.'service/Request.php';
require_once PATH_RAIZ. 'service/Login.php';
require_once PATH_RAIZ.'modelos/Usuarios.php';
require_once PATH_RAIZ.'dao/DaoUsuarios.php';

use dao\DaoUsuarios;
use service;
use service\DAOException;
use service\Login;

class ControllerPage
{
    private $daoUsuarios;
    private $request;
    private $autorizador;

    function __construct(){

        $this->daoUsuarios=new DaoUsuarios();
        $this->request= new service\Request();
        $this->autorizador = new Login();

    }

    public function index(){
        echo "<h1>Sin tarea asignada</h1>";
    }

    /**
     * Login
     * return String
     * carga $_REQUEST['salida'] con un array de objetos Usuarios  *
     */
    public function login(){
        $_REQUEST['url'] = "?controller=Page&action=conectar";
        return "formulario";
    }
    public function logoff(){
        $_SESSION['nombreUsuarios']="";
        return "index";
    }

    public function conectar(){
        try{
            $Usuarios_correo=$this->request->getParam('Usuarios_correo');
            $Usuarios_password=$this->request->getParam('Usuarios_password');
            if ($this->autorizador->login($Usuarios_correo,$Usuarios_password)){
                return "index";
            }
        } catch (DAOException $e) {
            $_REQUEST['msg'] = "Error de acceso.Problemas con la BBDD";
            return "formulario";
        }
        $_REQUEST['msg'] = "Error de acceso.";
        return "formulario";
    }
}

