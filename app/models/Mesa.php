<?php

require_once './models/Estado.php';
require_once './models/GeneradorCodigo.php';


class Mesa implements IPersistencia
{
    public $id;
    public $codigoMesa;
    public $estado;


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


    public static function crear($mesa)
    {
        $codigo = GenerarCodigo(5);
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO mesas (codigoMesa, estado) VALUES (:codigoMesa, :estado)");
        $consulta->bindValue(':codigoMesa', $codigo, PDO::PARAM_STR);
        $consulta->bindValue(':estado', Estado::CERRADA, PDO::PARAM_STR);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, codigoMesa, estado FROM mesas");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Mesa');
    }

    public static function obtenerUno($valor)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, codigoMesa, estado FROM mesas WHERE id = :valor");
        $consulta->bindValue(':valor', $valor, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Mesa');
    }
    public static function obtenerUnoPorCodigo($valor)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, codigoMesa, estado FROM mesas WHERE codigoMesa = :valor");
        $consulta->bindValue(':valor', $valor, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Mesa');
    }

    public static function modificar($mesa)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE mesas SET estado = :estado WHERE id = :id");
        $consulta->bindValue(':id', $mesa->id, PDO::PARAM_INT);
        $consulta->bindValue(':estado', $mesa->estado, PDO::PARAM_STR);
        $consulta->execute();

    }

    public static function borrar($id)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE mesas SET estado = :estado WHERE id = :id");
        $fecha = new DateTime(date("d-m-Y"));
        $consulta->bindValue(':id', $id, PDO::PARAM_STR);
        $consulta->bindValue(':estado', Estado::BAJA, PDO::PARAM_STR);
        $consulta->execute();
    }

    public static function obtenerCuenta($codigoPedido)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta(
            "SELECT p.idMesa , SUM(pr.precio)
            FROM pedidos as p
            INNER JOIN productos as pr ON p.idProducto = pr.id
            WHERE p.codigoPedido = :codigoPedido"
        );
        $consulta->bindValue(':codigoPedido', $codigoPedido, PDO::PARAM_STR);

        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function obtenerMesaPorCodigoPedido($codigoPedido)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta(
            "SELECT m.id, m.codigoMesa, m.estado
            FROM mesas as m
            INNER JOIN pedidos as p ON p.idMesa = m.id
            WHERE p.codigoPedido = :codigoPedido"
        );
        $consulta->bindValue(':codigoPedido', $codigoPedido, PDO::PARAM_STR);

        $consulta->execute();

        return $consulta->fetchObject('Mesa');

    }
    public static function obtenerUsosMesas()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta(
            "SELECT m.id, COUNT(*) as cantidad
            FROM pedidos as p
            INNER JOIN mesas as m ON p.idMesa = m.id"
        );


        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }
}