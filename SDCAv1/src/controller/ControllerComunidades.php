<?php
namespace controller;

require_once PATH_RAIZ . 'controller/ControllerBase.php';
require_once PATH_RAIZ . 'modelos/Cp_comunidades.php';
require_once PATH_RAIZ . 'dao/DaoComunidades.php';

use dao\DaoComunidades;
use modelos\Cp_comunidades;

class ControllerComunidades extends  ControllerBase
{
    function __construct()
    {
        parent::__construct(new DaoComunidades(),new Cp_comunidades());   
    }  
}

