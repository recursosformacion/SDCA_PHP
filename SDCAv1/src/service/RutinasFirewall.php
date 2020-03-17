<?php
namespace service;

class RutinasFirewall
{

    public static function desprotegerIP($ip)
    {
        if (self::is_csfPresent())
            exec('sudo csf -ar ' . $ip);
    }

    public static  function protegeIP($ip)
    {
        if (self::is_csfPresent())
            exec('sudo csf -a ' . $ip . ' Local kiosko desde ' . date("Y-m-d H:i:s"));
    }

    public static  function is_csfPresent()
    {
        $rut = exec('sudo csf - v');
        return (strpos(" " . $rut, 'csf:') !== false);
    }
}

