<?php
namespace App\service;

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
        try {
            $config = (require_once (__ROOT__ . '/config/settings.php'))['db'];
            
            $host = $config['host'];
            
            $dbname = $config['database'];
            $username = $config['username'];
            $password = $config['password'];
            $charset = $config['charset'];
            $flags = $config['flags'];
            $dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";          
            $pdo =  new PDO($dsn, $username, $password, $flags);
            
        } catch (Exception $e) {
            die("No se pudo conectar: " . $e->getMessage());
        }
        return $pdo;
    }

    static function disConnect()
    {
        self::$instance = null;
    }

    static function obtenPdo()
    {
       
    }
}









