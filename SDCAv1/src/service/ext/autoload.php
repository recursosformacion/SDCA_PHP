<?php
function Autoloader($className) {
    $className = str_replace("\\", "/", $className);
    $fileName = $className . '.php';
    require_once($fileName);
}
