<?php

require_once './interfaces/IPersistencia.php';

class Usuario implements IPersistencia
{
    public $id;
    public $usuario;
    public $clave;
    public $rol;

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
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, usuario, clave, rol FROM usuarios");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Usuario');
    }

    public static function obtenerUno($propiedad, $valor)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, usuario, clave, rol FROM usuarios WHERE " . $propiedad . " = :valor");
        //$consulta = $objAccesoDatos->prepararConsulta("SELECT id, usuario, clave, rol FROM usuarios WHERE :propiedad = :valor");
        //$consulta->bindValue(':propiedad', $propiedad, PDO::PARAM_STR);
        $consulta->bindValue(':valor', $valor, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Usuario');
    }

    public static function modificar($usuario)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE usuarios SET usuario = :usuario, clave = :clave, rol = :rol WHERE id = :id");
        $consulta->bindValue(':usuario', $usuario->usuario, PDO::PARAM_STR);
        $consulta->bindValue(':clave', $usuario->clave, PDO::PARAM_STR);
        $consulta->bindValue(':rol', $usuario->rol, PDO::PARAM_STR);
        $consulta->bindValue(':id', $usuario->id, PDO::PARAM_INT);
        $consulta->execute();
    }

    public static function borrar($usuario)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE usuarios SET fechaBaja = :fechaBaja WHERE id = :id");
        $fecha = new DateTime(date("d-m-Y"));
        $consulta->bindValue(':id', $usuario->id, PDO::PARAM_INT);
        $consulta->bindValue(':fechaBaja', date_format($fecha, 'Y-m-d H:i:s'));
        $consulta->execute();
    }
}