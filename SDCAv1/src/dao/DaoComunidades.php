<?php
declare (strict_types = 1);
namespace App\dao;


/*******************************************************************************
* Class Name:       DaoComunidades
* File Name:        DaoComunidades.php
* Generated:        Saturday, Apr 4, 2020 - 13:35:13 CEST
*  - for Table:     cp_comunidades
*   - in Database:  contabilidadautonomos
* Created by: Daoclass 
********************************************************************************/

// Files required by class:
require_once ("DaoBase.php");

use PDOStatement;
use App\dao\DaoBase;


// Begin Class "DaoComunidades"
class DaoComunidades extends DaoBase{
	
	// ************ Declaracion de variables
	const SELECT_ALL 	 = "SELECT * FROM cp_comunidades  ORDER BY cpcoa_nombre";
	const SELECT_WHERE	 = "SELECT * FROM FROM cp_comunidades WHERE :where  ORDER BY cpcoa_nombre";
	const SELECT_UNO 	 = "SELECT * FROM  cp_comunidades  WHERE cpcoa_id = :cpcoa_id ";
	const INSERTAR 	 = "INSERT into cp_comunidades values (:cpcoa_id,:cpcoa_nombre,:cpcoa_pais)";
	const ACTUALIZA 	 = "UPDATE cp_comunidades  set cpcoa_nombre= :cpcoa_nombre,cpcoa_pais= :cpcoa_pais
                                        WHERE cpcoa_id = :cpcoa_id  ";
	const DELETE 	 = "DELETE FROM cp_comunidades WHERE cpcoa_id = :cpcoa_id ";
	
	
	// Class Constructor
	public function __construct () {
		parent::__construct ("App\modelos\Cp_comunidades");
	}
	
	// Class Destructor
	public function __destruct () {
	
	}
	
	// Montar para SQL
	public function montaBind (string $orden, $modelo): PDOStatement {
		$stmt = $this->pdo->prepare ($orden);
		$stmt->bindValue (':cpcoa_id', $modelo->getCpcoa_id ());
		$stmt->bindValue (':cpcoa_nombre', $modelo->getCpcoa_nombre ());
		$stmt->bindValue (':cpcoa_pais', $modelo->getCpcoa_pais ());
		
		
		return $stmt;
	}
	
	// Montar para DELETE SQL
	public function montaBindDel (string $orden, $modelo): PDOStatement {
		$stmt = $this->pdo->prepare ($orden);
		$stmt->bindValue (':cpcoa_id', $modelo->getCpcoa_id ());
		
		return $stmt;
	}
	
	// Montar para SQL
	public function montaDebug (string $orden, $modelo): string {
		$stmt = $orden;
		$stmt = str_replace (':cpcoa_id', $modelo->getCpcoa_id (),$stmt);
		$stmt = str_replace (':cpcoa_nombre', $modelo->getCpcoa_nombre (),$stmt);
		$stmt = str_replace (':cpcoa_pais', $modelo->getCpcoa_pais (),$stmt);
		
		return $stmt;
	}

}
// End Class "DaoComunidades"
?>