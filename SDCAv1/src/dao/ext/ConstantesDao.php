<?php
namespace dao;

use App\modelos\ModeloBase;




/**
 * Recibe una seleccion, en donde $lista puede ser un select sencillo
 * o un seloect de objetos, y obtiene una lista para "select"
 * 
 * @param array $campos con los nombres de campo a utilizar
 * @param array $lista con los registros seleccionados
 * @param int $current  el valor actual del selec
 * @return string para HTML->SELECT
 */
function montaSelect(array $campos, array $lista, $current): string
{
    $campo1 = "get" . ucfirst($campos[0]);
    $campo2 = "get" . ucfirst($campos[1]);
    $salida = "";
    foreach ($lista as $elemento) {
        if ($elemento instanceof ModeloBase) {
            $salida .= '<option value="' . $elemento->$campo1() . '" ';
            if ($elemento->$campo1() == $current) {
                $salida .= " selected ";
            }
            $salida .= '>' . $elemento->$campo2() . '</option>';
        } else {
            $salida .= '<option value="' . $elemento[$campos[0]] . '" ';
            if ($elemento[$campos[0]] == $current) {
                $salida .= " selected ";
            }
            $salida .= '>' . $elemento[$campos[1]] . '</option>';
        }
    }
    return $salida;
}
?>