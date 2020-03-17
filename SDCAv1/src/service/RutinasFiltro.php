<?php
namespace service;


require_once PATH_RAIZ . 'service/EmailAdressValidator.php';


class RutinasFiltro
{

    /** 
     * comprobacion de que la string entregada tiene formato de Email
     * 
     * @param string $email
     * @return bool
     */
    public static function isEmailValidBasico(string $email): bool
    {
        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        } else {
            return true;
        }
    }
    public static function isEmailValid(string $email): bool
    {
        return (new EmailAddressValidator($email, 
            EmailAddressValidator::RFC_5322))->isValid();
    }
}

