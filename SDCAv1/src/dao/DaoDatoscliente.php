<?php
declare (strict_types = 1);
namespace App\dao;


/*******************************************************************************
* Class Name:       DaoDatoscliente
* File Name:        DaoDatoscliente.php
* Generated:        Thursday, Apr 2, 2020 - 19:30:36 CEST
*  - for Table:     datoscliente
*   - in Database:  contabilidadautonomos
* Created by: Daoclass 
********************************************************************************/

// Files required by class:
require_once ("DaoBase.php");

use PDOStatement;
use App\dao\DaoBaseACT;


// Begin Class "DaoDatoscliente"
class DaoDatoscliente extends DaoBaseACT{
	
	// ************ Declaracion de variables
	const SELECT_ALL 	 = "SELECT * FROM datoscliente  ORDER BY dc_nombre";
	const SELECT_WHERE	 = "SELECT * FROM FROM datoscliente WHERE :where  ORDER BY dc_nombre";
	const SELECT_UNO 	 = "SELECT * FROM  datoscliente  WHERE id_cliente = :id_cliente ";
	const INSERTAR 	 = "INSERT into datoscliente values (:id_cliente,:dc_nombre,:dc_direccion,:dc_cpostal,:dc_poblacion,:dc_tipocliente,:dc_correoelectronico,:dc_idfiscal)";
	const ACTUALIZA 	 = "UPDATE datoscliente  set dc_nombre= :dc_nombre,dc_direccion= :dc_direccion,dc_cpostal= :dc_cpostal,dc_poblacion= :dc_poblacion,dc_tipocliente= :dc_tipocliente,dc_correoelectronico= :dc_correoelectronico,dc_idfiscal= :dc_idfiscal
                                        WHERE id_cliente = :id_cliente  ";
	const DELETE 	 = "DELETE FROM datoscliente WHERE id_cliente = :id_cliente ";
	
	
	// Class Constructor
	public function __construct () {
		parent::__construct ("App\modelos\Datoscliente");
	}
	
	// Class Destructor
	public function __destruct () {
	
	}
	
	// Montar para SQL
	public function montaBind (string $orden, $modelo): PDOStatement {
		$stmt = $this->pdo->prepare ($orden);
		$stmt->bindValue (':id_cliente', $modelo->getId_cliente ());
		$stmt->bindValue (':dc_nombre', $modelo->getDc_nombre ());
		$stmt->bindValue (':dc_direccion', $modelo->getDc_direccion ());
		$stmt->bindValue (':dc_cpostal', $modelo->getDc_cpostal ());
		$stmt->bindValue (':dc_poblacion', $modelo->getDc_poblacion ());
		$stmt->bindValue (':dc_tipocliente', $modelo->getDc_tipocliente ());
		$stmt->bindValue (':dc_correoelectronico', $modelo->getDc_correoelectronico ());
		$stmt->bindValue (':dc_idfiscal', $modelo->getDc_idfiscal ());
		
		
		return $stmt;
	}
	
	// Montar para DELETE SQL
	public function montaBindDel (string $orden, $modelo): PDOStatement {
		$stmt = $this->pdo->prepare ($orden);
		$stmt->bindValue (':id_cliente', $modelo->getId_cliente ());
		
		return $stmt;
	}
	
	// Montar para SQL
	public function montaDebug (string $orden, $modelo): string {
		$stmt = $orden;
		$stmt = str_replace (':id_cliente', $modelo->getId_cliente (),$stmt);
		$stmt = str_replace (':dc_nombre', $modelo->getDc_nombre (),$stmt);
		$stmt = str_replace (':dc_direccion', $modelo->getDc_direccion (),$stmt);
		$stmt = str_replace (':dc_cpostal', $modelo->getDc_cpostal (),$stmt);
		$stmt = str_replace (':dc_poblacion', $modelo->getDc_poblacion (),$stmt);
		$stmt = str_replace (':dc_tipocliente', $modelo->getDc_tipocliente (),$stmt);
		$stmt = str_replace (':dc_correoelectronico', $modelo->getDc_correoelectronico (),$stmt);
		$stmt = str_replace (':dc_idfiscal', $modelo->getDc_idfiscal (),$stmt);
		
		return $stmt;
	}

}
// End Class "DaoDatoscliente"
?>