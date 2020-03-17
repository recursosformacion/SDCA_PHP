<?php
namespace controller;

require_once PATH_RAIZ . 'modelos/Cp_codigopostal.php';
require_once PATH_RAIZ . 'dao/DaoCodigoPostal.php';
require_once PATH_RAIZ . 'controller/ControllerBase.php';

use dao\DaoCodigoPostal;
use modelos\Cp_codigopostal;

class ControllerCodigoPostal extends  ControllerBase
{
    function __construct()
    {
        parent::__construct(new DaoCodigoPostal(),new Cp_codigopostal());   
    }  
    
    
    
}

