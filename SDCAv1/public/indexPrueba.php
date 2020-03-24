<?php
namespace App;


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
set_error_handler("gestionLog");

define('__ROOT__', dirname(dirname(__FILE__)));

require (__ROOT__ . '/src/dao/DaoComunidades.php');
require_once (__ROOT__ . '/service/MiErrorLog.php');



use App\dao\DaoComunidades;


    $cDao = new DaoComunidades();
    var_dump("Open hechp");
    var_dump($cDao.listAll());
    
