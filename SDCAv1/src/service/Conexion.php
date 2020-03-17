<?php
namespace service;

use PDO;
use Exception;

class Conexion
{

    private static $instance;

    private function __construct()
    {}

    private function __clone()
    {}

    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = self::connect();
        }
        return self::$instance;
    }

    static function connect()
    {
        $config = require_once(PATH_RAIZ.'config/config.php');
        try {
            $dsn = "mysql:host={$config['db']['host']};dbname={$config['db']['basedatos']};charset={$config['db']['charset']}";
            $pdo = new PDO($dsn, $config['db']['usuario'], $config['db']['password']);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // <--Activa exception
            $pdo->setAttribute(PDO::ATTR_CASE, PDO::CASE_LOWER); // <--. Fuerza a los nombres de las columnas a may�sculas o min�sculas, CASE_UPPER).
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN'){
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING); 
//                 $stmt = $pdo->prepare("SET GLOBAL general_log = 'ON'");  //actvar log en mi maquina
//                 $stmt->execute();
            } else {
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // <--Activa exception
            }
            
        } catch (Exception $e) {
            die("No se pudo conectar: " . $e->getMessage());
        }
        return $pdo;
    }

    static function disConnect()
    {
        self::$instance = null;
    }


}









