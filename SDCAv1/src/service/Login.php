<?php
declare(strict_types = 1);
namespace service;

require_once (realpath(dirname(dirname(__FILE__))) . "/config/init.php");

require_once PATH_RAIZ . 'controller/ConstantesControladores.php';
require_once PATH_RAIZ . 'modelos/Usuarios.php';
require_once PATH_RAIZ . 'dao/DaoUsuarios.php';

use Exception;
use modelos\Usuarios;
use dao\DaoUsuarios;


class Login
{

    private $user;
    private $cDao;
    
    function __construct()
    {
        $this->cDao = new DaoUsuarios();
    }

    public static function conviertePassword(string $passwd): string
    {
        return password_hash($passwd, PASSWORD_DEFAULT);
    }

    public function login($Usuarios, $passwd): bool
    {
        echo "login1" .'/' . $Usuarios . '/' . $passwd ;
        if (null ===$Usuarios || null === $passwd || $Usuarios=="" || $passwd=="") return false;
        $objUs = new Usuarios();
        $reso=false;
        try {
            $objUs = $this->cDao->obtenUsuarios($Usuarios);
            if (password_verify($passwd, $objUs->getUsuarios_password())) {
                $this->user = $objUs;
                $_SESSION['nombreUsuarios']=$objUs->getUsuarios_nombre();
                $reso= true;
            }
            
        } catch (Exception $e) {
            $reso=false;
        }
        if ($reso) return true;
        return $this->loginAntiguo($Usuarios, $passwd);
    }
    
    public function loginAntiguo($Usuarios, $passwd): bool
    {
        echo "/login2";
        $objUs = new Usuarios();
        try {
            $objUs = $this->cDao->autoriza($Usuarios, $passwd);
            if (isset($objUs)){
                $_SESSION['nombreUsuarios']=$objUs->getUsuarios_nombre();
                return true;
            }
            return false;
        } catch (Exception $e) {
            return false;
        }
    }
    
    public function info($pass){
        return password_get_info($pass );
    }

    /**
     * 
     * @return Usuarios
     */
    public function getUser(): Usuarios
    {
        return $this->user;
    }
}

