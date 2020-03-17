<?php
namespace App\dao;


require_once (PATH_RAIZ . 'modelos/Usuarios.php');
require_once (PATH_RAIZ . 'service/excepciones/DaoException.php');
require_once (PATH_RAIZ . 'dao/DaoBase.php');



use modelos\Usuarios;
use service\DaoException;


/**
 * 
 * @author migarcia
 * 
 */
class DaoUsuarios extends DaoBase
{

    const SELECT_ALL = 'SELECT * FROM Usuarioss';
    const SELECT_WHERE = 'SELECT * FROM Usuarioss WHERE :where ORDER BY Usuarios_nombre';

    const SELECT_UNO = 'SELECT * FROM Usuarioss WHERE Usuarios_id = :id';
    const SELECT_UNO_MAIL = 'SELECT * FROM Usuarioss WHERE Usuarios_correo=:Usuarios_correo';

    const AUTORIZAR = 'SELECT * FROM Usuarioss WHERE  Usuarios_correo=:Usuarios_correo AND Usuarios_password=:Usuarios_password';

    const INSERTAR = 'INSERT into Usuarioss values (:id,:Usuarios_nombre,:Usuarios_apellidos,:Usuarios_correo,:Usuarios_password,:Usuarios_lastupdate)';

    const ACTUALIZA = 'UPDATE Usuarioss set Usuarios_nombre = :Usuarios_nombre,
                                        Usuarios_apellidos=:Usuarios_apellidos,
                                        Usuarios_correo=:Usuarios_correo,
                                        Usuarios_password=:Usuarios_password,
                                        Usuarios_lastupdate = :Usuarios_lastupdate
                                        WHERE Usuarios_id = :id ';

    const DELETE = 'DELETE FROM Usuarioss WHERE Usuarios_id = :id';



    function __construct(PDO $connection)
    {
        parent::__construct($connection, 'modelos\Usuarios');
    }

    /**
     * 
     * @param string $Usuarios_correo
     * @param string $Usuarios_password
     * @throws DaoException
     * @return mixed|NULL
     */
    function autoriza(string $Usuarios_correo,string $Usuarios_password)
    {
        try {
            $stmt = $this->pdo->prepare(self::AUTORIZAR);
            $stmt->bindValue(':Usuarios_correo', $Usuarios_correo);
            $stmt->bindValue(':Usuarios_password', $Usuarios_password);
            $Usuarios = $this->acceder($stmt);
            if ($stmt->rowCount()==1)
                 return $Usuarios[0];

        } catch (PDOException $e) {
            throw new DaoException($e->getMessage(), (int) $e->getCode(), null);
        }
        return null;
    }

    function obtenUsuarios($Usuarios_correo)
    {
        try {
            $stmt = $this->pdo->prepare(self::SELECT_UNO_MAIL);
            $stmt->bindValue(':Usuarios_correo', $Usuarios_correo);
            $Usuarios = $this->acceder($stmt);
            if ($stmt->rowCount()==1){
                return $Usuarios[0];
            }
            throw new DaoException("No existe el Usuarios solicitado", (int) 9999, null);
        } catch (PDOException $e) {
            throw new DaoException($e->getMessage(), (int) $e->getCode(), null);
        }
        return null;
    }




    function montaBind(string $orden, $Usuarios)
    {
        $stmt = $this->pdo->prepare($orden);
        $stmt->bindValue(':id', $Usuarios->getId());
        $stmt->bindValue(':Usuarios_nombre', $Usuarios->getUsuarios_nombre());
        $stmt->bindValue(':Usuarios_apellidos', $Usuarios->getUsuarios_apellidos());
        $stmt->bindValue(':Usuarios_correo', $Usuarios->getUsuarios_correo());
        $stmt->bindValue(':Usuarios_password', $Usuarios->getUsuarios_password());
        $stmt->bindValue(':Usuarios_lastupdate', $Usuarios->getUsuarios_lastupdate());
        return $stmt;
    }
    function montaBindDel(string $orden,  $Usuarios)
    {
        $stmt = $this->pdo->prepare($orden);
        $stmt->bindValue(':id', $Usuarios->getUsuarios_id());
        return $stmt;
    }
    function montaDebug(string $orden, Usuarios $Usuarios)
    {
        $paraDebug = $orden;
        $paraDebug= str_replace(':id', $Usuarios->getUsuarios_id(),$paraDebug);
        $paraDebug= str_replace(':Usuarios_nombre', $Usuarios->getUsuarios_nombre(),$paraDebug);
        $paraDebug= str_replace(':Usuarios_apellidos', $Usuarios->getUsuarios_apellidos(),$paraDebug);
        $paraDebug= str_replace(':Usuarios_correo', $Usuarios->getUsuarios_correo(),$paraDebug);
        $paraDebug= str_replace(':Usuarios_password', $Usuarios->getUsuarios_password(),$paraDebug);
        $paraDebug= str_replace(':Usuarios_lastupdate', $Usuarios->getUsuarios_lastupdate(),$paraDebug);

        return $paraDebug;
    }
}

