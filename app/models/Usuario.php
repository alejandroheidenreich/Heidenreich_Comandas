<?php

require_once './interfaces/IPersistencia.php';
require_once './models/ROL.php';

class Usuario implements IPersistencia
{
    public $id;
    public $usuario;
    public $clave;
    public $rol;

    public $token;

    public $expiracionToken;

    public $fechaBaja;

    public function __construct( /*$usuario, $clave, $rol, $id = false*/)
    {
        // $this->usuario = $usuario;
        // $this->clave = $clave;
        // $this->rol = $rol;
        // $this->id = $id;

    }

    public function __get($propiedad)
    {
        if (property_exists($this, $propiedad)) {
            return $this->$propiedad;
        } else {
            return null;
        }
    }

    public function __set($propiedad, $valor)
    {
        if (property_exists($this, $propiedad)) {
            $this->$propiedad = $valor;
        } else {
            echo "No existe " . $propiedad;
        }
    }


    public static function crear($user)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO usuarios (usuario, clave, rol) VALUES (:usuario, :clave, :rol)");
        $claveHash = password_hash($user->clave, PASSWORD_DEFAULT);
        $consulta->bindValue(':usuario', $user->usuario, PDO::PARAM_STR);
        $consulta->bindValue(':clave', $claveHash);
        $consulta->bindValue(':rol', $user->rol, PDO::PARAM_STR);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, usuario, clave, rol, token, expiracionToken FROM usuarios");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Usuario');
    }

    public static function obtenerUno($usuario)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, usuario, clave, rol, token, expiracionToken FROM usuarios WHERE usuario = :usuario");
        $consulta->bindValue(':usuario', $usuario, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Usuario');
    }

    public static function obtenerUnoPorID($id)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, usuario, clave, rol, token, expiracionToken FROM usuarios WHERE id = :id AND fechaBaja IS NULL");
        $consulta->bindValue(':id', $id, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Usuario');
    }

    public static function modificar($usuario)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE usuarios SET usuario = :usuario, clave = :clave, rol = :rol, token = :token, expiracionToken = :expiracionToken WHERE id = :id AND fechaBaja IS NULL");

        $consulta->bindValue(':usuario', $usuario->usuario, PDO::PARAM_STR);
        $consulta->bindValue(':clave', $usuario->clave, PDO::PARAM_STR);
        $consulta->bindValue(':rol', $usuario->rol, PDO::PARAM_STR);
        $consulta->bindValue(':token', $usuario->token, PDO::PARAM_STR);
        $consulta->bindValue(':expiracionToken', $usuario->expiracionToken, PDO::PARAM_STR);
        $consulta->bindValue(':id', $usuario->id, PDO::PARAM_INT);
        $consulta->execute();
    }

    public static function borrar($id)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE usuarios SET fechaBaja = :fechaBaja WHERE id = :id AND fechaBaja IS NULL");
        $fecha = new DateTime(date("d-m-Y"));
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->bindValue(':fechaBaja', date_format($fecha, 'Y-m-d H:i:s'));
        $consulta->execute();
    }

    public static function ValidarRol($rol)
    {

        if ($rol != Rol::SOCIO && $rol != Rol::BARTENDER && $rol != Rol::CERVECERO && $rol != Rol::COCINERO && $rol != Rol::MOZO && $rol != Rol::CANDYBAR) {
            return false;
        }
        return true;
    }

    public static function ValidarUserName($username)
    {
        $usuarios = Usuario::obtenerTodos();

        foreach ($usuarios as $user) {
            if ($user->usuario == $username) {
                return $user;
            }
        }
        return null;
    }

    public static function BuscarPorToken($token)
    {
        $usuarios = Usuario::obtenerTodos();

        foreach ($usuarios as $user) {

            if ($user->token == $token) {
                return $user;
            }
        }
        return null;
    }

    public static function ValidarExpiracionToken($usuario)
    {
        return $usuario->expiracionToken >= time();
    }

}