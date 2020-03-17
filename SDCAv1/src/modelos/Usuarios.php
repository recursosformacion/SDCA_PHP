<?php
declare (strict_types = 1);
namespace modelos;


/*******************************************************************************
* Class Name:       Usuarios
* File Name:        Usuarios.php
* Generated:        Friday, Nov 15, 2019 - 9:43:36 UTC
*  - for Table:     usuarios
*   - in Database:  contabilidadautonomo
* Created by: table2class 
********************************************************************************/

// Files required by class:
require_once ("ModeloBase.php");

// Begin Class "Usuarios"
class Usuarios extends ModeloBase{
	
	// ************ Declaracion de variables
	private $usuario_id;
	private $usuario_nombre;
	private $usuario_apellidos;
	private $usuario_correo;
	private $usuario_password;
	private $usuario_lastupdate;
	
	const NOMBRE_TABLA_USUARIOS = "usuarios";
	const NOMBRE_PK_USUARIOS = "usuario_id";
	
	
	// Class Constructor
	public function __construct (int $usuario_id = null,string $usuario_nombre = null,string $usuario_apellidos = null,string $usuario_correo = null,string $usuario_password = null,string $usuario_lastupdate = null) {
		parent::__construct ("Usuarios");
		if (func_num_args () > 0) {
			$this->setUsuario_id ($usuario_id);
			$this->setUsuario_nombre ($usuario_nombre);
			$this->setUsuario_apellidos ($usuario_apellidos);
			$this->setUsuario_correo ($usuario_correo);
			$this->setUsuario_password ($usuario_password);
			$this->setUsuario_lastupdate ($usuario_lastupdate);
		}
	}
	
	// Class Destructor
	public function __destruct () {
	
	}
	
	// GET Functions
	public function getid ():int {
		return (int) $this->usuario_id;
	}
	
	public function getUsuario_id ():int {
		return (int) $this->usuario_id;
	}
	
	public function getUsuario_nombre ():string {
		return (string) $this->usuario_nombre;
	}
	
	public function getUsuario_apellidos ():string {
		return (string) $this->usuario_apellidos;
	}
	
	public function getUsuario_correo ():string {
		return (string) $this->usuario_correo;
	}
	
	public function getUsuario_password ():string {
		return (string) $this->usuario_password;
	}
	
	public function getUsuario_lastupdate ():string {
		return (string) $this->usuario_lastupdate;
	}
	
	// SET Functions
	public function setid (int $usuario_id):void {
		$this->usuario_id =$usuario_id;
	}
	
	public function setUsuario_id (int $usuario_id):void {
		$this->usuario_id = $usuario_id;
	}
	
	public function setUsuario_nombre (string $usuario_nombre):void {
		$this->usuario_nombre = $usuario_nombre;
	}
	
	public function setUsuario_apellidos (string $usuario_apellidos):void {
		$this->usuario_apellidos = $usuario_apellidos;
	}
	
	public function setUsuario_correo (string $usuario_correo):void {
		$this->usuario_correo = $usuario_correo;
	}
	
	public function setUsuario_password (string $usuario_password):void {
		$this->usuario_password = $usuario_password;
	}
	
	public function setUsuario_lastupdate (string $usuario_lastupdate):void {
		$this->usuario_lastupdate = $usuario_lastupdate;
	}
	
	// Salida en array
	public function getInArray (): Array {
		$array =[
		"usuario_id"=> $this->usuario_id,
		"usuario_nombre"=> $this->usuario_nombre,
		"usuario_apellidos"=> $this->usuario_apellidos,
		"usuario_correo"=> $this->usuario_correo,
		"usuario_password"=> $this->usuario_password,
		"usuario_lastupdate"=> $this->usuario_lastupdate
		];
		return $array;
	}
	
	// Construye desde array
	public static function setFromArray (array $datos) : Usuarios {
		$resp = new self ();
		$resp->setUsuario_id ($datos['usuario_id']);
		$resp->setUsuario_nombre ($datos['usuario_nombre']);
		$resp->setUsuario_apellidos ($datos['usuario_apellidos']);
		$resp->setUsuario_correo ($datos['usuario_correo']);
		$resp->setUsuario_password ($datos['usuario_password']);
		$resp->setUsuario_lastupdate ($datos['usuario_lastupdate']);
		return $resp;
	}
	
	public static function getNombreId ():string {
		return self::NOMBRE_PK_USUARIOS;
	}
	
	public static function getNombreTabla ():string { 
		return self::NOMBRE_TABLA_USUARIOS;
	}
	
	// para realizar desplegables
	
	public function getSelect (){
		return array (
				0 => "usuario_id",
				1 => "usuario_nombre"
				);
	}
}
// End Class "Usuarios"
?>