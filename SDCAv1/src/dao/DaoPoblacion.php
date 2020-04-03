<?php
declare (strict_types = 1);
namespace App\dao;


/*******************************************************************************
* Class Name:       DaoPoblacion
* File Name:        DaoPoblacion.php
* Generated:        Thursday, Apr 2, 2020 - 19:30:36 CEST
*  - for Table:     cp_poblacion
*   - in Database:  contabilidadautonomos
* Created by: Daoclass 
********************************************************************************/

// Files required by class:
require_once ("DaoBase.php");

use PDOStatement;
use App\dao\DaoBaseACT;


// Begin Class "DaoPoblacion"
class DaoPoblacion extends DaoBaseACT{
	
	// ************ Declaracion de variables
	const SELECT_ALL 	 = "SELECT * FROM cp_poblacion  ORDER BY cppob_id";
	const SELECT_WHERE	 = "SELECT * FROM FROM cp_poblacion WHERE :where  ORDER BY cppob_id";
	const SELECT_UNO 	 = "SELECT * FROM  cp_poblacion  WHERE id = :id ";
	const INSERTAR 	 = "INSERT into cp_poblacion values (:id,:cppob_id,:cppro_id,:cppob_nombre,:cppob_ineid,:cppob_lat,:cppob_lon)";
	const ACTUALIZA 	 = "UPDATE cp_poblacion  set cppob_id= :cppob_id,cppro_id= :cppro_id,cppob_nombre= :cppob_nombre,cppob_ineid= :cppob_ineid,cppob_lat= :cppob_lat,cppob_lon= :cppob_lon
                                        WHERE id = :id  ";
	const DELETE 	 = "DELETE FROM cp_poblacion WHERE id = :id ";
	
	
	// Class Constructor
	public function __construct () {
		parent::__construct ("App\modelos\Cp_poblacion");
	}
	
	// Class Destructor
	public function __destruct () {
	
	}
	
	// Montar para SQL
	public function montaBind (string $orden, $modelo): PDOStatement {
		$stmt = $this->pdo->prepare ($orden);
		$stmt->bindValue (':id', $modelo->getId ());
		$stmt->bindValue (':cppob_id', $modelo->getCppob_id ());
		$stmt->bindValue (':cppro_id', $modelo->getCppro_id ());
		$stmt->bindValue (':cppob_nombre', $modelo->getCppob_nombre ());
		$stmt->bindValue (':cppob_ineid', $modelo->getCppob_ineid ());
		$stmt->bindValue (':cppob_lat', $modelo->getCppob_lat ());
		$stmt->bindValue (':cppob_lon', $modelo->getCppob_lon ());
		
		
		return $stmt;
	}
	
	// Montar para DELETE SQL
	public function montaBindDel (string $orden, $modelo): PDOStatement {
		$stmt = $this->pdo->prepare ($orden);
		$stmt->bindValue (':id', $modelo->getId ());
		
		return $stmt;
	}
	
	// Montar para SQL
	public function montaDebug (string $orden, $modelo): string {
		$stmt = $orden;
		$stmt = str_replace (':id', $modelo->getId (),$stmt);
		$stmt = str_replace (':cppob_id', $modelo->getCppob_id (),$stmt);
		$stmt = str_replace (':cppro_id', $modelo->getCppro_id (),$stmt);
		$stmt = str_replace (':cppob_nombre', $modelo->getCppob_nombre (),$stmt);
		$stmt = str_replace (':cppob_ineid', $modelo->getCppob_ineid (),$stmt);
		$stmt = str_replace (':cppob_lat', $modelo->getCppob_lat (),$stmt);
		$stmt = str_replace (':cppob_lon', $modelo->getCppob_lon (),$stmt);
		
		return $stmt;
	}

}
// End Class "DaoPoblacion"
?>