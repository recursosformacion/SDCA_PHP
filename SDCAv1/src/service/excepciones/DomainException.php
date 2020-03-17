<?php
namespace service;

use Exception;

class DomainException extends \Exception
{
    // Redefinir la excepci�n, por lo que el mensaje no es opcional
    public function __construct(string $message = "***", int $code = 0, Exception $previous = null) {
        // algo de codigo
     //   echo $message;
        // aseg�rese de que todo est� asignado apropiadamente
        parent::__construct($message, $code, $previous);
    }

    // representaci�n de cadena personalizada del objeto
    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}

