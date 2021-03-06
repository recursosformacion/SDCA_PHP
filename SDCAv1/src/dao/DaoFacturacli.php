<?php
declare (strict_types = 1);
namespace App\dao;


/*******************************************************************************
* Class Name:       DaoFacturacli
* File Name:        DaoFacturacli.php
* Generated:        Saturday, Apr 4, 2020 - 13:35:13 CEST
*  - for Table:     tipo_facturacli
*   - in Database:  contabilidadautonomos
* Created by: Daoclass 
********************************************************************************/

// Files required by class:
require_once ("DaoBase.php");

use PDOStatement;
use App\dao\DaoBase;


// Begin Class "DaoFacturacli"
class DaoFacturacli extends DaoBase{
	
	// ************ Declaracion de variables
	const SELECT_ALL 	 = "SELECT * FROM tipo_facturacli  ORDER BY tfc_nombre";
	const SELECT_WHERE	 = "SELECT * FROM FROM tipo_facturacli WHERE :where  ORDER BY tfc_nombre";
	const SELECT_UNO 	 = "SELECT * FROM  tipo_facturacli  WHERE id_tipofacturacli = :id_tipofacturacli ";
	const INSERTAR 	 = "INSERT into tipo_facturacli values (:id_tipofacturacli,:tfc_nombre,:tfc_poriva,:tfc_porretencion)";
	const ACTUALIZA 	 = "UPDATE tipo_facturacli  set tfc_nombre= :tfc_nombre,tfc_poriva= :tfc_poriva,tfc_porretencion= :tfc_porretencion
                                        WHERE id_tipofacturacli = :id_tipofacturacli  ";
	const DELETE 	 = "DELETE FROM tipo_facturacli WHERE id_tipofacturacli = :id_tipofacturacli ";
	
	
	// Class Constructor
	public function __construct () {
		parent::__construct ("App\modelos\Tipo_facturacli");
	}
	
	// Class Destructor
	public function __destruct () {
	
	}
	
	// Montar para SQL
	public function montaBind (string $orden, $modelo): PDOStatement {
		$stmt = $this->pdo->prepare ($orden);
		$stmt->bindValue (':id_tipofacturacli', $modelo->getId_tipofacturacli ());
		$stmt->bindValue (':tfc_nombre', $modelo->getTfc_nombre ());
		$stmt->bindValue (':tfc_poriva', $modelo->getTfc_poriva ());
		$stmt->bindValue (':tfc_porretencion', $modelo->getTfc_porretencion ());
		
		
		return $stmt;
	}
	
	// Montar para DELETE SQL
	public function montaBindDel (string $orden, $modelo): PDOStatement {
		$stmt = $this->pdo->prepare ($orden);
		$stmt->bindValue (':id_tipofacturacli', $modelo->getId_tipofacturacli ());
		
		return $stmt;
	}
	
	// Montar para SQL
	public function montaDebug (string $orden, $modelo): string {
		$stmt = $orden;
		$stmt = str_replace (':id_tipofacturacli', $modelo->getId_tipofacturacli (),$stmt);
		$stmt = str_replace (':tfc_nombre', $modelo->getTfc_nombre (),$stmt);
		$stmt = str_replace (':tfc_poriva', $modelo->getTfc_poriva (),$stmt);
		$stmt = str_replace (':tfc_porretencion', $modelo->getTfc_porretencion (),$stmt);
		
		return $stmt;
	}

}
// End Class "DaoFacturacli"
?>