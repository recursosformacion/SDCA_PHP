<?php
const VIDEO = array(
    "mp3",
    "mp4",
    "wma"
);
const EXTENSIONES_AUTORIZADAS = array(
    "jpg",
    "jpeg",
    "gif",
    "png",
    "mp3",
    "mp4",
    "wma"
);
const ERRORES_IMAGEN = array(
    0 => 'No hay error, fichero subido con éxito',
    1 => 'El fichero subido excede la directiva upload_max_filesize de php.ini',
    2 => 'El fichero subido excede la directiva MAX_FILE_SIZE especificada en el formulario HTML.',
    3 => 'El fichero fue sólo parcialmente subido.',
    4 => 'No se subió ningún fichero.',
    6 => 'Falta la carpeta temporal. Introducido en PHP 5.0.3.',
    7 => 'No se pudo escribir el fichero en el disco. Introducido en PHP 5.1.0.',
    8 => 'UPLOAD_ERR_EXTENSION'
);
