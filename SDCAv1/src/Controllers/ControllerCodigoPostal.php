<?php
namespace controller;

require_once __DIR__ . '/../../vendor/autoload.php';

use App\dao\DaoCodigopostal;
use App\modelos\Cp_codigopostal;

class ControllerCodigoPostal extends  ControllerBase
{
    function __construct()
    {
        parent::__construct(new DaoCodigopostal(),new Cp_codigopostal());   
    }  
    
    
    
}

