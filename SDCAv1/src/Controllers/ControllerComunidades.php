<?php
namespace controller;

require_once __DIR__ . '/../../vendor/autoload.php';

use App\dao\DaoComunidades;
use App\modelos\Cp_comunidades;

class ControllerComunidades extends  ControllerBase
{
    function __construct()
    {
        parent::__construct(new DaoComunidades(),new Cp_comunidades());   
    }  
}

