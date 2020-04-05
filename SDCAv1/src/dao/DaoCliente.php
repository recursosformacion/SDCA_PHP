<?php
declare (strict_types = 1);
namespace App\dao;


/*******************************************************************************
* Class Name:       DaoCliente
* File Name:        DaoCliente.php
* Generated:        Saturday, Apr 4, 2020 - 13:35:13 CEST
*  - for Table:     tipo_cliente
*   - in Database:  contabilidadautonomos
* Created by: Daoclass 
********************************************************************************/

// Files required by class:
require_once ("DaoBase.php");

use PDOStatement;
use App\dao\DaoBase;


// Begin Class "DaoCliente"
class DaoCliente extends DaoBase{
	
	// ************ Declaracion de variables
	const SELECT_ALL 	 = "SELECT * FROM tipo_cliente  ORDER BY tc_nombre_tipo";
	const SELECT_WHERE	 = "SELECT * FROM FROM tipo_cliente WHERE :where  ORDER BY tc_nombre_tipo";
	const SELECT_UNO 	 = "SELECT * FROM  tipo_cliente  WHERE id_tipocliente = :id_tipocliente ";
	const INSERTAR 	 = "INSERT into tipo_cliente values (:id_tipocliente,:tc_nombre_tipo,:tc_nombre_descripcion)";
	const ACTUALIZA 	 = "UPDATE tipo_cliente  set tc_nombre_tipo= :tc_nombre_tipo,tc_nombre_descripcion= :tc_nombre_descripcion
                                        WHERE id_tipocliente = :id_tipocliente  ";
	const DELETE 	 = "DELETE FROM tipo_cliente WHERE id_tipocliente = :id_tipocliente ";
	
	
	// Class Constructor
	public function __construct () {
		parent::__construct ("App\modelos\Tipo_cliente");
	}
	
	// Class Destructor
	public function __destruct () {
	
	}
	
	// Montar para SQL
	public function montaBind (string $orden, $modelo): PDOStatement {
		$stmt = $this->pdo->prepare ($orden);
		$stmt->bindValue (':id_tipocliente', $modelo->getId_tipocliente ());
		$stmt->bindValue (':tc_nombre_tipo', $modelo->getTc_nombre_tipo ());
		$stmt->bindValue (':tc_nombre_descripcion', $modelo->getTc_nombre_descripcion ());
		
		
		return $stmt;
	}
	
	// Montar para DELETE SQL
	public function montaBindDel (string $orden, $modelo): PDOStatement {
		$stmt = $this->pdo->prepare ($orden);
		$stmt->bindValue (':id_tipocliente', $modelo->getId_tipocliente ());
		
		return $stmt;
	}
	
	// Montar para SQL
	public function montaDebug (string $orden, $modelo): string {
		$stmt = $orden;
		$stmt = str_replace (':id_tipocliente', $modelo->getId_tipocliente (),$stmt);
		$stmt = str_replace (':tc_nombre_tipo', $modelo->getTc_nombre_tipo (),$stmt);
		$stmt = str_replace (':tc_nombre_descripcion', $modelo->getTc_nombre_descripcion (),$stmt);
		
		return $stmt;
	}

}
// End Class "DaoCliente"
?>