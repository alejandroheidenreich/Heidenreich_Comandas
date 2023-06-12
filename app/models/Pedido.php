<?php

require_once './models/Estado.php';
require_once './models/GeneradorCodigo.php';
class Pedido implements IPersistencia
{
    public $id;
    public $codigoPedido; // 5 caracteres
    public $idMesa;
    public $idProducto;
    public $idFactura;
    public $nombreCliente;
    public $estado;
    public $tiempoEstimado;
    public $tiempoInicio;
    public $tiempoFinal;

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


    public static function crear($pedido)
    {
        $codigo = GenerarCodigo(5);
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO pedidos (codigoPedido, idMesa, idProducto, nombreCliente, estado) VALUES (:codigoPedido, :idMesa, :idProducto, :nombreCliente, :estado)");
        $consulta->bindValue(':codigoPedido', $codigo, PDO::PARAM_STR);
        $consulta->bindValue(':idMesa', $pedido->idMesa, PDO::PARAM_INT);
        $consulta->bindValue(':idProducto', $pedido->idProducto, PDO::PARAM_INT);
        $consulta->bindValue(':nombreCliente', $pedido->nombreCliente, PDO::PARAM_STR);
        $consulta->bindValue(':estado', Estado::PENDIENTE, PDO::PARAM_STR);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, codigoPedido, idMesa, idProducto, nombreCliente, estado, tiempoEstimado, tiempoInicio, tiempoFinal, fechaBaja FROM pedidos");

        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
    }

    public static function obtenerUno($propiedad, $valor)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT numeroPedido, idMesa, puntaje, encuesta FROM pedidos WHERE :propiedad = :valor");
        $consulta->bindValue(':propiedad', $propiedad, PDO::PARAM_STR);
        $consulta->bindValue(':valor', $valor, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Pedido');
    }

    public static function modificar($pedido)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE pedidos SET SET idMesa = :idMesa, puntaje = :puntaje, encuesta = :encuesta WHERE numeroPedido = :numeroPedido");
        $consulta->bindValue(':idMesa', $pedido->mesa->id, PDO::PARAM_STR);
        $consulta->bindValue(':encuesta', $pedido->encuesta, PDO::PARAM_STR);
        $consulta->bindValue(':puntaje', $pedido->puntaje, PDO::PARAM_STR);
        $consulta->bindValue(':numeroPedido', $pedido->numeroPedido, PDO::PARAM_STR);
        $consulta->execute();
    }

    public static function borrar($pedido)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE pedidos SET fechaBaja = :fechaBaja WHERE id = :id");
        $fecha = new DateTime(date("d-m-Y"));
        $consulta->bindValue(':id', $pedido->id, PDO::PARAM_STR);
        $consulta->bindValue(':fechaBaja', date_format($fecha, 'Y-m-d H:i:s'));
        $consulta->execute();
    }


}