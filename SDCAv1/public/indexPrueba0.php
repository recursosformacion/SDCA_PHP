<?php
namespace App;
session_start();


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

define('__ROOT__', dirname(dirname(__FILE__)));

require( __ROOT__ . '/src/errores/php_error.php' );
\php_error\reportErrors();

use App\dao\DaoComunidades;
use App\modelos\Cp_comunidades;

require_once __ROOT__ . '/vendor/autoload.php';

    echo "<br>Listar Json<br>";
    $cDao = new DaoComunidades();
    $obj=json_decode($cDao->listJson());   
    listarArray($obj);
    
    echo "<br>Listar la comunidad 1<br>";
    $modelo=new Cp_comunidades();
    $modelo->setCpcoa_id(1);
    listarComunidad( $cDao->listPorId((int) $modelo->getCpcoa_id()));
    
    
    
    echo "<br>Realiza Insert<br>";
    $modelo=new Cp_comunidades();
    $modelo->setCpcoa_nombre("Pruebas");
    $modelo->setCpcoa_pais(100);
    $mod1=$cDao->inserta($modelo);
    listarComunidad( $cDao->listPorId((int) $modelo->getCpcoa_id()));
   
    
    $modelo->setCpcoa_nombre("Mas pruebas");
    $mod1=$cDao->update($modelo);
    listarComunidad($cDao->listPorId($modelo->getCpcoa_id()));
    
    $cDao->borra($modelo);
    
    $cDao->resetAutoincrement();
    
    $idx=$cDao->maxIndex();
    if ($idx<$modelo->getCpcoa_id()) 
        echo "<br>Borrado " . $modelo->getCpcoa_id();
    else {
        echo "<bR>Problema " . $modelo->getCpcoa_id();
    }
    
    
    function listarComunidad($object){
        echo "<hr/>";
        if (is_null($object)){
            echo "<br>Ha llegado nulo";
            return;
        }
        echo "<br>cpcoa_id:\t" . $object->getCpcoa_id();
        echo "<br>cpcoa_nombre:\t" . $object->getCpcoa_nombre();
        echo "<br>cpcoa_pais:\t" . $object->getCpcoa_pais();
    }
    
    
    function listarArray($object){
        echo "<hr/>";
        foreach ($object as $key => $val) {
            if (is_array($val) || is_object($val)) {
                listarArray($val);
            } else {
                echo $key . "\t=" . $val .'<br>';
            }
        }
    }
