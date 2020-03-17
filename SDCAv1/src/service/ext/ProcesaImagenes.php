<?php
namespace service;

require_once PATH_RAIZ . 'service/ConstantesImagen.php';
require_once PATH_RAIZ . 'service/excepciones/ServicioException.php';
require_once PATH_RAIZ . 'vendor/autoload.php';

use FFMpeg;
use Exception;



class ProcesaImagenes
{

    public static $errores = [];

    static function createGallery($pathToImages)
    {
        $output = array();
        // open the directory
        $dir = opendir(PATH_RAIZ . $pathToImages);

        // loop through the directory
        while ($fname = readdir($dir)) {
            // strip the . and .. entries out
            if ($fname != '.' && $fname != '..') {
                $output[] = $fname;
            }
        }
        closedir($dir);
        return $output;
    }

    static function cargaImagen($request, $urlRec): String
    {
        ini_set('max_input_time', 300);
        ini_set('max_execution_time', 300);

        $newname = "";

        $extension = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
        $tipo = "";
        if (! (($_FILES["photo"]["type"] == "video/mp4") || ($_FILES["photo"]["type"] == "audio/mp3") || ($_FILES["photo"]["type"] == "audio/wma") || ($_FILES["photo"]["type"] == "image/pjpeg") || ($_FILES["photo"]["type"] == "image/gif") || ($_FILES["photo"]["type"] == "image/png") || ($_FILES["photo"]["type"] == "image/jpeg"))) {
            $tipo = $_FILES["photo"]["type"];
        }
        if (($_FILES["photo"]["size"] > 30000000) || ($_FILES["photo"]["size"] == 0) || (! in_array($extension, EXTENSIONES_AUTORIZADAS)) || ($_FILES["photo"]["error"] != 0) || $tipo |= "") {
            self::$errores[] = "Error subiendo el fichero." . $_FILES['photo']['name'] . "<br/>\n<b>" . ERRORES_IMAGEN[$_FILES["photo"]["error"]] . "</b><br/>" . $tipo;
        } else {
            $newname = date('dmYHis') . str_replace(" ", "", mb_convert_encoding($_FILES['photo']['name'], 'UTF-8'));
            $newname = str_replace('(', '_', $newname);
            $newname = str_replace(')', '_', $newname);
            $url = PATH_RAIZ . $urlRec . CD . $newname;
            if (move_uploaded_file($_FILES['photo']['tmp_name'], $url)) {
                self::$errores[] = "Fichero subidos correctamente.\n";
                if (in_array(pathinfo($url, PATHINFO_EXTENSION), VIDEO)) {
                    $sec = 2;
                    $movie = $url;
                    $thumbnail = $url . '.jpg';
                    try {
                        $ffmpeg = FFMpeg\FFMpeg::create();
                        $video = $ffmpeg->open($movie);
                        $frame = $video->frame(FFMpeg\Coordinate\TimeCode::fromSeconds($sec));
                        $frame->save($thumbnail);                     
                    } catch (Exception $e) {
                        logKioskoError($e);
                    }
                }
            } else {
                logKiosko("Error subiendo fichero ". $_FILES['photo']['name'] ." #".$_FILES["photo"]["error"], "cargaImagen");
            }
        }
        return $newname;
    }

    static function borraImagen($urlBase, $nombre)
    {
        $url = PATH_RAIZ . $urlBase . CD . $nombre;
        try {
            unlink($url);
            if (in_array(pathinfo($url, PATHINFO_EXTENSION), VIDEO)) {
                unlink($url . '.jpg');
            }
        } catch (Exception $e) {}
        return;
    }

    static function borraCarpeta($dir)
    {
        /**
         * * comprobar que estoy debajo de "datos"
         */
        if (substr($dir, 0, 5) == "datos") {
            if (is_dir($dir)) {
                $objects = scandir($dir);
                foreach ($objects as $object) {
                    if ($object != "." && $object != "..") {
                        if (filetype($dir . "/" . $object) == "dir")
                            rrmdir($dir . "/" . $object);
                        else
                            unlink($dir . "/" . $object);
                    }
                }
                reset($objects);
                rmdir($dir);
            }
        } else {
            if ($dir != "")
                throw new ServicioException("Se intenta borrar un directorio erroneo " . $dir, 0);
        }
    }

    static function crearCarpetaAuto($tipo, $obj)
    {
        $nombre = "";
        if ($tipo == "grupo")
            $nombre = $obj->getGrupo_url();
        if ($tipo == "cliente")
            $nombre = $obj->getCliente_url();
        if ($tipo == "local")
            $nombre = $obj->getLocal_url();
        if ($tipo == "promo")
            $nombre = $obj->getPromo_url();
        if ($nombre == "") {
            $carpeta = "datos" . CD . $tipo . sprintf("%'.06d", $obj->getId());
            $carpetab = PATH_RAIZ . $carpeta;
        } else {
            $carpeta = $nombre;
        }
        if (substr($carpeta, 0, 4) != "http") {
            $carpetab = PATH_RAIZ . $carpeta;
            static::crearCarpeta($carpetab);
        }
        return $carpeta;
    }

    static function crearCarpeta($carpeta)
    {
        if (! is_dir($carpeta)) {
            mkdir($carpeta, 0777, true);
        }
    }
}

