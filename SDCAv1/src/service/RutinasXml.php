<?php
namespace App\service;

use SimpleXMLElement;

class RutinasXml
{

    public static function arrayToXML($array, $nombre)
    {
       // creating object of SimpleXMLElement
        $raiz='<?xml version="1.0" encoding="UTF-8"?>';
        $raiz.='<N' . $nombre . '>';
        $raiz.='</N' . $nombre . '>';

        $xml = new SimpleXMLElement($raiz);
        self::array_to_xml($array,$xml);
        return $xml->asXML();
    }



    // function defination to convert array to xml
    public static function array_to_xml( $data, &$xml_data ) {
        foreach( $data as $key => $value ) {
            if( is_numeric($key) ){
                $key = 'item'.$key; //dealing with <0/>..<n/> issues
            }
            if( is_array($value) ) {
                $subnode = $xml_data->addChild($key);
                self::array_to_xml($value, $subnode);
            } else {
                $xml_data->addChild("$key",htmlspecialchars("$value"));
            }
        }
    }

}

